<?php

class Account_Information {

    public static $key = 'emma_account_information';

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
            'plugin_version' => '1.0.5',
            'account_id' => '',
            'publicAPIkey' => '',
            'privateAPIkey' => '',
            'logged_in' => 'false',
            'groups' => array(),
            'group_active' => '',
        );
        return $defaults;
    }

    function register_settings() {


        // register_setting( $option_group, $option_name, $sanitize_callback );
        register_setting( self::$key, self::$key, array( &$this, 'sanitize_account_information_settings' ) );

        add_settings_section( 'section_login', 'Emma Account Login Information', array( &$this, 'section_login_desc' ), self::$key );

        add_settings_field( 'public_api_key', 'Public API Key', array( &$this, 'field_public_api_key' ), self::$key, 'section_login' );
        add_settings_field( 'private_api_key', 'Private API Key', array( &$this, 'field_private_api_key' ), self::$key, 'section_login' );
        add_settings_field( 'account_id', 'Account ID', array( &$this, 'field_account_id' ), self::$key, 'section_login' );

        add_settings_section( 'section_groups', 'Add New Members to Group', array( &$this, 'section_groups_desc' ), self::$key );

        add_settings_field( 'logged_in', '', array( &$this, 'field_logged_in' ), self::$key, 'section_groups' );

        // check to see if logged in to emma
        if ( self::$settings['logged_in'] == 'true' ) {

            add_settings_field( 'groups', 'Select Group', array( &$this, 'field_groups' ), self::$key, 'section_groups' );

        }


    }

    function section_login_desc() { echo 'You must have an Emma Account with a API key. If you&apos;re unsure if your account is on the new API, contact Emma'; }
    function field_account_id() { ?>
        <input id="emma_account_id"
           type="text"
           size="7"
           name="<?php echo self::$key; ?>[account_id]"
           value="<?php echo esc_attr( self::$settings['account_id'] ); ?>"
        />
    <?php }
    function field_public_api_key() { ?>
        <input id="emma_publicapikey"
           type="text"
           size="20"
           name="<?php echo self::$key; ?>[publicAPIkey]"
           value="<?php echo esc_attr( self::$settings['publicAPIkey'] ); ?>"
        />
    <?php }
    function field_private_api_key() { ?>
        <input id="emma_privateapikey"
           type="text"
           size="20"
           name="<?php echo self::$key ?>[privateAPIkey]"
           value="<?php echo esc_attr( self::$settings['privateAPIkey'] ); ?>"
        />
    <?php }

    function section_groups_desc() {

        if ( self::$settings['logged_in'] == 'true' ) {
            echo 'Assign members to groups ( optional )';
        } else {
            echo 'Once you&apos;ve entered your account information and saved the changes, then you can choose from the available groups to assign new members to';
        }
    }

    function field_logged_in() { ?>
        <input id="emma_logged_in"
               type="hidden"
               name="<?php echo self::$key ?>[logged_in]"
               value="<?php echo esc_attr( self::$settings['logged_in'] ); ?>"
        />
    <?php }

    function field_groups() {

        $groups = self::$settings['groups'];

        // groups dropdown
        echo '<select id="emma_groups" name="' . self::$key . '[group_active]">';
        echo '<option value="000"> - select a group - </option>';

        foreach ( $groups as $group_key => $group_value ) {
            echo '<option value="' . $group_key . '"';
            if ( self::$settings['group_active'] == $group_key ) { echo "selected"; }
            echo '>' . $group_value . '</option>';
        }

        echo '</select>';

        // refresh button
        echo '<input style="margin-left: 20px;" type="submit" name="emma_account_information[refresh]" id="refresh" class="button-secondary" value="Refresh Groups" />';
    }

    function sanitize_account_information_settings( $input ) {

        // get the current options
        // $valid_input = self::$settings;
        $valid_input = array();

        // check which button was clicked, submit or reset,
        $submit = ( ! empty( $input['submit'] ) ? true : false );
        $reset = ( ! empty( $input['reset'])  ? true : false );
        $refresh = ( ! empty( $input['refresh']) ? true : false );

        // if the submit or refresh button was clicked
        if ( $submit || $refresh ) {

            /**
             * validate the account information settings, and add error messages
             * add_settings_error( $setting, $code, $message, $type )
             * $setting here refers to the $id of add_settings_field
             * add_settings_field( $id, $title, $callback, $page, $section, $args );
             */

            // account number

            // check if it's a number
            $valid_input['account_id'] = ( is_numeric($input['account_id']) ? $input['account_id'] : $valid_input['account_id'] );
            if ( $valid_input['account_id'] != $input['account_id'] ) {
                add_settings_error(
                    'account_id',
                    'emma_error',
                    'The Account Number can only contain numbers, no letters or alpha-numeric characters',
                    'error'
                );
            };

            // public API key
            if ( ( strlen($input['publicAPIkey']) == 20 ) && ( ctype_alnum($input['publicAPIkey']) ) ) {
                $valid_input['publicAPIkey'] = $input['publicAPIkey'];
            } else {
                add_settings_error(
                    'public_api_key',
                    'emma_error',
                    'The Public API Key can only contain letters and numbers, and should be 20 characters long',
                    'error'
                );
            }

            // private API key
            // make sure it's only 20 characters and contains only upper / lowercase letters and numbers
//            if ( preg_match('/[0-9a-zA-Z]{20}/', $input['privateAPIkey']) ) {
            if ( ( strlen($input['privateAPIkey']) == 20 ) && ( ctype_alnum($input['privateAPIkey']) ) ) {
                $valid_input['privateAPIkey'] = $input['privateAPIkey'];
            } else {
                add_settings_error(
                    'private_api_key',
                    'emma_error',
                    'The Private API Key can only contain letters and numbers, and should be 20 characters long',
                    'error'
                );
            }

            // get group data

            // instantiate a new Emma API class, pass login / auth data to it
            $emma_api = new Emma_API( $valid_input['account_id'], $valid_input['publicAPIkey'], $valid_input['privateAPIkey']);

            // get the groups for this account
            $groups = $emma_api->list_groups();

            // check if groups returned an error, or an answer
            if ( is_array($groups) ) {

                // if it returns an array, it's got groups back from hooking up w/ emma
                $valid_input['logged_in'] = 'true';

                // pass the array of groups into the settings
                $valid_input['groups'] = $groups;

                // if there is an active group selected, pass it through
                $valid_input['group_active'] = $input['group_active'];

            } else {

                // not logged in...
                $valid_input['logged_in'] = 'false';

                // pass thru previous info
                $valid_input['groups'] = self::$settings['groups'];
                $valid_input['group_active'] = $input['group_active'];

                // the method returns a string / error message otherwise
                add_settings_error(
                    'account_id',
                    'emma_error',
                    $groups,
                    'error'
                );


            }

        } elseif ( $reset ) {

            // get defaults
            $default_input = $this->get_settings_defaults();
            // assign to valid input
            $valid_input = $default_input;

        }

        return $valid_input;

    } // end sanitize_account_information_settings


}
