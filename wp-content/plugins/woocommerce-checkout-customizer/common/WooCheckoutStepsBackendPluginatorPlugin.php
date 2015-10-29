<?php

class WooCheckoutStepsBackendPluginatorPlugin
      extends WooCheckoutStepsPluginatorPlugin
{
    protected $menuOptions = array(
        'settings' => "Settings",
    );
    protected $defaultMenuOption = 'settings';
    
    protected function onInit()
    {
        $this->addActionListener('admin_menu', 'onAdminMenuAction');
        
        $this->addActionListener(
            'admin_print_styles', 
            'onInitGeneralCssAction'
        );
    } // end onInit
    
    public function onInitGeneralCssAction()
    {
        $this->onEnqueueCssFileAction(
            'pluginator-checkout-steps-wizard-general',
            'general.css'
        );
    } // end onInitGeneralCssAction
    
    public function onInstall($refresh = false, $settings = false)
    {      
        if (!$this->fileSystem) {
            $this->fileSystem = $this->getFileSystemInstance();
        }
        
        if ($this->_hasPermissionToCreateCacheFolder()) {
            $this->fileSystem->mkdir($this->pluginCachePath, 0777);
        }

        if (!$refresh) {
            $currentTheme = $this->getCurrentTheme();
            $currentTheme = false;
            if (!$currentTheme) {
                
                $currentTheme = $this->defaultTheme; 
                $value = array($currentTheme);
                $this->updateOptions('current_theme', $value);
            }
        }
                     
        if (!$refresh) {
            $this->installThemeOptions($currentTheme);
        } 
    } // end onInstal
    
    public function installThemeOptions($theme)
    {
        $themeSettings = $this->getOptions('settings_'.$theme);
            
        if ($themeSettings) {
            return true;
        }
        
        $this->_doInitDefaultThemeOptions($theme);
    } //end installThemeOptions
    
    private function _doInitDefaultThemeOptions($theme)
    {
        $generalOptions = $this->getGeneralSettings();
        
        $options = $this->getWizardThemeSettings($theme, 'options');
        
        if (!$options) {
            $options = array();
        }
        
        $options = array_merge($generalOptions, $options);
        
        foreach ($options as $ident => &$item) {
            if ($this->_hasDefaultValueInItem($item)) {
                $values[$ident] = $item['default'];
            }
        }
        unset($item);
        
        $this->updateOptions('settings_'.$theme, $values);
    } // end _doInitDefaultOptions
    
    private function _hasDefaultValueInItem($item)
    {
        return isset($item['default']);
    } //end _hasDefaultValueInItem
    
    public function onUninstall()
    {
        $temes = $this->wizardThemes;
        
        foreach ($temes as $theme) {
            $optionName = $this->optionsPrefix.'settings_'.$theme;
            delete_option($optionName);
        }
                 
        $optionName = $this->optionsPrefix.'current_theme';
        delete_option($optionName);  
    } // end onUninstall
    
    private function _hasPermissionToCreateCacheFolder()
    {
        return ($this->fileSystem->is_writable($this->pluginPath)
               && !file_exists($this->pluginCachePath));
    } // end _hasPermissionToCreateFolder
    
    public function _onFileSystemInstanceAction()
    {
        $this->fileSystem = $this->getFileSystemInstance();
    } // end _onFileSystemInstanceAction
    
    public function getPluginTemplatePath($fileName)
    {
        return $this->pluginTemplatePath.'backend/'.$fileName;
    } // end getPluginTemplatePath
    
    public function getPluginCssUrl($fileName) 
    {
        return $this->pluginCssUrl.'backend/'.$fileName;
    } // end getPluginCssUrl
    
    public function getPluginJsUrl($fileName)
    {
        return $this->pluginJsUrl.'backend/'.$fileName;
    } // end getPluginJsUrl
    
    public function onAdminMenuAction() 
    {
        $page = add_menu_page(
            __(
                'WooCommerce Checkout Customizer',
                $this->languageDomain
            ), 
            __('Checkout Customizer', $this->languageDomain), 
            'manage_options', 
            'pluginator-checkout-steps-wizard', 
            array(&$this, 'onDisplayOptionPage'), 
            $this->getPluginImagesUrl('icon_16x16.png')
        );
        
        $this->addActionListener(
            'admin_print_scripts-'.$page, 
            'onInitJsAction'
        );
        
        $this->addActionListener(
            'admin_print_styles-'.$page, 
            'onInitCssAction'
        );
        
        $this->addActionListener(
            'admin_head-'.$page,
            '_onFileSystemInstanceAction'
        );
    } // end onAdminMenuAction
    
    public function onInitJsAction()
    {
        $this->onEnqueueJsFileAction('jquery');
        $this->onEnqueueJsFileAction(
            'pluginator-checkout-steps-wizard-colorpicker',
            'colorpicker.js',
            'jquery'
        );
        
        $this->onEnqueueJsFileAction(
            'jquery-ui-sortable',
            false,
            'jquery'
        );
        
        $this->onEnqueueJsFileAction(
            'jquery-ui-slider',
            false,
            'jquery'
        );
        
        $this->onEnqueueJsFileAction(
            'pluginator-checkout-steps-wizard-tooltip',
            'tooltip.js',
            'pluginator-checkout-steps-wizard-colorpicker'
        );
        
        $this->onEnqueueJsFileAction(
            'pluginator-checkout-steps-wizard-general',
            'general.js',
            'jquery-ui-slider'
        );       
    } // end onInitJsAction
    
    public function onInitCssAction()
    {
        $this->onEnqueueCssFileAction(
            'pluginator-checkout-steps-wizard-styles',
            'style.css'
        );
        
        $this->onEnqueueCssFileAction(
            'pluginator-checkout-steps-wizard-menu',
            'menu.css'
        );
        
        $this->onEnqueueCssFileAction(
            'pluginator-checkout-steps-wizard-tooltip',
            'tooltip.css'
        );
        
        $this->onEnqueueCssFileAction(
            'pluginator-checkout-steps-wizard-colorpicker',
            'colorpicker.css'
        );
        
        $this->onEnqueueCssFileAction(
            'pluginator-checkout-steps-wizard-sortable',
            'sortable.css'
        );
        
        $this->onEnqueueCssFileAction(
            'pluginator-checkout-steps-wizard-slider',
            'slider.css'
        );
    } // end onInitCssAction
    
    public function onDisplayOptionPage()
    {
        if (!$this->menuOptions) {         
            $menu = $this->fetch('menu.phtml');
            echo $menu;
        }

        $methodName = 'fetchOptionPage';
        
        if ($this->hasOptionPageInRequest()) {
            $postfix = $_GET['tab'];
        } else {
            $postfix = $this->defaultMenuOption;
        }
        $methodName.= ucfirst($postfix);
        
        $method = array(&$this, $methodName);
        
        if (!is_callable($method)) {
            throw new Exception("Undefined method name: ".$methodName);
        }
        
        call_user_func_array($method, array());
    } // end onDisplayOptionPage
    
    protected function hasOptionPageInRequest()
    {
        return array_key_exists('tab', $_GET)
               && array_key_exists($_GET['tab'], $this->menuOptions);
    } // end hasOptionPageInRequest
    
    public function getOptionsFieldSet($theme)
    {
        $fildset = array(
            'general' => array(),
            'theme' => array(
                'legend' => __('Stylesheet  Settings', $this->languageDomain) 
            ),
        );
        
        $settings = $this->loadSettings($theme);
        
        if (!$settings) {
            return false;
        }
        
        foreach ($settings as $ident => &$item) {
            if (array_key_exists('fieldsetKey', $item)) {
               $key = $item['fieldsetKey'];
               $fildset[$key]['filds'][$ident] = $settings[$ident];
            }
        }
        unset($item);
        
        return $fildset;
    } // end getOptionsFieldSet
    
    public function getGeneralSettings()
    {
        $path = $this->pluginPath.'includes/general_settings_list.php';
        
        if (!file_exists($path)) {
            return false;
        }
        
        $generalSettings = include($path);
        
        return $generalSettings;
    } //end getGeneralSettings
    
    public function getSettings($optionsList)
    {
        $generalSettings = $this->getGeneralSettings();
        
        $path = $this->pluginPath.'includes/themes_settings_list.php';
        
        if (!file_exists($path)) {
            return false;
        }
        

        $settings = include($path);

        $diff = array_diff_key($settings, $optionsList);
        $options = array_diff_key($settings, $diff);
        
        $options = $this->arrayReplace($optionsList, $options);
        
        $options = array_merge($generalSettings, $options);

        return $options;
    } // end loadSettings
    
    public function arrayReplace($arrayOne, $arrayTwo)
    {
        foreach ($arrayOne as $key => $value) {

            if (!array_key_exists($key, $arrayTwo)) {
                continue;
            }
            
            if (is_array($value)) {          
                $arrayTwo[$key] = $this->arrayReplace($arrayTwo[$key], $arrayOne[$key]);   
            }
            
            $arrayOne[$key] = $arrayTwo[$key];
        } 

        return $arrayOne;
    } // end arrayReplace
    
    public function loadSettings($theme)
    {
        $optionsList = $this->getWizardThemeSettings($theme, 'options');
        
        if (!$optionsList) {
            return false;
        }
        
        $settings = $this->getSettings($optionsList);
        
        if (!$settings) {
            return false;
        }

        $values = $this->getOptions('settings_'.$theme);
        if ($values) {
            foreach ($settings as $ident => &$item) {
                if (array_key_exists($ident, $values)) {
                    $item['value'] = $values[$ident];
                }
            }
            unset($item);
        }
        
        return $settings;
    } // end loadSettings
    
    public function fetchOptionPageSettings()
    {
        if ($this->_isRefreshPlugin()) {
            $this->onRefreshPlugin();
        }
        
        if ($this->_isRefreshCompleted()) {
            $message = __(
                'Success refresh plugin',
                $this->languageDomain
            );
            
            $this->displayUpdate($message);   
        }
        $this->_displayPluginErrors();
        
        $this->displayOptionsHeader();
        
        $this->updateTheme();
        
        $currentTheme = $this->getCurrentTheme();
        
        $this->updateThemeOptions($currentTheme);

        $vars = array(
            'themes' => $this->getWizardThemeSettings(),
            'currentTheme' => $currentTheme,
        );
        
        echo $this->fetch('list_of_themes.phtml', $vars);

        $fieldsets = $this->getOptionsFieldSet($currentTheme);
        
        if (!$fieldsets) {
            return false;
        }
        $options = $this->getOptions('settings_'.$currentTheme);
        
        $vars = array(
            'fieldset' => $fieldsets,
            'currentValues' => $options,
            'currentThemeName' => $this->getWizardThemeSettings(
                $currentTheme,
                'name'
            )
        );
        
        echo $this->fetch('settings_page.phtml', $vars);
    } // end fetchOptionPageSettings
    
    public function getSelectorClassForDisplayEvent($class)
    {
        $selector = $class.'-visible';
        
        $currentTheme = $this->getCurrentTheme();
        
        $options = $this->getOptions('settings_'.$currentTheme);
        
        if ($this->_isVisibleInSettings($options, $class)) {
            $selector.=  ' pluginator-checkout-steps-wizard-hidden ';
        }
        
        return $selector;
    } // end getSelectorClassForDisplayEvent
    
    private function _isVisibleInSettings($options, $class)
    {
        return !isset($options[$class])
               || $options[$class] == 'disable'
               || ($options[$class] == 'horizontal'
               && $class == 'stepsOrientation');
    } // end _isVisibleInSettings
    
    public function displayOptionsHeader()
    { 
        $vars = array(
            'content' => __(
                'WooCommerce Checkout Customizer',
                $this->languageDomain
            )
        );
        
        echo $this->fetch('options_header.phtml', $vars);
    } // end displayOptionsHeader
    
    private function _isRefreshCompleted()
    {
        return array_key_exists('refresh_completed', $_GET);
    } // end _isRefreshCompleted
    
    private function _displayPluginErrors()
    {        
        $caheFolderErorr = $this->_detectTheCacheFolderAccessErrors();

        if ($caheFolderErorr) {
            echo $this->fetch('refresh.phtml');
        }
    } // end _displayPluginErrors
    
    private function _detectTheCacheFolderAccessErrors()
    {
        if (!$this->fileSystem->is_writable($this->pluginCachePath)) {

            $message = __(
                "Caching does not work! ",
                $this->languageDomain
            );
            
            $message .= __(
                "You don't have permission to access: ",
                $this->languageDomain
            );
            
            $path = $this->pluginCachePath;
            
            if (!$this->fileSystem->exists($path)) {
                $path = $this->pluginPath;
            }
            
            $message .= $path;
            //$message .= $this->fetch('manual_url.phtml');
            
            $this->displayError($message);
            
            return true;
        }
        
        return false;
    } // end _detectTheCacheFolderAccessErrors
    
    private function _isRefreshPlugin()
    {
        return array_key_exists('refresh_plugin', $_GET);
    } // end _isRefreshPlugin
    
    public function onRefreshPlugin()
    {
        $this->onInstall(true);
    } // end onRefreshPlugin
    
    public function updateThemeOptions($theme)
    {
        if ($this->isUpdateOptions('save')) {
            try {
                $this->_doUpdateOptions($_POST, $theme);
                           
                $this->displayOptionPageUpdateMessage(
                    'Success update settings'
                );               
            } catch (Exception $e) {
                $message = $e->getMessage();
                $this->displayError($message);
            }
        }
    } //end updateThemeOptions
    
    public function updateTheme()
    {
        if ($this->isUpdateOptions('changeTheme')) {
           try {
                $this->updateCurrentTheme();
                $this->installThemeOptions($_POST['themeSelector']);
                           
                $this->displayOptionPageUpdateMessage(
                    'Success update current theme'
                );               
            } catch (Exception $e) {
                $message = $e->getMessage();
                $this->displayError($message);
            }
        }
    } //end updateTheme
    
    public function updateCurrentTheme()
    {
        $value = array($_POST['themeSelector']);
        $options = $this->updateOptions('current_theme', $value);
    } //end updateCurrentTheme
    
    public function displayOptionPageUpdateMessage($text)
    {
        $message = __(
            $text,
            $this->languageDomain
        );
            
        $this->displayUpdate($message);   
    } // end displayOptionPageUpdateMessage
    
    private function _doUpdateOptions($newSettings = array(), $theme)
    {
        $this->updateOptions('settings_'.$theme, $newSettings);
    } // end _doUpdateOptions
    
    public function isUpdateOptions($action)
    {
        return array_key_exists('__action', $_POST)
               && $_POST['__action'] == $action;
    } // end isUpdateOptions
}