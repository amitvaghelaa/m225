<?php
/**
 * @category Mageants ImprovedSorting
 * @package Mageants_ImprovedSorting
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <info@mageants.com>
 */

namespace Mageants\ImprovedSorting\Model\Rewrite\Catalog\Category\Attribute\Source;

use Mageants\ImprovedSorting\Helper\Data;

class Sortby extends \Magento\Catalog\Model\Category\Attribute\Source\Sortby 
{

    /**
     * @var \Mageants\ImprovedSorting\Helper\Data
     */
    protected $_dataHelper;

    public function __construct(
        \Magento\Catalog\Model\Config $catalogConfig,
        Data $dataHelper
    )
    {
        parent::__construct($catalogConfig);
        $this->_dataHelper = $dataHelper;
    }

	/**
     * {@inheritdoc}
     */
    public function getAllOptions()
    {
        if ($this->_options === null) {
            $this->_options = [['label' => __('Position'), 'value' => 'position']];
            foreach ($this->_getCatalogConfig()->getAttributesUsedForSortBy() as $attribute) {
                $this->_options[] = [
                    'label' => __($attribute['frontend_label']),
                    'value' => $attribute['attribute_code'],
                ];
            }

            $moduleEnable = $this->_dataHelper->getModuleEnable();

            if($moduleEnable){
                $this->_options = $this->_customAttribute($this->_options);
            }
            
        }
        return $this->_options;
    }

    public function _customAttribute($_options)
    {

        $_options[] = ['label' => __('Best Sellers'), 'value' => "bestsellers"];
        $_options[] = ['label' => __('Most Viewed'), 'value' => "most_viewed"];
        $_options[] = ['label' => __('Now in Wishlists'), 'value' => "wished"];
        $_options[] = ['label' => __('Reviews Count'), 'value' => "reviews_count"];
        $_options[] = ['label' => __('Top Rated'), 'value' => "rating_summary"];
        $_options[] = ['label' => __('New'), 'value' => "created_at"];
        $_options[] = ['label' => __('Biggest Saving'), 'value' => "saving"];
        
       
       return $_options;
    }
}


