<?php
/**
 * @category Mageants MultipleWishlist
 * @package Mageants_MultipleWishlist
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <info@mageants.com>
 */ 
namespace Mageants\MultipleWishlist\Block;
class WishlistData extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Framework\App\Request\Http
     */
    public $request;
    /**
     * @var \Mageants\MultipleWishlist\Model\ResourceModel\Wishlist\CollectionFactory
     */
    public $wishlistColl;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    public $scopeConfig; 
    /**
     * @var \Magento\Customer\Model\SessionFactory
     */
    protected $_customerSession;
     /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterfaces
     */
    public $productRepository; 
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;
    /**
     * @var \Magento\Catalog\Model\Product
     */
    public $product;
    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $_messageManager;

     /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\App\Request\Http $request
     * @param \Mageants\MultipleWishlist\Model\WishlistProductFactory $wishlistProductFactory
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Mageants\MultipleWishlist\Model\ResourceModel\Wishlist\CollectionFactory $wishlistColl
     * @param \Mageants\MultipleWishlist\Model\WishlistFactory $favoriteList
     * @param \Magento\Customer\Model\SessionFactory $customerSession 
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig 
     * @param \Magento\Catalog\Model\Product $product
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \Magento\Framework\Registry $coreRegistry
     */ 

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\Request\Http $request,
        \Mageants\MultipleWishlist\Model\ResourceModel\Wishlist\CollectionFactory $wishlistColl,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Customer\Model\SessionFactory $customerSession,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Catalog\Model\Product $product,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\Message\ManagerInterface $messageManager
       
    ) {
      $this->request = $request;
      $this->wishlistColl = $wishlistColl;
      $this->productRepository = $productRepository;
      $this->_customerSession = $customerSession; 
      $this->scopeConfig = $scopeConfig;
      $this->_coreRegistry = $coreRegistry;
      $this->product = $product;
      $this->_messageManager = $messageManager;
      parent::__construct($context);
    }
  /**
   * @return array
   */
  public function getWishlistProductColl()
  {
    if ($this->getCustomerData()) {
      $customerId = $this->getCustomerData()->getId();
      $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
      $wishlist = $objectManager->get('\Magento\Wishlist\Model\Wishlist');
      $wishlist_collection = $wishlist->loadByCustomerId($customerId, true)->getItemCollection();
      return $wishlist_collection;
    }

    return false;
  }
  public function getProductData()
  {
      $productData = $this->product;  
      return $productData;
  }
  /**
   * @return array
   */
  public function getProductByID($productId)
  {
      return $this->productRepository->getById($productId);  
  }
  /**
   * @return array
   */
  public function getCustomerData() 
  {
       return $this->_customerSession->create()->getCustomerData();       
  }
  /**
   * @return array
   */
  public function getWishlistCollection()
  {
      $customerId = $this->getCustomerData()->getId();
      $wishlistCollection = $this->wishlistColl->create()->addFieldToFilter('customer_id', array('eq' => $customerId));
      return $wishlistCollection;
      
  }  
  public function getMessageManager()
  {
    return $this->_messageManager;
  }
}
