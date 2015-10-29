<?php
class WooCheckoutStepsFrontendPluginatorPlugin 
      extends WooCheckoutStepsPluginatorPlugin
{
    protected $currenteTheme = array();
    
    protected $compabilityThemes = array(
        'Shoppica',
        'Femme'
    );
    
    protected $siteTheme = '';
    
    protected $wooAdaptedVersions = array(
        '2.0.13'
    );
    
    protected $wooVersion = '';
    
    protected function onInit()
    {
        $this->onInitVariables();

        $this->addActionListener(
            'woocommerce_before_checkout_form',
            'appendWizardBlockToCheckoutPage'
        );

        $this->addActionListener('wp_enqueue_scripts', 'onInitJsAction');

        $this->addActionListener('wp_print_styles', 'onInitCssAction');

        $this->addActionListener(
            'wp_ajax_valid_post_code',
            'checkUserPostcodeAction'
        );
        
        $this->addActionListener(
            'wp_ajax_nopriv_valid_post_code',
            'checkUserPostcodeAction'
        );

        $this->addActionListener(
            'wp_ajax_login_user_wizard_step',
            'onSignonUserAction'
        );
        
        $this->addActionListener(
            'wp_ajax_nopriv_login_user_wizard_step',
            'onSignonUserAction'
        );

        $this->addFilterListener(
            'woocommerce_locate_template',
            'onLocateWoocommerceTemplateFilter',
            100,
            2
        );
    } // end onInit

    public function onLocateWoocommerceTemplateFilter($template, $templateName)
    {
        if ($this->_isAdaptedTheme()) {
            return $template;
        }
        
        $path = $this->getWoocommerceTemplatesPath($templateName);
        
        if (file_exists($path)) {
            
            $template = $path;
        }
    
        return $template;
    } // end onLocateWoocommerceTemplateFilter
    
    protected function getWoocommerceTemplatesPath($templateName)
    {
        return $this->getPluginTemplatePath('woocommerce/'.$templateName);
    } // end getWoocommerceTemplatesPath

    public function onInitVariables()
    {
        $this->currenteTheme = $this->getCurrentTheme();
        
        $this->siteTheme = wp_get_theme();
        
        $this->wooVersion = $this->getCurrentWoocommerceVersion();
    } //end onInitVariables

    private function _isDisabledPaymentStep()
    {
        $theme = $this->currenteTheme;
        
        $settings = $this->getOptions('settings_'.$theme);
        
        return $this->_hasOptionInSettings($settings, 'disableSteps')
               && in_array('payment', $settings['disableSteps']);
    } //end _isDisabledPaymentStep
    
    private function _hasOptionInSettings($settings, $option)
    {
        return array_key_exists($option, $settings);
    } // end _hasOptionInSettings
        
    public function checkUserPostcodeAction()
    {
       $country = $_POST['country'];
       
       $postCode = $_POST['postCode'];
        
       echo WC_Validation::is_postcode($postCode, $country);
       
       exit();
    } // end checkUserPostcodeAction
     
    public function onSignonUserAction()
    {
        if (is_email($_POST['username'])) {
            $userData = get_user_by_email($_POST['username']);
            $userLogin = (!$userData) ? 'not detected' : $userData->user_login;
        } else {
            $userLogin = $_POST['username'];
        }
        
        $userData = array(
            'user_login'    => $userLogin,
            'user_password' => $_POST['password'],
            'remember'      => $_POST['rememberme']
        );
        
        $result = wp_signon($userData, false);
        
        if ($this->_isUnsuccessfulAuthentication($result)) {
            $message = $result->get_error_message();
            echo $this->fetchLoginErrorMessage($message);
        } else {
            echo 'successfully';
        }
        exit();
    } // end onSignonUserAction
    
    private function _isUnsuccessfulAuthentication($result)
    {
        return is_wp_error($result);
    } // end _isUnsuccessfulAuthentication
    
    public function appendWizardBlockToCheckoutPage()
    {
        $theme = $this->currenteTheme;
        
        $settings = $this->getOptions('settings_'.$theme);

        $steps = array(
            'login' => array(
                'name' => $settings['loginStepName'],
                'class' => 'pluginator-wizard-step-login',
            ),
            'billing' => array(
                'name' => $settings['bilingStepName'],
                'class' => 'pluginator-wizard-step-billing',
                'content' => $this->fetch('biling_content.phtml')
            ),
            'shipping' => array(
                'name' => $settings['shippingStepName'],
                'class' => 'pluginator-wizard-step-shipping',
                'content' => $this->fetch('shipping_content.phtml')
            ),
            'reviewOrder' => array(
                'name' => $settings['reviewOrderStepName'],
                'class' => 'pluginator-wizard-step-view-order'
            ),
            'payment' => array(
                'name' => $settings['paymentStepName'],
                'class' => 'pluginator-wizard-step-payment',
            ),
        );

        $this->appendReviewOrderContent($steps);

        if ($this->isEnabledCustomStep($settings)) {
            $steps['custom'] = array(
                'name' => $settings['customStepName'],
                'class' => 'pluginator-wizard-step-custom',
            );
        }
        
        $permissibleSteps = array();

        foreach ($steps as $key => $step) {
            if (!$this->_isDesabledStep($settings, $key)) {
                $newKey = $this->getElementOrdinalNumberInArray(
                    $key,
                    $settings['sortSteps']
                );
                $permissibleSteps[$newKey] = $step;
            }
        }

        ksort($permissibleSteps);

        $vars = array(
            'steps' => $permissibleSteps,
            'count' => count($permissibleSteps)
        );
        
        echo $this->fetch('wizard_content.phtml', $vars);
    } // end appendWizardBlockToCheckoutPage
    
    protected function appendReviewOrderContent(&$steps)
    {
    } // end appendReviewOrderContent
    
    public function getElementOrdinalNumberInArray($element, $array)
    {
        $n = -1;
        
        if ($element == 'login') {
            return $n;
        }
        
        foreach ($array as $key => $value) {
            $n++;
            if ($key == $element) {
                return $n;
            }
        }
    } //end getElementOrdinalNumberInArray
    
    private function _hasStepKeyInCustomPosition($settings, $step)
    {
        return $settings['customStepPosition'] == $step;
    } //end _hasStepKeyInCustomPosition
    
    private function _isDesabledStep($settings, $step)
    {
        if ($this->_isLoginStep($step) && $this->isAuthorizedUser()) {
            return true;
        }
        
        if (!$this->_hasDisableStepsInOptions($settings)) {
            return false;
        }
        
        return in_array($step, $settings['disableSteps']);
    } // end _isDesabledStep
    
    private function _hasDisableStepsInOptions($settings)
    {
        return array_key_exists('disableSteps', $settings);
    } //end _hasDisableStepsInOptions
    
    private function _isLoginStep($step)
    {
        return $step == 'login';
    } //end _isLoginStep
    
    public function isEnabledCustomStep($settings)
    {
        return $this->isWoocommerceCheckoutFieldEditorPluginActive()
               && array_key_exists('enableCustomStep', $settings);
    } //end isEnabledCustomStep

    public function fetchLoginErrorMessage($message = '')
    {
        $vars = array(
           'message' => $message
        );
        
        return $this->fetch('login_error_message.phtml', $vars);
    } // end fetchErrorMessage
    
    public function getPluginCssUrl($path) 
    {
        return $this->pluginUrl.$path;
    } // end getPluginCssUrl
    
    public function getPluginJsUrl($fileName)
    {
        return $this->pluginJsUrl.'frontend/'.$fileName;
    } // end getPluginJsUrl
    
    public function getPluginTemplatePath($fileName)
    {
        return $this->pluginTemplatePath.'frontend/'.$fileName;
    } // end getPluginTemplatePath
    
    public function onInitJsAction()
    {
        if (!$this->_hasCheckoutInPage()) {
            return false;
        }

        $this->onEnqueueJsFileAction('jquery');
        $this->onEnqueueJsFileAction(
            'pluginator-jquery-steps',
            'jquery.steps.js',
            'jquery',
            $this->version
        );
        
        $this->onEnqueueJsFileAction(
            'pluginator-checkout-steps-custom',
            'custom.js',
            'jquery'
        );
      
        $jsFolder = $this->getFolderWithFile();

        $this->onEnqueueJsFileAction(
            'pluginator-checkout-steps-collect_wizard',
            $jsFolder.'/collect_wizard.js',
            'pluginator-checkout-steps-general',
            $this->version,
            true
        );
        
        $this->onEnqueueJsFileAction(
            'pluginator-checkout-steps-general',
            $jsFolder.'/general.js',
            'wc-checkout',
            $this->version,
            true
        );
        
        $deps = array('pluginator-checkout-steps-general');
        
        if ($this->_isWoocommerceTermsConditionsPopupPluginActive()) {
            array_push($deps, "wc-terms-conditions-popup");
        }
        
        $this->onEnqueueJsFileAction(
            'pluginator-checkout-steps-update-payment-block',
            $jsFolder.'/update_payment_block.js',
            $deps,
            $this->version,
            true
        );
 
        $settings = $this->getOptions('settings_'.$this->currenteTheme);
        
        $vars = array(
            'ajaxurl' => $this->makeUniversalLink(admin_url('admin-ajax.php')),
            'imagesUrl' => $this->getPluginImagesUrl(''),
            'isAuthorizedUser' => $this->isAuthorizedUser(),
            'nextButton' => $settings['nextButtonName'],
            'previousButton' => $settings['previousButtonName'],
            'finishButton' => $settings['finishButtonName'],
            'noAccountButton' => $settings['noAccountButtonName'],
            'titleTemplate' => $this->getTabsTitleTemplate($settings),
            'termsLocation' => $settings['termsAndConditionsLocation'],
            'transitionEffect' => $settings['transitionEffect'],
        );
        
        if ($this->_hasOptionInSettings($settings, 'requireFieldsNotify')) {
            $vars['requireFieldsNotify'] = $settings['requireFieldsNotify'];
            $vars['requireFieldsText'] = $settings['requireFieldsText'];
        }
        
        if ($this->_hasOptionInSettings($settings, 'stepsOrientation')) {
            $vars['stepsOrientation'] = $settings['stepsOrientation'];
        }
        
        if ($this->_hasOptionInSettings($settings, 'hideComplitedSteps')) {
            $vars['hideComplitedSteps'] = $settings['hideComplitedSteps'];
        }
        
        if ($this->_hasOptionInSettings($settings, 'disableSteps')) {
            $vars['disableSteps'] = $settings['disableSteps'];
        }
        
        if ($this->isEnabledCustomStep($settings)) {
            $fields = $this->getAdditionalFieldsFromCheckoutFieldEditor();
            $vars['customFields'] = $fields;
        }
        
        $vars = $this->doLocalizeString($vars);

        wp_localize_script(
            'pluginator-checkout-steps-general',
            'fesiCheckoutSteps',
            $vars
        );
    } // end onInitJsAction
    
    private function _isWoocommerceTermsConditionsPopupPluginActive()
    {
        $folderName = 'woocommerce-terms-conditions-popup';
        $mainFile = 'woocommerce-terms-conditions-popup.php';
               
        return $this->isPluginActive($folderName.'/'.$mainFile);
    } // end _isWoocommercePluginActive
    
    public function doLocalizeString($vars = array())
    {
        if (!is_array($vars)) {
            return $vars;
        }

        foreach ($vars as $key => $value) {
            if (!is_string($value)) {
                continue;
            }
            
            $vars[$key] = __($value, $this->languageDomain);
        }
        
        return $vars;
    } // end getString
    
    protected function getFolderWithFile()
    {     
        if (!$this->_hasSiteThemeInCompabilityThemes()) {
            $folder = 'all';
        } else {
            $folder = mb_strtolower($this->siteTheme);
            $folder = str_replace(' ', '_', $folder);
        }
        
        if ($this->_isAdaptedVersion()) {
            $folder = 'v'.$this->wooVersion.'/'.$folder;
        }
        
        return $folder;
    } // end getFolderWithFile
    
    private function _isAdaptedTheme()
    {
        return in_array($this->siteTheme, $this->compabilityThemes);
    } // end _isAdaptedTheme
    
    private function _isAdaptedVersion()
    {
        return in_array($this->wooVersion, $this->wooAdaptedVersions);
    } // end _isAdaptedVersion
    
    private function _hasSiteThemeInCompabilityThemes()
    {
        return in_array($this->siteTheme, $this->compabilityThemes);
    } // end _hasSiteThemeInCompabilityThemes
    
    public function getTabsTitleTemplate($settings = array())
    {
        $path =  $this->getThemeFolderPath($this->currenteTheme);

        if (!file_exists($path)) {
            echo "Theme folder is not found: ".$path;
            return false;
        }
        
        $templatePath = $path.'tab_title_template.phtml';
        
        if (!file_exists($templatePath)) {
            $message = "Theme tabs title template file ".$templatePath;
            $message .= " is not found.";
            echo $message;
            return false;
        }
        
        $vars = array();
        
        if ($this->_hasOptionInSettings($settings, 'showStepsNumbers')) {
            $vars['showNumbers'] = true;
        }
        
        $content =  $this->fetchThemeContent($templatePath, $vars);
        
        return $content;
    } //end getTabsTitleTemplate
    
    public function getAdditionalFieldsFromCheckoutFieldEditor()
    {
        $fields = get_option('wc_fields_additional');
        
        $fields = array_keys($fields);
        
        return $fields;
    } //end getAdditionalFieldsFromCheckoutFieldEditor
    
    public function onInitCssAction()
    {
        if (!$this->_hasCheckoutInPage()) {
            return false;
        }
        
        $this->addActionListener(
            'wp_head',
            'appendThemeCssToHeaderForWizardCustomize'
        );
        
        $folder = $this->getFolderWithFile();

        $this->onEnqueueCssFileAction(
            'pluginator-checkout-steps-styles',
            'static/styles/frontend/'.$folder.'/style.css',
            array(),
            $this->version
        );
        
        $path = 'themes/'.$this->currenteTheme.'/style.css';
        
        $this->onEnqueueCssFileAction(
            'pluginator-jquery-steps',
            $path,
            array(),
            $this->version
        );

        $settings = $this->getOptions('settings_'.$this->currenteTheme);
        
        if ($this->_hasOptionInSettings($settings, 'hideComplitedSteps')) {
            $this->onEnqueueCssFileAction(
                'pluginator-checkout-steps-completed-steps',
                'static/styles/frontend/hide-completed-steps.css',
                array(),
                $this->version
            );
        }
    } // end onInitCssAction
    
    private function _hasCheckoutInPage()
    {
        return is_checkout() 
               || $this->_isActiveWoocommerceQuickCheckoutPlugin();
    } // end _hasCheckoutInPage
    
    private function _isActiveWoocommerceQuickCheckoutPlugin()
    {
        $path = "woocommerce-quick-checkout/woocommerce-quick-checkout.php";
        return $this->isPluginActive($path);
    } // end _isActiveWoocommerceQuickCheckoutPlugin
    
    public function appendThemeCssToHeaderForWizardCustomize()
    {
        $theme = $this->currenteTheme;
        
        $themePath = $this->getThemeFolderPath($theme);
        $themePath .= 'customize.phtml';
        
        if (!file_exists($themePath)) {
            return false;
        }
        
        $vars = array(
            'settings' => $this->getOptions('settings_'.$theme)
        );

        echo $this->fetchThemeContent($themePath, $vars);
    } // end appendThemeCssToHeaderForWizardCustomize
    
    public function getCurrentThemeStyle($theme)
    {
        return $this->getWizardThemeSettings($theme, 'file');
    } //end getCurrentThemeStyle
    
    public function isAuthorizedUser()
    {
        return get_current_user_id();
    } // end isAuthorizedUser
    
    public function fetchThemeContent($path, $vars = array()) 
    {
        if ($vars) {
            extract($vars);
        }

        ob_start();

        include $path;

        $content = ob_get_clean();    
        
        return $content;                
    } // end fetchThemeContent
    
    public function convertHexToRgb($hex)
    {
        $hex = str_replace("#", "", $hex);
  
        if (strlen($hex) == 3) {
           
              $r = hexdec(substr($hex,0,1).substr($hex,0,1));
            
              $g = hexdec(substr($hex,1,1).substr($hex,1,1));
              
              $b = hexdec(substr($hex,2,1).substr($hex,2,1));
        } else {
              $r = hexdec(substr($hex,0,2));
            
              $g = hexdec(substr($hex,2,2));
              
              $b = hexdec(substr($hex,4,2));
        }
        
        $rgb = array($r, $g, $b);
      
        return $rgb;
    } // end _convertHexToRgb
}