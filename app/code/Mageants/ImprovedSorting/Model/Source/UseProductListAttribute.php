<?php
/**
 * @category Mageants ImprovedSorting
 * @package Mageants_ImprovedSorting
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <info@mageants.com>
 */

namespace Mageants\ImprovedSorting\Model\Source;

/**
 * Class Attribute
 * @package Mageants\ImprovedSorting\Model\Source
 */
class UseProductListAttribute implements \Magento\Framework\Data\OptionSourceInterface
{
    protected $_productListAttribute;


    public function __construct(\Magento\Catalog\Model\ResourceModel\Config $attribute)
     {
        $this->_productListAttribute = $attribute;
    }

    /**
     * @return array
     */

    public function toOptionArray()
    {
        $productListAttribute = $this->_productListAttribute->getAttributesUsedInListing();
        $options = [];
        $options[] = ['label' => __(' '), 'value' => ' '];
        foreach ($productListAttribute as $key => $attribute) {
           $options[] = ['label' => $attribute['frontend_label'], 'value' => $attribute['attribute_code']];
        }      
       
       return $options;
    }
}

