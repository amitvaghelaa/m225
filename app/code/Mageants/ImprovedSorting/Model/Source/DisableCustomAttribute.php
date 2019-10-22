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
class DisableCustomAttribute implements \Magento\Framework\Data\OptionSourceInterface
{

    /**
     * @return array
     */

    public function toOptionArray()
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

