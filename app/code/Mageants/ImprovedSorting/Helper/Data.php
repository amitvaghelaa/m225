<?php
/**
 * @category Mageants ImprovedSorting
 * @package Mageants_ImprovedSorting
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <info@mageants.com>
 */

namespace Mageants\ImprovedSorting\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,   
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {  
       $this->_scopeConfig = $scopeConfig; 
       $this->_storeManager = $storeManager;
    }


    public function getStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }

    public function getHidePositionSorting()
    {
        $hidePositionSorting = $this->_scopeConfig->getValue("improvedSorting_config/improvedSorting_general_settings/hide_position_sorting",\Magento\Store\Model\ScopeInterface::SCOPE_STORE,$this->getStoreId());

        return $hidePositionSorting;
    }

    public function getDisableSorting()
    { 
        $disableSorting = $this->_scopeConfig->getValue("improvedSorting_config/improvedSorting_general_settings/disable_sorting",\Magento\Store\Model\ScopeInterface::SCOPE_STORE,$this->getStoreId());

        $disableSorting = explode(",",$disableSorting);
        
        return $disableSorting;
    }

    public function getDefaultDesAttribute()
    { 
        $defaultDesAttribute = $this->_scopeConfig->getValue("improvedSorting_config/improvedSorting_general_settings/descending_sorting",\Magento\Store\Model\ScopeInterface::SCOPE_STORE,$this->getStoreId());

        $defaultDesAttribute = explode(",",$defaultDesAttribute);
        
        return $defaultDesAttribute;
    }

    public function getNewestLabel()
    { 
        $newestLabel = $this->_scopeConfig->getValue("improvedSorting_config/improvedSorting_newest/newest_label",\Magento\Store\Model\ScopeInterface::SCOPE_STORE,$this->getStoreId());
        
        return $newestLabel;
    }

    public function getBestsellerLabel()
    { 
        $bestsellerLabel = $this->_scopeConfig->getValue("improvedSorting_config/improvedSorting_bestsellers/bestsellers_label",\Magento\Store\Model\ScopeInterface::SCOPE_STORE,$this->getStoreId());
        
        return $bestsellerLabel;
    }

    public function getMostviewLabel()
    { 
        $mostviewLabel = $this->_scopeConfig->getValue("improvedSorting_config/improvedSorting_mostviewed/mostviewed_label",\Magento\Store\Model\ScopeInterface::SCOPE_STORE,$this->getStoreId());
        
        return $mostviewLabel;
    }

    public function getBiggestSavingLabel()
    { 
        $biggestSavingLabel = $this->_scopeConfig->getValue("improvedSorting_config/improvedSorting_biggest_saving/biggest_saving_label",\Magento\Store\Model\ScopeInterface::SCOPE_STORE,$this->getStoreId());
        
        return $biggestSavingLabel;
    }

    public function getWishlistsLabel()
    { 
        $wishlistsLabel = $this->_scopeConfig->getValue("improvedSorting_config/improvedSorting_now_wishlists/now_wishlists_label",\Magento\Store\Model\ScopeInterface::SCOPE_STORE,$this->getStoreId());
        
        return $wishlistsLabel;
    }

    public function getTopRatedLabel()
    { 
        $topRatedLabel = $this->_scopeConfig->getValue("improvedSorting_config/improvedSorting_top_rated/top_rated_label",\Magento\Store\Model\ScopeInterface::SCOPE_STORE,$this->getStoreId());
        
        return $topRatedLabel;
    }

    public function getReviewCountLabel()
    { 
        $reviewCountLabel = $this->_scopeConfig->getValue("improvedSorting_config/improvedSorting_reviews_count/reviews_count_label",\Magento\Store\Model\ScopeInterface::SCOPE_STORE,$this->getStoreId());
        
        return $reviewCountLabel;
    }

    public function getExcludeOrderSt()
    { 
        $excludeOrderSt = $this->_scopeConfig->getValue("improvedSorting_config/improvedSorting_bestsellers/bestsellers_order_status",\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        
        $order_statuses = explode(",",$excludeOrderSt);
        $order_status =  "'" . implode("','", $order_statuses) . "'";
       // $excludeOrderSt = explode(",",$excludeOrderSt);

        return $order_status;
    }

    public function getDefaultSortSearchPage()
    { 
        $defaultSortSearchPage = $this->_scopeConfig->getValue("improvedSorting_config/improvedSorting_general_settings/default_sorting_searchpage",\Magento\Store\Model\ScopeInterface::SCOPE_STORE,$this->getStoreId());
       
        return $defaultSortSearchPage;
    }

    public function getWishlistViewPeriod()
    { 
        $wishlistViewPeriod = $this->_scopeConfig->getValue("improvedSorting_config/improvedSorting_now_wishlists/now_wishlists_period",\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
       
        return $wishlistViewPeriod;
    }

    public function getBiggestSortPercentage()
    { 
        $biggestSortPercentage = $this->_scopeConfig->getValue("improvedSorting_config/improvedSorting_biggest_saving/biggest_saving_sorting",\Magento\Store\Model\ScopeInterface::SCOPE_STORE,$this->getStoreId());
       
        return $biggestSortPercentage;
    }

    public function getShowProductImageInLast()
    { 
        $showProductImageInLast = $this->_scopeConfig->getValue("improvedSorting_config/improvedSorting_general_settings/show_product_image",\Magento\Store\Model\ScopeInterface::SCOPE_STORE,$this->getStoreId());
       
        return $showProductImageInLast;
    }

    public function getShowOutOfStockProInLast()
    { 
        $showOutOfStockProInLast = $this->_scopeConfig->getValue("improvedSorting_config/improvedSorting_general_settings/show_outofstock_product",\Magento\Store\Model\ScopeInterface::SCOPE_STORE,$this->getStoreId());
       
        return $showOutOfStockProInLast;
    }

    public function getUseQtyOutOfStockPro()
    { 
        $useQtyOutOfStockPro = $this->_scopeConfig->getValue("improvedSorting_config/improvedSorting_general_settings/show_outofstock_qty_product",\Magento\Store\Model\ScopeInterface::SCOPE_STORE,$this->getStoreId());
       
        return $useQtyOutOfStockPro;
    }

    public function getMostViewPeriod()
    { 
        $mostViewPeriod = $this->_scopeConfig->getValue("improvedSorting_config/improvedSorting_mostviewed/mostview_period",\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
       
        return $mostViewPeriod;
    }

    public function getUseCustomBestSellerAttri()
    {  
        $useCustomBestSellerAttri = $this->_scopeConfig->getValue("improvedSorting_config/improvedSorting_bestsellers/bestsellers_sorting",\Magento\Store\Model\ScopeInterface::SCOPE_STORE,$this->getStoreId());
       
        return $useCustomBestSellerAttri;
    }

    public function getUseCustomMostviewAttri()
    { 
        $useCustomMostviewAttri = $this->_scopeConfig->getValue("improvedSorting_config/improvedSorting_mostviewed/mostviewed_sorting",\Magento\Store\Model\ScopeInterface::SCOPE_STORE,$this->getStoreId());
       
        return $useCustomMostviewAttri;
    }

    public function getUseCustomNewestAttri()
    { 
        $useCustomNewestAttri = $this->_scopeConfig->getValue("improvedSorting_config/improvedSorting_newest/newest_sorting",\Magento\Store\Model\ScopeInterface::SCOPE_STORE,$this->getStoreId());
       
        return $useCustomNewestAttri;
    }

    public function getBestSellerViewPeriod()
    { 
        $bestSellerViewPeriod = $this->_scopeConfig->getValue("improvedSorting_config/improvedSorting_bestsellers/bestsellers_period",\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
       
        return $bestSellerViewPeriod;
    }

    public function getModuleEnable()
    { 
        $moduleEnable = $this->_scopeConfig->getValue("improvedSorting_config/improvedSorting_general_settings/module_enable",\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
       
        return $moduleEnable;
    }

}