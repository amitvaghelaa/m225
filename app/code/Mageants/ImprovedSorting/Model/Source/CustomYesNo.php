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
class CustomYesNo implements \Magento\Framework\Data\OptionSourceInterface
{

    /**
     * @return array
     */

    public function toOptionArray()
    {
        $options = [];
        $options[] = ['label' => __('No'), 'value' => "0"];
        $options[] = ['label' => __('Yes'), 'value' => "1"];
        $options[] = ['label' => __('Yes for Catalog, No for Search'), 'value' => "2"];
     
       return $options;
    }
}

