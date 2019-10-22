<?php
/**
 * @category  Mageants AjaxShoppingCart
 * @package   Mageants_AjaxShoppingCart
 * @copyright Copyright (c) 2019 Magento
 * @author    Mageants Team <support@mageants.com>
 */
namespace Mageants\AjaxShoppingCart\Model\Config\Source;

class Selling implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => '0', 'label' => __('None')],
            ['value' => 'related', 'label' => __('Related')],
            ['value' => 'crosssell', 'label' => __('Cross-sell')]
        ];
    }
}
