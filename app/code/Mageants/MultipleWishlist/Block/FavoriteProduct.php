<?php
/**
 * @category Mageants MultipleWishlist
 * @package Mageants_MultipleWishlist
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <info@mageants.com>
 */ 
namespace Mageants\MultipleWishlist\Block;
class FavoriteProduct extends \Magento\Framework\View\Element\Template
{    
    /**
     * @var \Magento\Catalog\Model\Product
     */
    protected $product;
    /**
     * @var \Magento\Customer\Model\SessionFactory
     */
    protected $_customerSession;


    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Mageants\MultipleWishlist\Model\ResourceModel\FavoriteProduct\CollectionFactory $favoriteProdFactory
     * @param \Mageants\MultipleWishlist\Model\ResourceModel\Favorite\CollectionFactory $favoriteListFactory
     * @param \Magento\Catalog\Model\Product $product
     * @param \Magento\Customer\Model\SessionFactory $customerSession 
     * @param array $data
     */

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Catalog\Model\Product $product,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Customer\Model\SessionFactory $customerSession,
        array $data = []
    )
    {    
        $this->product = $product; 
        $this->productRepository = $productRepository;
        $this->_customerSession = $customerSession; 
        parent::__construct($context, $data);
    } 
    /**
     * @return array
     */
    public function getProductData()
    {
        $productData = $this->product;  
        return $productData;
    }

    public function getProductByID($pid)
    {
        return $this->productRepository->getById($pid);  
        //return $productData;
    }
    /**
     * @return array
     */
    public function getCustomerData() {
         return $this->_customerSession->create()->getCustomerData();       
    }
}
