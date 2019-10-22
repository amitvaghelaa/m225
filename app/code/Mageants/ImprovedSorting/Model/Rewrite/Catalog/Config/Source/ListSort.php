<?php
/**
 * @category Mageants ImprovedSorting
 * @package Mageants_ImprovedSorting
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <info@mageants.com>
 */

namespace Mageants\ImprovedSorting\Model\Rewrite\Catalog\Config\Source;

use Mageants\ImprovedSorting\Helper\Data;

class ListSort extends \Magento\Catalog\Model\Config\Source\ListSort {

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

	public function toOptionArray()
    {
        $options = [];
        $options[] = ['label' => __('Position'), 'value' => 'position'];
        foreach ($this->_getCatalogConfig()->getAttributesUsedForSortBy() as $attribute) {
            $options[] = ['label' => __($attribute['frontend_label']), 'value' => $attribute['attribute_code']];
        }

        $moduleEnable = $this->_dataHelper->getModuleEnable();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
        $request = $objectManager->get('Magento\Framework\App\Request\Http'); 
        $param = $request->getParam('store');
        if($param){
        $options = $this->_customAttribute($options);
        }elseif($moduleEnable){
        $options = $this->_customAttribute($options);
        }
        
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
