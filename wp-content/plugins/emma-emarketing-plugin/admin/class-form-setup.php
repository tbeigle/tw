<?php

class Form_Setup {

    public static $key = 'emma_form_setup';

    public static $settings = array();

    function __construct() {

        add_action( 'admin_init', array( &$this, 'register_settings' ) );

        self::$settings = $this->get_settings_options();

    }

    public static function get_settings_options() {

        // load the settings from the database
        $settings_options = (array) get_option( self::$key );

        // merge with defaults
        $settings_options = array_merge( self::get_settings_defaults(), $settings_options );

        return $settings_options;

    }

    public static function get_settings_defaults() {
        $defaults = array(
            'include_firstname_lastname' => '1',
            'form_size' => 'medium',
            'email_placeholder' => '',
            'firstname_placeholder' => '',
            'lastname_placeholder' => '',
            'submit_txt' => 'Subscribe',
            'confirmation_msg' => 'Thanks for subscribing! you should receive a confirmation email shortly. Please check your spam folder, as occasionally this email may be perceived as spam',
            'powered_by' => 'no',
            'send_confirmation_email' => '1',
            'confirmation_email_subject' => 'You&apos;re recent subscription to Emma',
            'confirmation_email_msg' => 'Thanks for subscribing! This email confirms that you now have an active subscription to our newsletter!',
        );
        return $defaults;
    }

    function register_settings() {

        register_setting( self::$key, self::$key, array( &$this, 'sanitize_form_setup_settings' ) );

        add_settings_section( 'section_form_field_includes', 'Forms Fields', array( &$this, 'section_form_field_includes_desc' ), self::$key );

        add_settings_field( 'include_firstname_lastname', 'Include First and Last Name Fields', array( &$this, 'field_include_firstname_lastname' ), self::$key, 'section_form_field_includes' );

        add_settings_section( 'section_form_size', 'Select Form Size', array( &$this, 'section_form_size_desc' ), self::$key );

        add_settings_field( 'form_size', 'Form Size', array( &$this, 'field_form_size' ), self::$key, 'section_form_size' );

        add_settings_section( 'section_form_placeholders', 'Form Placeholders', array( &$this, 'section_form_placeholders_desc' ), self::$key );

        add_settings_field( 'email_placeholder', 'Email Placeholder', array( &$this, 'field_email_placeholder' ), self::$key, 'section_form_placeholders' );
        add_settings_field( 'firstname_placeholder', 'First Name Placeholder', array( &$this, 'field_firstname_placeholder' ), self::$key, 'section_form_placeholders' );
        add_settings_field( 'lastname_placeholder', 'Last Name Placeholder', array( &$this, 'field_lastname_placeholder' ), self::$key, 'section_form_placeholders' );
        add_settings_field( 'submit_button_text', 'Submit Button Text', array( &$this, 'field_submit_txt' ), self::$key, 'section_form_placeholders' );
        add_settings_field( 'confirmation_msg', 'Confirmation Message', array( &$this, 'field_confirmation_msg' ), self::$key, 'section_form_placeholders' );

        add_settings_section( 'section_confirmation_email', 'Confirmation Email', array( &$this, 'section_confirmation_email_desc' ), self::$key );
        add_settings_field( 'send_confirmation_email', 'Send Confirmation Email?', array( &$this, 'field_send_confirmation_email' ), self::$key, 'section_confirmation_email' );
        add_settings_field( 'confirmation_email_subject', 'Confirmation Email Subject', array( &$this, 'field_confirmation_email_subject' ), self::$key, 'section_confirmation_email' );
        add_settings_field( 'confirmation_email_textarea', 'Confirmation Email Message', array( &$this, 'field_confirmation_email_textarea' ), self::$key, 'section_confirmation_email' );

        add_settings_section( 'section_powered_by', 'Give Props', array( &$this, 'section_powered_by_desc'), self::$key );
        add_settings_field( 'powered_by', 'Add "Powered By Emma" Link', array( &$this, 'field_powered_by'), self::$key, 'section_powered_by' );

        // form preview
        // add_settings_field( 'form_preview', 'Form Preview', array( &$this, 'field_form_preview' ), self::$key, 'section_form_placeholders' );
    }

    function section_confirmation_email_desc() {
        echo '<p>Configure the confirmation email</p>';
    }

    function field_send_confirmation_email() { ?>
        <label for="send_confirmation_email_yes">Yes</label>
        <input id="send_confirmation_email_yes"
           type="radio"
           name="<?php echo self::$key; ?>[send_confirmation_email]"
           value="1" <?php checked( '1', ( self::$settings['send_confirmation_email'] ) ); ?>
        />
        <label for="send_confirmation_email_no">No</label>
        <input id="send_confirmation_email_no"
               type="radio"
               name="<?php echo self::$key; ?>[send_confirmation_email]"
               value="0" <?php checked( '0', ( self::$settings['send_confirmation_email'] ) ); ?>
                />
    <?php }

    function field_confirmation_email_subject() { ?>

        <input id="confirmation_email_subject"
           type="text"
           size="100"
           name="<?php echo self::$key; ?>[confirmation_email_subject]"
           value="<?php echo esc_attr( self::$settings['confirmation_email_subject'] ); ?>"
            />
    <?php }

    function field_confirmation_email_textarea() { ?>

        <textarea id="confirmation_email_msg"
              name="<?php echo self::$key; ?>[confirmation_email_msg]"
              rows="6"
              cols="40" >
        <?php
        // avoid undefined index by checking for the value 1st, then assigning it nothing if it has not been set.
        $confirmation_email_msg = isset( self::$settings['confirmation_email_msg'] ) ? esc_attr( self::$settings['confirmation_email_msg'] ) : '';
        echo $confirmation_email_msg;
        ?>
        </textarea>

    <?php }

    function section_form_field_includes_desc() {  }
    function field_include_firstname_lastname() { ?>
        <label for="include_firstname_lastname_yes">Yes</label>
        <input id="include_firstname_lastname_yes"
           type="radio"
           name="<?php echo self::$key; ?>[include_firstname_lastname]"
           value="1" <?php checked( '1', ( self::$settings['include_firstname_lastname'] ) ); ?>
        />
        <label for="include_firstname_lastname_no">No</label>
        <input id="include_firstname_lastname_no"
           type="radio"
           name="<?php echo self::$key; ?>[include_firstname_lastname]"
           value="0" <?php checked( '0', ( self::$settings['include_firstname_lastname'] ) ); ?>
        />
    <?php }

    function section_form_size_desc() {  }
    function field_form_size() { ?>
        <input id="form_size_x_small"
           type="radio"
           name="<?php echo self::$key; ?>[form_size]"
           value="x-small" <?php checked( 'x-small', ( self::$settings['form_size'] ) ); ?>
        />
        <label for="form_size_x_small">Extra Small ( 200px )</label>
        <br />
        <input id="form_size_small"
           type="radio"
           name="<?php echo self::$key; ?>[form_size]"
           value="small" <?php checked( 'small', ( self::$settings['form_size'] ) ); ?>
        />
        <label for="form_size_small">Small ( 280px )</label>
        <br />
        <input id="form_size_medium"
           type="radio"
           name="<?php echo self::$key; ?>[form_size]"
           value="medium" <?php checked( 'medium', ( self::$settings['form_size'] ) ); ?>
        />
        <label for="form_size_medium">Medium ( 300px )</label>
        <br />
        <input id="form_size_large"
           type="radio"
           name="<?php echo self::$key; ?>[form_size]"
           value="large" <?php checked( 'large', ( self::$settings['form_size'] ) ); ?>
        />
        <label for="form_size_large">Large ( 340px )</label>
    <?php }

    function section_form_placeholders_desc() {  }
    function field_email_placeholder() { ?>
        <input id="emma_email_placeholder"
           type="text"
           size="40"
           name="<?php echo self::$key; ?>[email_placeholder]"
           value="<?php echo esc_attr( self::$settings['email_placeholder'] ); ?>"
        />
    <?php }

    function field_firstname_placeholder() {?>
        <input id="emma_firstname_placeholder"
           type="text"
           size="40"
           name="<?php echo self::$key; ?>[firstname_placeholder]"
           value="<?php echo esc_attr( self::$settings['firstname_placeholder'] ); ?>"
        />
    <?php }

    function field_lastname_placeholder() { ?>
        <input id="emma_lastname_placeholder"
           type="text"
           size="40"
           name="<?php echo self::$key; ?>[lastname_placeholder]"
           value="<?php echo esc_attr( self::$settings['lastname_placeholder'] ); ?>"
        />
    <?php }

    function field_submit_txt() { ?>
        <input id="emma_submit_txt"
           type="text"
           size="40"
           name="<?php echo self::$key; ?>[submit_txt]"
           value="<?php echo esc_attr( self::$settings['submit_txt'] ); ?>"
        />
    <?php }

    function field_confirmation_msg() { ?>
        <textarea id="emma_confirmation_msg"
              name="<?php echo self::$key; ?>[confirmation_msg]"
              rows="6"
              cols="40" >
        <?php
        // avoid undefined index by checking for the value 1st, then assigning it nothing if it has not been set.
        $confirmation_msg = isset( self::$settings['confirmation_msg'] ) ? esc_attr( self::$settings['confirmation_msg'] ) : '';
        echo $confirmation_msg;
        ?>
        </textarea>
    <?php
        // someday we're gonna use the native wp_editor, and let them dump html in thar...
        //$args = array("textarea_name" => "emma_options[confirmation_msg]");
        //wp_editor( $options['confirmation_msg'], "emma_options[confirmation_msg]", $args );
    }

    // Powered By
    function section_powered_by_desc() {
        echo 'Would you like to add a stylish "Powered by Emma" logo and link to your form? It would really help us out!';
    }

    function field_powered_by() { ?>
        <label for="powered_by_yes">Yes</label>
        <input id="powered_by_yes"
           type="radio"
           name="<?php echo self::$key; ?>[powered_by]"
           value="yes" <?php checked( 'yes', ( self::$settings['powered_by'] ) ); ?>
        />
        <label for="powered_by_no">No</label>
        <input id="powered_by_no"
           type="radio"
           name="<?php echo self::$key; ?>[powered_by]"
           value="no" <?php checked( 'no', ( self::$settings['powered_by'] ) ); ?>
        />
    <?php }

    // Form preview section
    // for version 2.0,
    function field_form_preview() {
        echo '<div style="position: fixed; top: 130px; right: 50px;">';
        $preview_form = new Form( self::$settings );
        echo $preview_form->output();
        echo '</div>';
    }

    function sanitize_form_setup_settings( $input ) {

        $valid_input = array();

        // check which button was clicked, submit or reset,
        $submit = ( ! empty( $input['submit'] ) ? true : false );
        $reset = ( ! empty( $input['reset'])  ? true : false );

        if ( $submit ) {

            // text inputs
            $valid_input['include_firstname_lastname']  = $input['include_firstname_lastname'];
            $valid_input['form_size']                   = $input['form_size'];
            $valid_input['email_placeholder']           = wp_kses( $input['email_placeholder'], '' );
            $valid_input['firstname_placeholder']       = wp_kses( $input['firstname_placeholder'], '' );
            $valid_input['lastname_placeholder']        = wp_kses( $input['lastname_placeholder'], '' );
            $valid_input['submit_txt']                  = wp_kses( $input['submit_txt'], '' );
            $valid_input['confirmation_msg']            = wp_kses( $input['confirmation_msg'], '' );
            $valid_input['powered_by']                  = $input['powered_by'];
            $valid_input['send_confirmation_email']     = $input['send_confirmation_email'];
            $valid_input['confirmation_email_subject']  = wp_kses( $input['confirmation_email_subject'], '' );
            $valid_input['confirmation_email_msg']      = wp_kses( $input['confirmation_email_msg'], '' );

        } elseif ( $reset ) {

            // get defaults
            $default_input = $this->get_settings_defaults();
            // assign to valid input
            $valid_input = $default_input;

        }

        return $valid_input;

    }


}
