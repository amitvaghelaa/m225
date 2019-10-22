<?php

namespace Mageants\BannerSlider\Model\Config\Source;

class Mode implements \Magento\Framework\Option\ArrayInterface
{
	
    public function toOptionArray()
    {
       return [
            ['value' => 'fade',       'label' => __('Fade')],
            ['value' => 'vertical',   'label' => __('Vertical')],
            ['value' => 'horizontal', 'label' => __('Horizontal')]
        ];
    }
}