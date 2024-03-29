<?php
/**
 * @category Mageants ImprovedSorting
 * @package Mageants_ImprovedSorting
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <info@mageants.com>
 */

namespace Mageants\ImprovedSorting\Block\Product;


use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Collection\AbstractCollection;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Catalog\Helper\Product\ProductList;
use Mageants\ImprovedSorting\Helper\Data;


class ListProduct extends \Magento\Catalog\Block\Product\ListProduct
{

    protected $_dataHelper;

    protected $_direction = ProductList::DEFAULT_SORT_DIRECTION;

    public function __construct(
       \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Data\Helper\PostHelper $postDataHelper,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        CategoryRepositoryInterface $categoryRepository,
        \Magento\Framework\Url\Helper\Data $urlHelper,
        Data $dataHelper,
        array $data = []
    ) {

        parent::__construct($context,
            $postDataHelper,
            $layerResolver,
            $categoryRepository,
            $urlHelper,
            $data
        );
      
        $this->_dataHelper = $dataHelper;
     
    }

	/**    
     * Retrieve loaded category collection
     *
     * @return AbstractCollection
     */
    protected function _getProductCollection()
    { 
        if ($this->_productCollection === null) {
            $layer = $this->getLayer();
            /* @var $layer \Magento\Catalog\Model\Layer */
            if ($this->getShowRootCategory()) {
                $this->setCategoryId($this->_storeManager->getStore()->getRootCategoryId());
            }
            // if this is a product view page
            if ($this->_coreRegistry->registry('product')) {
                // get collection of categories this product is associated with
                $categories = $this->_coreRegistry->registry('product')
                    ->getCategoryCollection()->setPage(1, 1)
                    ->load();
                // if the product is associated with any category
                if ($categories->count()) {
                    // show products from this category
                    $this->setCategoryId(current($categories->getIterator()));
                }
            }
            $origCategory = null;
            if ($this->getCategoryId()) {
                try {
                    $category = $this->categoryRepository->get($this->getCategoryId());
                } catch (NoSuchEntityException $e) {
                    $category = null;
                }
                if ($category) {
                    $origCategory = $layer->getCurrentCategory();
                    $layer->setCurrentCategory($category);
                }
            }
            
            $this->_productCollection = $layer->getProductCollection();
            
            $this->prepareSortableFieldsByCategory($layer->getCurrentCategory());
            if ($origCategory) {
                $layer->setCurrentCategory($origCategory);
            }
        }
       
        return $this->_productCollection;
    }

    public function getDefaultDirection()
    {  
        $Availablesort = $this->getSortBy();
        
        $defaultDesAttri = $this->_dataHelper->getDefaultDesAttribute();

        if(in_array($Availablesort, $defaultDesAttri))
        {
            return 'desc';
        }else{
            return ProductList::DEFAULT_SORT_DIRECTION;
        }     
        
    }
   /*  public function getSortBy()
    {  
        $Availablesort = $this->getAvailableOrders();
        if($Availablesort){
            foreach ($Availablesort as $key => $value) {
                return $key;
            }
        }     
    }*/
}