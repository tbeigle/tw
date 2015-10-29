<?php
    
abstract class PluginatorPlugin
{
    protected $wpUrl;
    protected $wpPluginsUrl;
    
    protected $pluginDirName;
    protected $pluginMainFile;
    
    protected $pluginPath;
    protected $pluginUrl;
    
    protected $pluginCachePath;
    protected $pluginCacheUrl;
    
    protected $pluginStaticPath;
    protected $pluginStaticUrl;
        
    protected $pluginCssPath;
    protected $pluginCssUrl;
    
    protected $pluginImagesPath;
    protected $pluginImagesUrl;
    
    protected $pluginJsPath;
    protected $pluginJsUrl;
    
    protected $pluginTemplatePath;
    protected $pluginTemplateUrl;
    
    protected $pluginLanguagesPath;
    protected $pluginLanguagesUrl;

    protected $languageDomain = '';
    protected $optionsPrefix  = '';
    
    protected $fileSystem = '';
    
    public function __construct($pluginMainFile)
    {
        $this->wpUrl = get_site_url();
        $this->wpUrl = $this->makeUniversalLink($this->wpUrl);
        
        $this->wpPluginsUrl = plugins_url('/');
        $this->wpPluginsUrl = $this->makeUniversalLink($this->wpPluginsUrl);
        
        $this->pluginDirName = plugin_basename(dirname($pluginMainFile)).'/';
        
        $this->pluginMainFile = $pluginMainFile;
        
        $this->pluginPath = plugin_dir_path($pluginMainFile);
        $this->pluginUrl = plugins_url('/', $pluginMainFile);
        $this->pluginUrl = $this->makeUniversalLink($this->pluginUrl);
        
        $this->pluginCachePath = $this->pluginPath.'cache/';
        $this->pluginCacheUrl = $this->pluginUrl.'cache/';
        
        $this->pluginStaticPath = $this->pluginPath.'static/';
        $this->pluginStaticUrl = $this->pluginUrl.'static/';
        
        $this->pluginCssPath = $this->pluginStaticPath.'styles/';
        $this->pluginCssUrl = $this->pluginStaticUrl.'styles/';
        
        $this->pluginImagesPath = $this->pluginStaticPath.'images/';
        $this->pluginImagesUrl = $this->pluginStaticUrl.'images/';
        
        $this->pluginJsPath = $this->pluginStaticPath.'js/';
        $this->pluginJsUrl = $this->pluginStaticUrl.'js/';
        
        $this->pluginTemplatePath = $this->pluginPath.'templates/';
        $this->pluginTemplateUrl = $this->pluginUrl.'templates/';
        
        $this->pluginLanguagesPath = $this->pluginDirName.'languages/';

        $this->onInit();
    } // end __construct
    
    public function makeUniversalLink($url = '')
    {
        $protocols = array(
            'http:',
            'https:'
        );
        
        foreach ($protocols as $protocol) {
            $url = str_replace($protocol, '', $url);
        }
        
        return $url;
    } // end makeUniversalLink
    
    protected function onInit()
    {        
        register_activation_hook(
            $this->pluginMainFile, 
            array(&$this, 'onInstall')
        );
        
        register_deactivation_hook(
            $this->pluginMainFile, 
            array(&$this, 'onUninstall')
        );
        
        if (defined('WP_BLOG_ADMIN')) {
            $this->onBackendInit();
        } else {
            $this->onFrontendInit();
        }
    } // end onInit
    
    protected function onBackendInit()
    {
    } // end onBackendInit
    
    protected function onFrontendInit()
    {
    } // end onFrontendInit
    
    public function onInstall()
    {
    } // end onInstall
    
    public function onUninstall()
    {
    } // end onUninstall
    
    public function getLanguageDomain()
    {
        return $this->languageDomain;
    } // end getLanguageDomain
    
    public function getPluginPath()
    {
        return $this->pluginPath;
    } // end getPluginPath
    
    public function getPluginCachePath($fileName)
    {
        return $this->pluginCachePath.$fileName.'.php';
    } // end getPluginCachePath
    
    public function getPluginStaticPath($fileName)
    {
        return $this->pluginStaticPath.$fileName;
    } // end pluginStaticPath
    
    public function getPluginCssPath($fileName)
    {
        return $this->pluginCssPath.$fileName;
    } // end pluginCssPath
    
    public function getPluginImagesPath($fileName)
    {
        return $this->pluginImagesPath.$fileName;
    } // end pluginImagesPath
    
    public function getPluginJsPath($fileName)
    {
        return $this->pluginJsPath.$fileName;
    } // end pluginJsPath
    
    public function getPluginTemplatePath($fileName)
    {
        return $this->pluginTemplatePath.$fileName;
    } // end getPluginTemplatePath
    
    public function getPluginLanguagesPath()
    {
        return $this->pluginLanguagesPath;
    } // end getPluginLanguagesPath

    public function getPluginUrl()
    {
        return $this->pluginUrl;
    } // end getPluginUrl
    
    public function getPluginCacheUrl()
    {
        return $this->pluginCacheUrl;
    } // end getPluginCacheUrl
    
    public function getPluginStaticUrl()
    {
        return $this->pluginStaticUrl;
    } // end getPluginStaticUrl
    
    public function getPluginCssUrl($fileName) 
    {
        return $this->pluginCssUrl.$fileName;
    } // end getPluginCssUrl
    
    public function getPluginImagesUrl($fileName)
    {
        return $this->pluginImagesUrl.$fileName;
    } // end getPluginImagesUrl
    
    public function getPluginJsUrl($fileName)
    {
        return $this->pluginJsUrl.$fileName;
    } // end getPluginJsUrl
    
    public function getPluginTemplateUrl($fileName)
    {
        return $this->pluginTemplateUrl.$fileName;
    } // end getPluginTemplateUrl
    
    public function isPluginActive($pluginMainFilePath)
    {
        if (is_multisite())
        {
           $activPlugins = get_site_option('active_sitewide_plugins');
           $result =  array_key_exists($pluginMainFilePath, $activPlugins);
           if ($result) {
               return true;
           }
        }
        
        $activPlugins = get_option('active_plugins');   
        return in_array($pluginMainFilePath, $activPlugins);
    } // end isPluginActive
    
    public function addActionListener(
        $hook, $method, $priority = 10, $acceptedArgs = 1
    )
    {
        add_action($hook, array(&$this, $method), $priority, $acceptedArgs);
    } // end addActionListener
    
    public function addFilterListener(
        $hook, $method, $priority = 10, $acceptedArgs = 1
    )
    {
        add_filter($hook, array(&$this, $method), $priority, $acceptedArgs);
    } // end addFilterListener
    
    public function addShortcodeListener($tag, $method)
    {
        add_shortcode(
            $tag,
            array(&$this, $method)
        );
    } // end addShortcodeListener
    
    public function getOptions($optionName)
    {
        $options = $this->getCache($optionName);
        
        if (!$options){
           $options = get_option($this->optionsPrefix.$optionName); 
        }
        
        $options = json_decode($options, true);
   
        return $options;
    } // end getOptions
    
    public function getCache($fileName)
    {
        $file = $this->getPluginCachePath($fileName);
        
        if (!file_exists($file)) {
            return false;
        }
        
        $content = include($file);
        
        return $content;
    } //end getCache
    
    public function updateOptions($optionName, $values = array())
    {
        $values = $this->doChangeSingleQuotesToDouble($values);

        $value = json_encode($values);

        update_option($this->optionsPrefix.$optionName, $value);
        
        $result = $this->updateCacheFile($optionName, $value);

        return $result;
        
    } // end updateOptions
    
    protected function doChangeSingleQuotesToSymbol($options = array())
    {
        foreach ($options as $key => $value) {
            if (!is_string($value)) {
                continue;
            } 
            
            $result = str_replace("'", '&#039;', $value);
            $options[$key] = stripslashes($result);
        }
        
        return $options;
    } // end doChangeSingleQuotesToSymbol
    
    public function updateCacheFile($fileName, $values)
    {
        if (!$this->fileSystem) {
            $this->fileSystem = $this->getFileSystemInstance();
        }
        
        if (!$this->fileSystem) {
            return false;
        }
   
        if (!$this->fileSystem->is_writable($this->pluginCachePath)) {
            return false;
        }
        
        $content = "<?php return '".$values."';";
        
        $filePath = $this->getPluginCachePath($fileName);

        $this->fileSystem->put_contents($filePath, $content, 0777);
    } //end updateCacheFile
    
    public function &getFileSystemInstance($method = 'direct')
    {
        $wpFileSystem = false;
        
        if ($this->_hasWordpessFileSystemObjectInGlobals()) {
            $wpFileSystem = $GLOBALS['wp_filesystem'];
        }

        if (!$wpFileSystem) {
            define('FS_METHOD', $method);
            WP_Filesystem();
            $wpFileSystem = $GLOBALS['wp_filesystem'];
        }

        return $wpFileSystem;
    } // end doWriteCacheToFile
    
    private function _hasWordpessFileSystemObjectInGlobals()
    {
        return array_key_exists('wp_filesystem', $GLOBALS);
    } // end _hasWordpessFileSystemObjectInGlobals
    
    public function onEnqueueJsFileAction($handle, $file = '', $deps = '')
    {
        $version = '';
        $inFooter = '';
        
        $args = func_get_args();
        
        if (isset($args[3])) {
            $version = $args[3];
        }
        
        if (isset($args[4])) {
            $inFooter = $args[4];
        }
        
        $src = '';
        
        if ($file) {
            $src = $this->getPluginJsUrl($file);
        }
        
        if (!$deps) {
            $deps = array();
        }
        
        if (!is_array($deps)) {
            $deps = array($deps);
        }
        
        wp_enqueue_script($handle, $src, $deps, $version, $inFooter);
    } // end  onEnqueueJsFileAction
    
    public function onEnqueueCssFileAction(
        $handle, $file = false, $deps = array()
    )
    {
        $version = false;
        $media = 'all';
        
        $args = func_get_args();
        
        if (isset($args[3])) {
            $version = $args[3];
        }
        
        if (isset($args[4])) {
            $media = $args[4];
        }
        
        $src = '';
        
        if ($file) {
            $src = $this->getPluginCssUrl($file);
        }
        
        if ($deps) {
            $deps = array($deps);
        }
        
        wp_enqueue_style($handle, $src, $deps, $version, $media);
    } // end  onEnqueueCssFileAction
    
    public function fetch($template, $vars = array()) 
    {
        if ($vars) {
            extract($vars);
        }

        ob_start();
              
        $templatePath = $this->getPluginTemplatePath($template); 
        
        include $templatePath;

        $content = ob_get_clean();    
        
        return $content;                
    } // end fetch
    
    public function getUrl()
    {
        $url = $_SERVER['REQUEST_URI'];
        
        $args = func_get_args();
        if (!$args) {
            return $url;
        }
        
        if (!is_array($args[0])) {
            $url = $args[0];
            $args = array_slice($args, 1);
        }

        if (isset($args[0]) && is_array($args[0])) {
            
            $data = parse_url($url);
            
            if (array_key_exists('query', $data)) {
                $url = $data['path'];
                parse_str($data['query'], $params);
                            
                foreach ($args[0] as $key => $value) {
                    if ($value != '') {
                       continue;
                    }
                    
                    unset($args[0][$key]);
                    
                    if (array_key_exists($key, $params)) {
                        unset($params[$key]);
                    }
                }
        
                $args[0] = array_merge($params, $args[0]);
            }

            $seperator = preg_match("#\?#Umis", $url) ? '&' : '?';
            $url .= $seperator.http_build_query($args[0]);
        }
        
        return $url;
    } // end getUrl
    
    public function displayError($error)
    {
        $this->displayMessage($error, 'error');
    } // end displayError
    
    public function displayUpdate($text)
    {
        $this->displayMessage($text, 'updated');
    } // end displayUpdate
    
    public function displayMessage($text, $type)
    {
        $message = __(
            $text,
            $this->languageDomain
        );
        
        $template = 'message.phtml';

        $vars = array(
            'type' => $type,
            'message' => $message
        );

        echo $this->fetch($template, $vars);
    }// end displayMessage
}