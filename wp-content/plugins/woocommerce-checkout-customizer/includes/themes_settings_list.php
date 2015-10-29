<?php
$settings = array(
    'bodyColorsDivider' => array(
        'caption' => __(
            'Body Content',
            $this->languageDomain
        ),
        'type'    => 'divider',
        'fieldsetKey' => 'theme',
    ),
    'contentBackground' => array(
        'caption' => __('Background', $this->languageDomain),
        'type'    => 'color_picker',
        'fieldsetKey' => 'theme',
    ),
    'contentBackgroundOpacity' => array(
        'caption' => __('Background Color Opacity', $this->languageDomain),
        'type' => 'slider',
        'min' => 0,
        'max' => 10,
        'fieldsetKey' => 'theme',
        'class' => 'pluginator-change-slider',
        'event' => 'change-slider'
    ),
    'contentBorderRadius' => array(
        'caption' => __(
            'Border Radius',
            $this->languageDomain
        ),
        'type' => 'slider',
        'class' => 'pluginator-change-slider',
        'event' => 'change-slider',
        'min' => 0,
        'max' => 50,
        'fieldsetKey' => 'theme'
    ),
    'paddingTabsDivider' => array(
        'caption' => __(
            'Padding for Tabs',
            $this->languageDomain
        ),
        'type'    => 'divider',
        'fieldsetKey' => 'theme',
    ),
    'tabsPaddingTop' => array(
        'caption' => __(
            'Padding Top',
            $this->languageDomain
        ),
        'lable' => 'px',
        'type' => 'slider',
        'class' => 'pluginator-change-slider',
        'event' => 'change-slider',
        'min' => 0,
        'max' => 50,
        'fieldsetKey' => 'theme',
    ),
    'tabsPaddingBottom' => array(
        'caption' => __(
            'Padding Bottom',
            $this->languageDomain
        ),
        'lable' => 'px',
        'type' => 'slider',
        'class' => 'pluginator-change-slider',
        'event' => 'change-slider',
        'min' => 0,
        'max' => 50,
        'fieldsetKey' => 'theme',
    ),
    'intervalTabsDivider' => array(
        'caption' => __(
            'Interval between Tabs',
            $this->languageDomain
        ),
        'type'    => 'divider',
        'fieldsetKey' => 'theme',
    ),
    'tabsHorizontalInterval' => array(
        'caption' => __(
            'For Horizontal Orientation',
            $this->languageDomain
        ),
        'lable' => 'px',
        'type' => 'slider',
        'class' => 'pluginator-change-slider',
        'event' => 'change-slider',
        'min' => 0,
        'max' => 50,
        'fieldsetKey' => 'theme',
    ),
    'tabsVerticalInterval' => array(
        'caption' => __(
            'For Vertical Orientation',
            $this->languageDomain
        ),
        'lable' => 'px',
        'type' => 'slider',
        'class' => 'pluginator-change-slider',
        'event' => 'change-slider',
        'min' => 0,
        'max' => 50,
        'fieldsetKey' => 'theme',
    ),
    'borderTabsDivider' => array(
        'caption' => __(
            'Tabs Border',
            $this->languageDomain
        ),
        'type'    => 'divider',
        'fieldsetKey' => 'theme',
    ),
    'tabsBorderRadius' => array(
        'caption' => __(
            'Radius',
            $this->languageDomain
        ),
        'lable' => 'px',
        'type' => 'slider',
        'class' => 'pluginator-change-slider',
        'event' => 'change-slider',
        'min' => 0,
        'max' => 50,
        'fieldsetKey' => 'theme'
    ),
    'backgroundColorsTabsDivider' => array(
        'caption' => __(
            'Tabs Background Colors',
            $this->languageDomain
        ),
        'type'    => 'divider',
        'fieldsetKey' => 'theme',
    ),
    'doneTabsColor' => array(
        'caption' => __(
            'Completed Color',
            $this->languageDomain
        ),
        'type'    => 'color_picker',
        'fieldsetKey' => 'theme'
    ),
    'disabledTabsColor' => array(
        'caption' => __(
            'Disabled Color',
            $this->languageDomain
        ),
        'type'    => 'color_picker',
        'fieldsetKey' => 'theme'
    ),
    'activeTabsColor' => array(
        'caption' => __(
            'Actived Color',
            $this->languageDomain
        ),
        'type'    => 'color_picker',
        'fieldsetKey' => 'theme'
    ),
    'hoverTabsColor' => array(
        'caption' => __(
            'Color on Hover',
            $this->languageDomain
        ),
        'type'    => 'color_picker',
        'fieldsetKey' => 'theme'
    ),
    'errorTabsColor' => array(
        'caption' => __(
            "Color if Didn't fill required fields",
            $this->languageDomain
        ),
        'type'    => 'color_picker',
        'fieldsetKey' => 'theme'
    ),
    'fontColorsTabsDivider' => array(
        'caption' => __(
            'Tabs Font Colors',
            $this->languageDomain
        ),
        'type'    => 'divider',
        'fieldsetKey' => 'theme',
    ),
    'doneFontColor' => array(
        'caption' => __(
            'Completed Color',
            $this->languageDomain
        ),
        'type'    => 'color_picker',
        'fieldsetKey' => 'theme'
    ),
    'disabledFontColor' => array(
        'caption' => __(
            'Disabled Color',
            $this->languageDomain
        ),
        'type'    => 'color_picker',
        'fieldsetKey' => 'theme'
    ),            
    'activeFontColor' => array(
        'caption' => __(
            'Actived Color',
            $this->languageDomain
        ),
        'type'    => 'color_picker',
        'fieldsetKey' => 'theme'
    ),            
    'hoverFontColor' => array(
        'caption' => __(
            'Color on Hover',
            $this->languageDomain
        ),
        'type'    => 'color_picker',
        'fieldsetKey' => 'theme'
    ),            
    'errorFontColor' => array(
        'caption' => __(
            "Color if Didn't fill required fields",
            $this->languageDomain
        ),
        'type'    => 'color_picker',
        'fieldsetKey' => 'theme'
    ),
    'numbersBackgroundColorDivider' => array(
        'caption' => __(
            'Fill for Circle Around Numbers',
            $this->languageDomain
        ),
        'type'    => 'divider',
        'fieldsetKey' => 'theme',
    ),
    'doneNumbersColor' => array(
        'caption' => __(
            'Completed Color',
            $this->languageDomain
        ),
        'type'    => 'color_picker',
        'fieldsetKey' => 'theme'
    ),
    'disabledNumbersColor' => array(
        'caption' => __(
            'Disabled Color',
            $this->languageDomain
        ),
        'type'    => 'color_picker',
        'fieldsetKey' => 'theme'
    ),
    'activeNumbersColor' => array(
        'caption' => __(
            'Actived Color',
            $this->languageDomain
        ),
        'type'    => 'color_picker',
        'fieldsetKey' => 'theme'
    ),
    'hoverNumbersColor' => array(
        'caption' => __(
            'Color on Hover',
            $this->languageDomain
        ),
        'type'    => 'color_picker',
        'fieldsetKey' => 'theme'
    ),
    'errorNumbersColor' => array(
        'caption' => __(
            "Color if Didn't fill required fields",
            $this->languageDomain
        ),
        'type'    => 'color_picker',
        'fieldsetKey' => 'theme'
    ),
    'numbersBorderColorDivider' => array(
        'caption' => __(
            'Border Colors for Circle Around Numbers',
            $this->languageDomain
        ),
        'type'    => 'divider',
        'fieldsetKey' => 'theme',
    ),
    'doneNumbersBorderColor' => array(
        'caption' => __(
            'Completed Color',
            $this->languageDomain
        ),
        'type'    => 'color_picker',
        'fieldsetKey' => 'theme'
    ),
    'disabledNumbersBorderColor' => array(
        'caption' => __(
            'Disabled Color',
            $this->languageDomain
        ),
        'type'    => 'color_picker',
        'fieldsetKey' => 'theme'
    ),
    'activeNumbersBorderColor' => array(
        'caption' => __(
            'Actived Color',
            $this->languageDomain
        ),
        'type'    => 'color_picker',
        'fieldsetKey' => 'theme'
    ),
    'hoverNumbersBorderColor' => array(
        'caption' => __(
            'Color on Hover',
            $this->languageDomain
        ),
        'type'    => 'color_picker',
        'fieldsetKey' => 'theme'
    ),
    'errorNumbersBorderColor' => array(
        'caption' => __(
            "Color if Didn't fill required fields",
            $this->languageDomain
        ),
        
        'type'    => 'color_picker',
        'fieldsetKey' => 'theme'
    ),
    'numbersFontColorDivider' => array(
        'caption' => __(
            'Numbers Font Colors',
            $this->languageDomain
        ),
        'type'    => 'divider',
        'fieldsetKey' => 'theme',
    ),
    'doneNumbersFontColor' => array(
        'caption' => __(
            'Completed Color',
            $this->languageDomain
        ),
        'type'    => 'color_picker',
        'fieldsetKey' => 'theme'
    ),
    'disabledNumbersFontColor' => array(
        'caption' => __(
            'Disabled Color',
            $this->languageDomain
        ),
        'type'    => 'color_picker',
        'fieldsetKey' => 'theme'
    ),
    'activeNumbersFontColor' => array(
        'caption' => __(
            'Actived Color',
            $this->languageDomain
        ),
        
        'type'    => 'color_picker',
        'fieldsetKey' => 'theme'
    ),
    'hoverNumbersFontColor' => array(
        'caption' => __(
            'Color on Hover',
            $this->languageDomain
        ),
        'type'    => 'color_picker',
        'fieldsetKey' => 'theme'
    ),
    'errorNumbersFontColor' => array(
        'caption' => __(
            "Color if Didn't fill required fields",
            $this->languageDomain
        ),
        
        'type'    => 'color_picker',
        'fieldsetKey' => 'theme'
    ),
    'borderActionsButtonsDivider' => array(
        'caption' => __(
            'Actions Buttons Border',
            $this->languageDomain
        ),
        'type'    => 'divider',
        'fieldsetKey' => 'theme',
    ),
    'actionsButtonsBorderRadius' => array(
        'caption' => __(
            'Radius',
            $this->languageDomain
        ),
        'lable' => 'px',
        'type' => 'slider',
        'class' => 'pluginator-change-slider',
        'event' => 'change-slider',
        'min' => 0,
        'max' => 50,
        'fieldsetKey' => 'theme'
    ),
    'actionsButtonsColorDivider' => array(
        'caption' => __(
            'Actions Buttons Colors',
            $this->languageDomain
        ),
        'type'    => 'divider',
        'fieldsetKey' => 'theme',
    ),
    'actionsButtonsColor' => array(
        'caption' => __(
            'Color',
            $this->languageDomain
        ),
        'type'    => 'color_picker',
        'fieldsetKey' => 'theme'
    ),
    'hoverActionsButtonsColor' => array(
        'caption' => __(
            'Color on Hover',
            $this->languageDomain
        ),
        'type'    => 'color_picker',
        'fieldsetKey' => 'theme'
    ),
    'disabledActionsButtonsColor' => array(
        'caption' => __(
            'Color on Disabled',
            $this->languageDomain
        ),
        'type'    => 'color_picker',
        'fieldsetKey' => 'theme'
    ),
    'actionsButtonsFontColorDivider' => array(
        'caption' => __(
            'Actions Buttons Font Colors',
            $this->languageDomain
        ),
        'type'    => 'divider',
        'fieldsetKey' => 'theme',
    ),
    'actionsButtonsFontColor' => array(
        'caption' => __(
            'Color',
            $this->languageDomain
        ),
        'type'    => 'color_picker',
        'fieldsetKey' => 'theme'
    ),
    'hoverActionsButtonsFontColor' => array(
        'caption' => __(
            'Color on Hover',
            $this->languageDomain
        ),
        'type'    => 'color_picker',
        'fieldsetKey' => 'theme'
    ),
    'disabledActionsButtonsFontColor' => array(
        'caption' => __(
            'Color on Disabled',
            $this->languageDomain
        ),
        'type'    => 'color_picker',
        'fieldsetKey' => 'theme'
    ),
    'fontSizeDivider' => array(
        'caption' => __(
            'Fonts Size',
            $this->languageDomain
        ),
        'type'    => 'divider',
        'fieldsetKey' => 'theme',
    ),
    'tabsFontSize' => array(
        'caption' => __('for Tabs', $this->languageDomain),
        'lable' => 'px',
        'type' => 'input_size',
        'default' => 14,
        'class' => 'pluginator-font-size',
        'fieldsetKey' => 'theme'
    ),
    'numbersFontSize' => array(
        'caption' => __('for Numbers', $this->languageDomain),
        'lable' => 'px',
        'type' => 'input_size',
        'default' => 14,
        'class' => 'pluginator-font-size',
        'fieldsetKey' => 'theme'
    ),
    'actionsButtonsFontSize' => array(
        'caption' => __('for Actions Buttons', $this->languageDomain),
        'lable' => 'px',
        'type' => 'input_size',
        'default' => 14,
        'class' => 'pluginator-font-size',
        'fieldsetKey' => 'theme'
    ),
    'stepsOrientation' => array(
        'caption' => __('Orientation', $this->languageDomain),
        'type'    => 'input_select',
        'fieldsetKey' => 'theme',
        'values' => array(
            'horizontal' => __('Horizontal', $this->languageDomain),
            'vertical' => __('Vertical', $this->languageDomain),
        ),
        'event' => 'visible',
    ),
    'stepsPosition' => array(
        'caption' => __('Position', $this->languageDomain),
        'type'    => 'input_select',
        'fieldsetKey' => 'theme',
        'values' => array(
            'left' => __('Left', $this->languageDomain),
            'right' => __('Right', $this->languageDomain),
        ),
        'eventClasses' => 'stepsOrientation',
    ),
    'stepsContainerWidth' => array(
        'caption' => __('Width for Steps Container ', $this->languageDomain),
        'lable' => '%',
        'type' => 'slider',
        'class' => 'pluginator-change-slider',
        'event' => 'change-slider',
        'min' => 0,
        'max' => 50,
        'fieldsetKey' => 'theme',
        'eventClasses' => 'stepsOrientation',
    ),
    'showStepsNumbers' => array(
        'caption' => __('Show Numbers', $this->languageDomain),
        'type'    => 'input_checkbox',
        'fieldsetKey' => 'theme',
        'lable' => 'Enable displaying numbers in steps'
    ),
);

return $settings;