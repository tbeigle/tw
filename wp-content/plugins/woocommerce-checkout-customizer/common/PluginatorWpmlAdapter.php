<?php

abstract class PluginatorWpmlAdapter extends PluginatorPlugin
{    
    public function getOptions($optionName)
    {
        if (defined('WP_BLOG_ADMIN')) {
            remove_all_filters('option_'.$this->optionsPrefix.$optionName); 
        } 

        $options = array();
        
        if (!$this->_isWpmlActive()) {
            $options = $this->getCache($optionName);
        }

        if ($this->_isWpmlActive() || !$options) {
            $options = get_option($this->optionsPrefix.$optionName); 
        }
        
        if ($this->isJson($options)) {
            $options = json_decode($options, true);
        } else {
            $options = unserialize($options);
        }
           
        return $options;
    } // end getOptions
    
    private function _isWpmlActive()
    {
        $path = 'sitepress-multilingual-cms/sitepress.php';
        return $this->isPluginActive($path);
    } // end _isWpmlActive
    
    public function updateOptions($optionName, $values = array())
    {
        $values = $this->doChangeSingleQuotesToSymbol($values);
        
        $value = serialize($values);

        delete_option($this->optionsPrefix.$optionName);
        $result = add_option($this->optionsPrefix.$optionName, $value, '', 'no');
        
        if (!$this->_isWpmlActive()) {
            $result = $this->updateCacheFile($optionName, $value);
        }

        return $result;
    } // end updateOptions
    
    protected function isJson($string)
    {
        if (!is_string($string)) {
            return false;
        }
        
        $result = json_decode($string, true);

        return is_array($result);
    } // end isJson
    
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
}