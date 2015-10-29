<?php
/**
 * Plugin Name: WooCommerce Checkout Customizer
 * Description:  With Checkout Customizer allows you to create easy step-by-step chechout wizard for WooCommerce checkout process
 * Version: 1.0
 * Author: Pluginator
 */

if (!class_exists('PluginatorPlugin')) {
    require_once dirname(__FILE__).'/common/PluginatorPlugin.php';
}

if (!class_exists('PluginatorWpmlAdapter')) {
    require_once dirname(__FILE__).'/common/PluginatorWpmlAdapter.php';
}

class WooCheckoutStepsPluginatorPlugin extends PluginatorWpmlAdapter
{
    protected $languageDomain = 'pluginator_checkout_steps';
    protected $optionsPrefix = 'pluginator_checkout_steps_';
    protected $version = '1.0';
    protected $wizardThemes = array(
        'red-blue',
        'green-line',
        'orientation'
    );
    
    protected $defaultTheme = 'red-blue';
    
    protected $wooVersionWhithUpdateWichBrokePlugin = 2.3;
     
    protected function onInit()
    {
        $this->addActionListener('plugins_loaded', 'onLanguagesInitAction');
       
        if ($this->_isWoocommercePluginNotActiveWhenPluginatorPluginActive()) {
            $this->addActionListener(
                'admin_notices',
                'onDisplayInfoAboutDisabledWoocommerceAction' 
            );
            
            return false;
        }
       
        parent::onInit();
    } // end onInit
    
    public function getThemeFolderPath($theme = '')
    {
        return $this->pluginPath.'themes/'.$theme.'/';
    } // end getThemeFolderPath
    
    public function onInstall()
    {
        if (!$this->_isWoocommercePluginActive()) {
            $message = 'WooCommerce not active or not installed.';
            $this->displayError($message);
            exit();
        } 
                
        $plugin = $this->onBackendInit();
        
        $plugin->onInstall();
    } // end onInstall
    
    public function onUninstall()
    { 
        $plugin = $this->onBackendInit();
        
        $plugin->onUninstall();
    } // end onInstall
    
    public function onLanguagesInitAction()
    {
        load_plugin_textdomain(
            $this->languageDomain,
            false,
            $this->pluginLanguagesPath
        );
    } // end onLanguagesInitAction
    
    protected function onFrontendInit()
    {        
        $instance = $this->_getFrontendPluginInstance();
        
        $frontend = new $instance(__FILE__);
        return $frontend;
    } // end onFrontendInit
    
    private function _getFrontendPluginInstance()
    {
        $className = 'WooCheckoutStepsFrontendPluginatorPlugin';
        
        $filePath = 'common/'.$className.'.php';
        require_once $this->pluginPath.$filePath;
        
        if (!$this->isWooVersionMoreThanVersionWhithUpdateWichBrokePlugin()) {
            return $className;
        }
        
        $className = 'CheckoutStepsFrontendPluginatorPlugin';
        
        $filePath = 'common/'.$className.'.php';
        require_once $this->pluginPath.$filePath;
        
        return $className;
    } // end getFrontendPluginInstance
    
    private function isWooVersionMoreThanVersionWhithUpdateWichBrokePlugin()
    {
        $wooVersion = $this->getCurrentWoocommerceVersion();
        
        $versionParts = explode(".", $wooVersion);
        $withUpdateParts = explode(
            ".",
            $this->wooVersionWhithUpdateWichBrokePlugin
        );

        foreach ($versionParts as $key => $value) {
            if (!array_key_exists($key, $withUpdateParts)
                || (int)$value > (int)$withUpdateParts[$key]) {
                return true;
            }
            
            if ((int)$value == (int)$withUpdateParts[$key]) {
                continue;
            }
            
            return false;
        }
    } // end isCurrentWooVersionMoreThanVersionWhithUpdateWichBrokePlugin
    
    public function getCurrentWoocommerceVersion()
    {
        require_once(ABSPATH.'wp-admin/includes/plugin.php' );
        $path = WP_CONTENT_DIR.'/plugins/woocommerce/woocommerce.php';
        $wooInfo = get_plugin_data($path);
        return $wooInfo['Version'];
    } // end getCurrentWoocommerceVersion
    
    protected function onBackendInit()
    {
        $filePath = 'common/WooCheckoutStepsBackendPluginatorPlugin.php';
        require_once $this->pluginPath.$filePath;
        $backend = new WooCheckoutStepsBackendPluginatorPlugin(__FILE__);
        return $backend;
    } // end onFrontendInit
    
    public function onDisplayInfoAboutDisabledWoocommerceAction()
    {
        $message = 'Woocommerce Checkout Steps Wizard: ';
        $message .= 'WooCommerce not active or not installed.';
        $this->displayError($message);
    } //end onDisplayInfoAboutDisabledWoocommerceAction
    
    private function _isWoocommercePluginNotActiveWhenPluginatorPluginActive()
    {
        return $this->_isPluginatorPluginActive()
               && !$this->_isWoocommercePluginActive();
    } // end _isWoocommercePluginNotActiveWhenPluginatorPluginActive
    
    private function _isPluginatorPluginActive()
    {
        $path = 'woocommerce-multistep-checkout-pro/plugin.php';
        return $this->isPluginActive($path);
    } // end _isPluginatorPluginActive
    
    private function _isWoocommercePluginActive()
    {        
        return $this->isPluginActive('woocommerce/woocommerce.php');
    } // end _isWoocommercePluginActive
    
    public function isWoocommerceCheckoutFieldEditorPluginActive()
    {        
        return $this->isPluginActive(
            'woocommerce-checkout-field-editor/checkout-field-editor.php'
        );
    } // end isWoocommerceCheckoutFieldEditorPluginActive
    
    public function getCurrentTheme()
    {
        $options = $this->getOptions('current_theme');
        
        if (!$options) {
            return false;
        }
        
        return $options[0];
    } // end getCurrentTheme
    
    public function getWizardThemeSettings($theme = '', $attr = false)
    {
        $themes = array();
        
        foreach ($this->wizardThemes as $value) {
            $path =  $this->getThemeFolderPath($value);
            if (!file_exists($path)) {
                echo "Theme folder is not found: ".$path;
                return false;
            }
            
            $themeSettingsPath = $path.'settings.php';
            if (!file_exists($themeSettingsPath)) {
                $message = "Theme configuration file ".$themeSettingsPath;
                $message .= " is not found.";
                echo $message;
                return false;
            }
                     
            $themes[$value] = include($themeSettingsPath);
        }

        if (!$theme) {
            return $themes;
        }
        
        if (!$attr) {
            return $themes[$theme]; 
        }
        
        if (!$this->_hasAttributInSettings($themes[$theme], $attr)) {
           return false; 
        }

        return $themes[$theme][$attr];
    } // end getWizardThemeSettings
    
    private function _hasAttributInSettings($settings, $attr)
    {
        return array_key_exists($attr, $settings);
    } // _hasAttributInSettings
}
$className = 'wooCheckoutStepsPluginatorPlugin';
$GLOBALS[$className] = new WooCheckoutStepsPluginatorPlugin(__FILE__); 
