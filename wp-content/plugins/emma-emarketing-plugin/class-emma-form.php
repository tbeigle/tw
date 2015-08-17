<?php 

include_once( 'admin/class-account-information.php' );
include_once( 'admin/class-form-setup.php' );

class Emma_Form {

    private $form_setup_settings = array();
    private $account_information_settings = array();

    private $status_txt;

    private $emma_api;

    function __construct () {

        // get authorization data from plugin options
        $this->account_information_settings = (array) get_option( Account_Information::$key );
        $this->form_setup_settings = (array) get_option( Form_Setup::$key );

        // instantiate new emma api class, pass in auth data
        $this->emma_api = new Emma_API( $this->account_information_settings['account_id'], $this->account_information_settings['publicAPIkey'], $this->account_information_settings['privateAPIkey'] );

    }

    // outputs the emma form,
    public function generate_form() {

        // check if the form has been submitted
        if ( isset($_POST['emma_form_submit']) ) {

            // validate form fields, if not valid, return status_txt
            if ( !is_email($_POST['emma_email']) ) {

                $this->status_txt = 'Please enter a valid email address';

            // process the form
            } else {

                // get data from form into transportable array
                $data = $this->process_form_data();

                // put members in appropriate group(s)
                $data = $this->assign_members_to_groups( $data );

                // call emma, import_single_member, pass in data
                $response = $this->emma_api->import_single_member( $data );

                // handle the response,
                // pass in wp_error or returned array from emma
                // get back object w/ status
                $handled_response = $this->emma_request_response_handler( $response );

                // check to see if the member was added,
                if ( $handled_response->status == 'member_added' ) {
                    // verify the member was added
                    $verified_member = $this->emma_verify_member( $handled_response );

                    // member successfully added
                    if ( $verified_member->status == 'member_verified' ) {

                        // get custom confirmation message, pass through to form
                        $this->status_txt = $this->form_setup_settings['confirmation_msg'];

                        // check to see if user wants to send out confirmation email
                        if ( $this->form_setup_settings['send_confirmation_email'] == '1' ) {
                            $this->send_confirmation_email($verified_member->body->email);
                        }
                    }

                    // if a wp_error comes back, pass it through to the status text
                    if ( $verified_member->status == 'wp_error' ) {
                        $this->status_txt = $verified_member->wp_error;
                    }

//                    print_r($handled_response);
//                    print_r($verified_member);

                }

                //
                if ( $handled_response->status == 'member_not_added' ) {

                    $this->status_txt = 'Member Not Added, Member may have already been added. Please Try Again.';

//                    print_r($handled_response);
                }

                if ( $handled_response->status == 'member_failed' ) {
                    $this->status_txt = 'Member is in limbo';
                    print_r($handled_response);
                }

            }

        }

        return $this->output_form();

    }


    public function process_form_data() {

        // construct data array to send to emma, array structure parallels emma api data request object
        $form_data = array();
        $form_data['email'] = $_POST['emma_email'];
        if ( isset($_POST['emma_firstname']) ) $form_data['fields']['firstname'] = $_POST['emma_firstname'];
        if ( isset($_POST['emma_lastname']) ) $form_data['fields']['lastname'] = $_POST['emma_lastname'];

        return $form_data;
    }

    public function assign_members_to_groups( $data ) {

        // assign members to group(s), based on settings
        if ( $this->account_information_settings['group_active'] !== '0' ) {

            // the api accepts an array of integers.
            // pass in the active group id as an integer.
            $group_ids = (int)$this->account_information_settings['group_active'];

            // for now, we're just passing in one group,
            $data['group_ids'] = array($group_ids);

        }

        // if they're not assigning any members to groups, just pass it on thru
        return $data;
    }


    // handles requests returned from the Emma_API class, has to deal w/ WP_Error as well as return objects
    public function emma_request_response_handler( $response ) {

        // if the API call returns an array
        if ( is_array($response) ) {

            // decode the JSON from the request body
            $response_body = json_decode( $response['body'] );

            $response_object = new stdClass();

            // convert to object
            foreach ( $response as $key => $value ) {
                $response_object->$key = $value;
            }
            // put the body back
            $response_object->body = $response_body;

            $response = $response_object;

            // check if the member was added
            if ( $response_body->added == true ) {
                $response->status = 'member_added';
            } else if ( $response_body->added == false ) {
                $response->status = 'member_not_added';
            } else {
                $response->status = 'member_fail';
            }

        }

        // check if the response is a wp_error
        if( is_wp_error( $response ) ) {

            $response->status = 'wp_error';
            $response->wp_error = 'Something went wrong! Please try to submit the form again,';
            // get the wordpress error
            $response->wp_error .= '<pre>' . $response->get_error_message() . '</pre>';

        }

        return $response;
    }

    public function emma_verify_member( $handled_response ) {

        // call get_member_detail to verify the member was added, using their member ID
        $verified_member = $this->emma_api->get_member_detail( $handled_response->body->member_id );

        if( is_wp_error( $verified_member ) ) {

            $verified_member->status = 'wp_error';
            $verified_member->wp_error =  'Something went wrong! Please try to submit the form again,';
            // get the wordpress error
            $verified_member->wp_error .= '<pre>' . $verified_member->get_error_message() . '</pre>';

        } else {

            $verified_member_body = json_decode( $verified_member['body'] );

            $verified_member_object = new stdClass();

            // convert to object
            foreach ( $verified_member as $key => $value ) {
                $verified_member_object->$key = $value;
            }
            // put the body back
            $verified_member_object->body = $verified_member_body;

            $verified_member = $verified_member_object;


//            if ( $verified_member_body->status == "active" ) {

                $verified_member->status = 'member_verified';
//            }

        }

        return $verified_member;
    }


        // if they're unverified
//        if ( $verified_member == false ) {
//            $verified_member->status = 'member_already_added';
//        } else if ( $verified_member == true ) {
//            $verified_member = 'member_verified';
//        } else {
//            $verified_member->status = 'huh';
//        }
//
//
//        } else {
//
//            // pull the json out of the body and decode it for php
//            $verified_member = json_decode( $verified_member['body'] );
//
//        }

//        // check if the returned is a wp_error
//        if( is_wp_error( $response ) ) {
//
//            $response->status = 'Something went wrong! Please try to submit the form again,';
////			$status_txt =  'Something went wrong! Please try to submit the form again.';
//            // get the wordpress error
//            $response->status .= '<pre>' . $response->get_error_message() . '</pre>';
//
//        } else {
//
//            // decode the JSON from the request body
//            $response = json_decode( $response['body'] );
//
//            // check if the member was added
//            if ( $response->added == true ) {
//
//                // call get_member_detail to verify the member was added, using their member ID
//                $verify_member = $emma_api->get_member_detail( $response->member_id );
//
//                // if they're unverified
//                if ( $verify_member == false ) {
//
//                    $response->status_txt = 'This member has already been added.';
//
//                } else {
//                    $response->status_txt = 'success';
//                }
//
//
//            } else {
//
//                $response->status_txt = 'Member not added';
//            }
//
//        } // end if / else
//        // check to see if it throws a wordpress error
//        if ( is_wp_error( $response ) ) {
//
//            $this->status_txt =  '<div class="e2ma-error">Something misfired. Please check your API keys and try again,</div>';
//            // get the wordpress error
//            $this->status_txt .= '<pre>' . $response->get_error_message() . '</pre>';
//
//    }
//
//        return $response;
//
//    }

    public function send_confirmation_email( $email ) {

        // build email data
//        $to = $email;
        $to = $email;
        $subject = $this->form_setup_settings['confirmation_email_subject'];
        $message = $this->form_setup_settings['confirmation_email_msg'];
        $headers[] = "From:" . get_bloginfo('admin_email'); // uses site admin's email Settings -> General

        // send it.
        $mail_return = wp_mail( $to, $subject, $message, $headers );
//        $mail_return = wp_mail( $to, $subject, $message );

        if ( $mail_return == true ) {
            $this->status_txt .= 'A Confirmation eMail has been sent';
        } else if ( $mail_return == false ) {
            $this->status_txt .= '  A Confirmation eMail could not be sent to that address';
        } else {
            $this->status_txt .= '  Your Mail is in Limbo';
        }

    }

    public function output_form() {

        // output form markup
        $emma_form = '<div id="emma-form" class="' . $this->form_setup_settings['form_size'] . '">';
        $emma_form .= '<div class="emma-wrap">';
        $emma_form .= '<form id="emma-subscription-form" action="' . htmlspecialchars( $_SERVER['REQUEST_URI'] ) . '" method="post" accept-charset="utf-8">';
        $emma_form .= '<ul id="emma-form-elements">';
        $emma_form .= '<li class="emma-form-row">';
        $emma_form .= '<label class="emma-form-label" for="emma-email"> Email <span class="emma-required">*</span> </label>';
        $emma_form .= '<input id="emma-email" class="emma-form-input" type="text" name="emma_email" size="30" placeholder="' . $this->form_setup_settings['email_placeholder'] . '">';
        $emma_form .= '</li>';
        // check if they want to include the first and last name fields
        // and include custom placeholder text
        if ( $this->form_setup_settings['include_firstname_lastname'] == '1' ) {
            $emma_form .= '<li class="emma-form-row">';
            $emma_form .= '<label class="emma-form-label" for="emma-firstname">First Name</label>';
            $emma_form .= '<input id="emma-firstname" class="emma-form-input" type="text" name="emma_firstname" size="30" placeholder="' . $this->form_setup_settings['firstname_placeholder'] . '">';
            $emma_form .= '</li>';
            $emma_form .= '<li class="emma-form-row">';
            $emma_form .= '<label class="emma-form-label" for="emma-lastname">Last Name</label>';
            $emma_form .= '<input id="emma-lastname"  class="emma-form-input" type="text" name="emma_lastname"  size="30" placeholder="' . $this->form_setup_settings['lastname_placeholder'] . '">';
            $emma_form .= '</li>';
        }
        $emma_form .= '<li class="emma-form-row emma-form-row-last">';
        $emma_form .= '<span class="emma-form-label-required">';
        $emma_form .= '<span class="emma-required">*</span> required </span>';
        $emma_form .= '<input id="emma-form-submit" type="submit" name="emma_form_submit" value="' . $this->form_setup_settings['submit_txt'] . '">';
        $emma_form .= '</li>';
        $emma_form .= '</ul>';
        $emma_form .= '</form>';

        // output status message
        if ( isset($_POST['emma_form_submit']) ) {
            $emma_form .= '<div class="emma-status">' . $this->status_txt . '</div>';
        }

        if ( $this->form_setup_settings['powered_by'] == 'yes' ) {

            $emma_form .= '<div id="powered-by">Powered By:';
            $emma_form .= '<a href="http://myemma.com/" target="_blank">Emma</a>';
            $emma_form .= '</div>';

        }

        $emma_form .= '</div><!-- end .emma-wrap -->';

        $emma_form .= '</div><!-- end #emma-form -->';

        return $emma_form;

    } // end output_form

} // end Class Emma_Form

