<?php
class CheckoutStepsFrontendPluginatorPlugin 
      extends WooCheckoutStepsFrontendPluginatorPlugin
{
    protected function onInit()
    {
        parent::onInit();
        $this->addActionListener(
            'wp',
            'onRemoveWoocommerceActionsAction'
        );
    } // end onInit
    
    public function onRemoveWoocommerceActionsAction()
    {
        remove_action(
            'woocommerce_checkout_order_review',
            'woocommerce_order_review',
            10
        );
    } // end onRemoveWoocommerceActionsAction
    
    protected function appendReviewOrderContent(&$steps)
    {
        $steps['reviewOrder']['content'] = $this->fetch(
            'view_order_content.phtml'
        );
    } // end appendReviewOrderContent
    
    protected function getFolderWithFile()
    {             
        $folder = 'v'.$this->wooVersionWhithUpdateWichBrokePlugin.'/';
        
        return $folder;
    } // end getFolderWithFile
    
    protected function getWoocommerceTemplatesPath($templateName)
    {
        return $this->getPluginTemplatePath('v2.3/woocommerce/'.$templateName);
    } // end getWoocommerceTemplatesPath
}