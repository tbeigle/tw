<?php
$settings = array(
    'disableSteps' => array(
        'caption' => __('Disable Steps', $this->languageDomain),
        'type'    => 'multi_select',
        'fieldsetKey' => 'general',
        'values' => array(
            'login' => __('Login', $this->languageDomain),
            'shipping' => __('Shipping', $this->languageDomain),
            'reviewOrder' => __('Review Order', $this->languageDomain),
            'payment' => __('Payment', $this->languageDomain)
        ),
        'default' => array()
    ),
    'sortSteps' => array(
        'caption' => __('Sort Steps', $this->languageDomain),
        'type'    => 'sortable_select',
        'fieldsetKey' => 'general',
        'values' => array(
            1 => array(
                'step' => 'billing',
                'caption' => __('Billing', $this->languageDomain),
            ),
            2 => array(
                'step' => 'shipping',
                'caption' => __('Shipping', $this->languageDomain),
            ),
            3 => array(
                'step' => 'reviewOrder',
                'caption' => __('Review Order', $this->languageDomain),
            ),
            4 => array(
                'step' => 'payment',
                'caption' => __('Payment', $this->languageDomain)
            ),
            5 => array(
                'step' => 'custom',
                'caption' => __('Custom', $this->languageDomain),
            ),
        ),
        'default' => array(
            'billing' => 1,
            'shipping' => 2,
            'reviewOrder' => 3,
            'payment' => 4,
        )
    ),
    'termsAndConditionsDivider' => array(
        'caption' => __(
            'Terms and Conditions',
            $this->languageDomain
        ),
        'type'    => 'divider',
        'fieldsetKey' => 'general',
    ),
    'termsAndConditionsLocation' => array(
        'caption' => __('Location', $this->languageDomain),
        'type'    => 'input_select',
        'fieldsetKey' => 'general',
        'values' => array(
            'billing' => __('Billing', $this->languageDomain),
            'shipping' => __('Shipping', $this->languageDomain),
            'view-order' => __('Review Order', $this->languageDomain),
            'payment' => __('Payment', $this->languageDomain)
        ),
        'default' => 'payment'
    ),
    'termsAndConditionsPosition' => array(
        'caption' => __('Position', $this->languageDomain),
        'type'    => 'input_select',
        'fieldsetKey' => 'general',
        'values' => array(
            'left' => __('Left', $this->languageDomain),
            'right' => __('Right', $this->languageDomain),
        ),
        'default' => 'right'
    ),
    'enableCustomStep' => array(
        'caption' => __('Enable Custom Step', $this->languageDomain),
        'hint' => __(
            'In step will be displayed fields which created with the plugin'.
            ' WooCommerce Checkout Field Editor, see Additional Fields',
            $this->languageDomain
        ),
        'type'    => 'input_checkbox',
        'fieldsetKey' => 'general',
        'conditionForDisplay' => 'isWoocommerceCheckoutFieldEditorPluginActive',
        'event' => 'visible'
    ),
    'tabsTitlesDivider' => array(
        'caption' => __(
            'Tabs Titles',
            $this->languageDomain
        ),
        'type'    => 'divider',
        'fieldsetKey' => 'general',
    ),
    'customStepName' => array(
        'caption' => __('Custom Step', $this->languageDomain),
        'type'    => 'input_text',
        'fieldsetKey' => 'general',
        'conditionForDisplay' => 'isWoocommerceCheckoutFieldEditorPluginActive',
        'eventClasses' => 'enableCustomStep',
        'default' => __('Custom', $this->languageDomain)
    ),
    'loginStepName' => array(
        'caption' => __('Login Step', $this->languageDomain),
        'type'    => 'input_text',
        'fieldsetKey' => 'general',
        'default' => __('LogIn User', $this->languageDomain)
    ),
    'bilingStepName' => array(
        'caption' => __('Billing Step', $this->languageDomain),
        'type'    => 'input_text',
        'fieldsetKey' => 'general',
        'default' => __('Billing Details', $this->languageDomain)
    ),
    'shippingStepName' => array(
        'caption' => __('Shipping Step', $this->languageDomain),
        'type'    => 'input_text',
        'fieldsetKey' => 'general',
        'default' => __('Ship Details', $this->languageDomain)
    ),
    'reviewOrderStepName' => array(
        'caption' => __('Review Order Step', $this->languageDomain),
        'type'    => 'input_text',
        'fieldsetKey' => 'general',
        'default' => __('View order', $this->languageDomain)
    ),
    'paymentStepName' => array(
        'caption' => __('Payment Step', $this->languageDomain),
        'type'    => 'input_text',
        'fieldsetKey' => 'general',
        'default' => __('Payment', $this->languageDomain)
    ),
    'actionsButtonsTitlesDivider' => array(
        'caption' => __(
            'Actions Buttons Titles',
            $this->languageDomain
        ),
        'type'    => 'divider',
        'fieldsetKey' => 'general',
    ),
    'nextButtonName' => array(
        'caption' => __('Next', $this->languageDomain),
        'type'    => 'input_text',
        'fieldsetKey' => 'general',
        'default' => __('Next', $this->languageDomain)
    ),
    'previousButtonName' => array(
        'caption' => __('Previous', $this->languageDomain),
        'type'    => 'input_text',
        'fieldsetKey' => 'general',
        'default' => __('Previous', $this->languageDomain)
    ),
    'finishButtonName' => array(
        'caption' => __('Finish', $this->languageDomain),
        'type'    => 'input_text',
        'fieldsetKey' => 'general',
        'default' => __('Finish', $this->languageDomain)
    ),
    'noAccountButtonName' => array(
        'caption' => __('No Account', $this->languageDomain),
        'type'    => 'input_text',
        'fieldsetKey' => 'general',
        'default' => __("I don't have an account", $this->languageDomain)
    ),
    
    'requireFieldsDivider' => array(
        'caption' => __(
            'Require fields',
            $this->languageDomain
        ),
        'type'    => 'divider',
        'fieldsetKey' => 'general',
    ),
    'requireFieldsNotify' => array(
        'caption' => __('Enable Require Notify', $this->languageDomain),
        'type'    => 'input_checkbox',
        'fieldsetKey' => 'general',
        'event' => 'visible'
    ),
    'requireFieldsText' => array(
        'caption' => __('Notify Text', $this->languageDomain),
        'type'    => 'input_text',
        'fieldsetKey' => 'general',
        'default' => __('This field is required', $this->languageDomain),
        'eventClasses' => 'requireFieldsNotify',
    ),
    'requireFieldsTextColor' => array(
        'caption' => __(
            'Color',
            $this->languageDomain
        ),
        'type'    => 'color_picker',
        'fieldsetKey' => 'general',
        'default' => '#ff0000',
        'eventClasses' => 'requireFieldsNotify',
    ),
    'transitionDivider' => array(
        'caption' => __(
            'Transition',
            $this->languageDomain
        ),
        'type'    => 'divider',
        'fieldsetKey' => 'general',
    ),
    'transitionEffect' => array(
        'caption' => __('Effect', $this->languageDomain),
        'type'    => 'input_select',
        'fieldsetKey' => 'general',
        'values' => array(
            0 => __('none', $this->languageDomain),
            1 => __('fade', $this->languageDomain),
            3 => __('slideLeft', $this->languageDomain),
        ),
        'default' => 3
    ),
    'hideComplitedSteps' => array(
        'caption' => __('Hide Completed Steps', $this->languageDomain),
        'type'    => 'input_checkbox',
        'fieldsetKey' => 'general',
        'event' => 'visible'
    ),
    
);

return $settings;