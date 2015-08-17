<?php
/**
* Dynamic Styles for the Emma Emarketing Plugin
*
* Outputs a custom stylesheet for the plugin that only gets instantiated on the pages the widget or shortcode is used on,
* @package Emma_Emarketing
* @author ah so designs
* @version 1.0
* @abstract
* @copyright not yet
*/

// output dynamic form stylesheet
//http://css-tricks.com/css-variables-with-php/
//header("Content-type: text/css; charset: UTF-8");

class Emma_Style {

    private $settings;

    function __construct() {

        $this->settings = (array) get_option( Form_Custom::$key );

    }

    public function output() { ?>

        <style id="emma-emarketing" type="text/css" media="all">
            /**
            * Emma Emarketing Plugin Stylesheet
            */

            /** Basics **/
            #emma-subscription-form { width: 100%; }
            ul#emma-form-elements { list-style-type: none; margin: 0; padding: 0; }
            ul#emma-form-elements li.emma-form-row { list-style-type: none; width: 90%; margin: 3px auto; height: 20px; display: block; }
            ul#emma-form-elements .emma-form-label { float: left; width: 27%; }
            ul#emma-form-elements .emma-form-input { float: right; width: 69%;}
            ul#emma-form-elements .emma-form-row-last { clear: both; }
            ul#emma-form-elements .emma-required { color: #C00; }
            ul#emma-form-elements #emma-form-submit { float: right; }
            ul#emma-form-elements .emma-form-label-required { width: 40%; }
            .emma-status-msg { width: 90%; margin: 0 auto; }
            .emma-error { width: 90%; margin: 0 auto; color: #C00; }


            #emma-form.x-small { width: 200px; }
            #emma-form.small { width: 280px; }
            #emma-form.medium { width: 300px; }
            #emma-form.large { width: 340px; }

            /** Customizable Elements **/
            ul#emma-form-elements .emma-form-input {
                border: <?php echo $this->settings['border_width'] . 'px ' . $this->settings['border_type'] . ' #' . $this->settings['border_color']; ?>;
                color: #<?php echo $this->settings['txt_color']; ?>;
                background-color: #<?php echo $this->settings['bg_color']; ?>;
            }
            #emma-form input[type="submit"] {
                border: <?php echo $this->settings['submit_border_width'] . 'px ' . $this->settings['submit_border_type'] . ' #' . $this->settings['submit_border_color']; ?>;
                color: #<?php echo $this->settings['submit_txt_color']; ?>;
                background-color: #<?php echo $this->settings['submit_bg_color']; ?>;
            }
            #emma-form input[type="submit"]:hover {
                border: <?php echo $this->settings['submit_hover_border_width'] . 'px ' . $this->settings['submit_hover_border_type'] . ' #' . $this->settings['submit_hover_border_color']; ?>;
                color: #<?php echo $this->settings['submit_hover_txt_color']; ?>;
                background-color: #<?php echo $this->settings['submit_hover_bg_color']; ?>;
            }

            #emma-form.x-small ul#emma-form-elements .emma-form-input,
            #emma-form.x-small ul#emma-form-elements .emma-form-label { float: left; width: 97%; }

            /* status text */
            #emma-form .emma-status { width: 90%; margin: 0 auto; }

            /* powered by logo */
            #powered-by { padding: 0 20px; }
            #powered-by a { width: 130px; height: 40px; display: block; text-indent: -9999px; background: transparent url(<?php echo EMMA_EMARKETING_URL . '/assets/images/emma_powered_by_logo.png'; ?> ) no-repeat top left; }

        </style>

    <?php }

} // end class Style