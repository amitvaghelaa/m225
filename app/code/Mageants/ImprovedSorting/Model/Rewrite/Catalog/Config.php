<?php
/**
 * @category Mageants ImprovedSorting
 * @package Mageants_ImprovedSorting
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <info@mageants.com>
 */

namespace Mageants\ImprovedSorting\Model\Rewrite\Catalog;

use \Magento\Catalog\Model\Config as ConfigCore;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Serialize\SerializerInterface;
use Mageants\ImprovedSorting\Helper\Data;
use Mageants\ImprovedSorting\Model\SortOrderFactory;

class Config extends ConfigCore
{
    /**
     * @var \Mageants\ImprovedSorting\Helper\Data
     */
    protected $_dataHelper;

    protected $_sortOrderFactory;
 
    public function __construct(
        \Magento\Framework\App\CacheInterface $cache,
        \Magento\Eav\Model\Entity\TypeFactory $entityTypeFactory,
        \Magento\Eav\Model\ResourceModel\Entity\Type\CollectionFactory $entityTypeCollectionFactory,
        \Magento\Framework\App\Cache\StateInterface $cacheState,
        \Magento\Framework\Validator\UniversalFactory $universalFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Catalog\Model\ResourceModel\ConfigFactory $configFactory,
        \Magento\Catalog\Model\Product\TypeFactory $productTypeFactory,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute\Group\CollectionFactory $groupCollectionFactory,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory $setCollectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Eav\Model\Config $eavConfig,
        SerializerInterface $serializer = null,
        Data $dataHelper,
        SortOrderFactory $sortOrderObj
    )
    {
        parent::__construct(
        $cache,
        $entityTypeFactory,
        $entityTypeCollectionFactory,
        $cacheState,
        $universalFactory,
        $scopeConfig,
        $configFactory,
        $productTypeFactory,
        $groupCollectionFactory,
        $setCollectionFactory,
        $storeManager,
        $eavConfig,
        $serializer
        );

        $this->_dataHelper = $dataHelper;
        $this->_sortOrderFactory = $sortOrderObj;
    }

	 /**
     * Retrieve Attributes Used for Sort by as array
     * key = code, value = name
     *
     * @return array
     */
    public function getAttributeUsedForSortByArray()
    {   
        
        $sortOrderObj = $this->_sortOrderFactory->create();
        $sortOrderCollection = $sortOrderObj->getCollection();
        $sortOrderCollection->getSelect()->order('sortorder ASC');
        $moduleEnable = $this->_dataHelper->getModuleEnable();
        if(count($sortOrderCollection) >= 1 && $moduleEnable):

            $options = $this->customSortOrder($sortOrderCollection);

        else:
            $options = ['position' => __('Position')];
            foreach ($this->getAttributesUsedForSortBy() as $attribute) {
             /* @var $attribute \Magento\Eav\Model\Entity\Attribute\AbstractAttribute */
            $options[$attribute->getAttributeCode()] = $attribute->getStoreLabel();
            } 

        endif;

        return $options;
    }

    public function customSortOrder($sortOrderCollection)
    {

        $newestLabel = $this->_dataHelper->getNewestLabel();
        $bestsellerLabel = $this->_dataHelper->getBestsellerLabel();
        $mostviewLabel = $this->_dataHelper->getMostviewLabel();
        $biggestSavingLabel = $this->_dataHelper->getBiggestSavingLabel();
        $wishlistsLabel = $this->_dataHelper->getWishlistsLabel();
        $topRatedLabel = $this->_dataHelper->getTopRatedLabel();
        $reviewCountLabel = $this->_dataHelper->getReviewCountLabel();

        foreach ($sortOrderCollection as $attribute) {
            
            if($attribute['attribute_code'] == "created_at"):
                
                if($newestLabel):

                    $options[$attribute['attribute_code']] = $newestLabel;

                else:

                    $options[$attribute['attribute_code']] = $attribute['attribute_label'];

                endif;  

            elseif($attribute['attribute_code'] == "bestsellers"):

                if($bestsellerLabel):

                    $options[$attribute['attribute_code']] = $bestsellerLabel;

                else:

                    $options[$attribute['attribute_code']] = $attribute['attribute_label'];

                endif;      

            elseif($attribute['attribute_code'] == "most_viewed"):

                if($mostviewLabel):

                    $options[$attribute['attribute_code']] = $mostviewLabel;

                else:

                    $options[$attribute['attribute_code']] = $attribute['attribute_label'];

                endif; 

            elseif($attribute['attribute_code'] == "saving"):

                if($biggestSavingLabel):

                    $options[$attribute['attribute_code']] = $biggestSavingLabel;

                else:

                    $options[$attribute['attribute_code']] = $attribute['attribute_label'];

                endif;  

            elseif($attribute['attribute_code'] == "wished"):

                if($wishlistsLabel):

                    $options[$attribute['attribute_code']] = $wishlistsLabel;

                else:

                    $options[$attribute['attribute_code']] = $attribute['attribute_label'];

                endif;  

            elseif($attribute['attribute_code'] == "rating_summary"):

                if($topRatedLabel):

                    $options[$attribute['attribute_code']] = $topRatedLabel;

                else:

                    $options[$attribute['attribute_code']] = $attribute['attribute_label'];

                endif; 

             elseif($attribute['attribute_code'] == "reviews_count"):

                if($reviewCountLabel):

                    $options[$attribute['attribute_code']] = $reviewCountLabel;

                else:

                    $options[$attribute['attribute_code']] = $attribute['attribute_label'];

                endif; 

            else:

                $options[$attribute['attribute_code']] = $attribute['attribute_label'];

            endif;
        } 

        $hidePositionSorting = $this->_dataHelper->getHidePositionSorting();

        $disableSorting = $this->_dataHelper->getDisableSorting();

        if($disableSorting):

            $options = $this->checkDisableSorting($options,$disableSorting);

        endif;

        if($hidePositionSorting != 0):
            unset($options['position']);
        endif;

        return $options;
    }

    public function checkDisableSorting($options,$disableSorting)
    { 

        foreach ($options as $key => $option) {
           
            if(in_array($key, $disableSorting))
            {
                 unset($options[$key]);
            }

        }

        return $options;
    }


}