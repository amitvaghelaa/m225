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
class CustomSearchAttribute implements \Magento\Framework\Data\OptionSourceInterface
{


    protected $_configAttribute;


    public function __construct(\Magento\Catalog\Model\Config $catalogConfig)
    {
        $this->_configAttribute = $catalogConfig;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [];
        $options[] = ['label' => __('Relevance'), 'value' => 'relevance'];
        foreach ($this->_configAttribute->getAttributesUsedForSortBy() as $attribute) {
            $options[] = ['label' => __($attribute['frontend_label']), 'value' => $attribute['attribute_code']];
        }

        $options = $this->_customAttribute($options);

        return $options;

    }

    public function _customAttribute($options)
    {

        $options[] = ['label' => __('Best Sellers'), 'value' => "bestsellers"];
        $options[] = ['label' => __('Most Viewed'), 'value' => "most_viewed"];
        $options[] = ['label' => __('Now in Wishlists'), 'value' => "wished"];
        $options[] = ['label' => __('Reviews Count'), 'value' => "reviews_count"];
        $options[] = ['label' => __('Top Rated'), 'value' => "rating_summary"];
        $options[] = ['label' => __('New'), 'value' => "created_at"];
        $options[] = ['label' => __('Biggest Saving'), 'value' => "saving"];
        
       
       return $options;
    }
}

