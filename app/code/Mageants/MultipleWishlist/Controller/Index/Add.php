<?php
/**
 * @category Mageants MultipleWishlist
 * @package Mageants_MultipleWishlist
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <info@mageants.com>
 */
namespace Mageants\MultipleWishlist\Controller\Index;

use Magento\Framework\Controller\ResultFactory;

use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Cache\Frontend\Pool;

class Add extends \Magento\Framework\App\Action\Action
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
     * @var \Mageants\MultipleWishlist\Model\WishlistFactory
     */
    public $wishlistFactory;

    /**
      * @param \Magento\Framework\App\Action\Context $context
      * @param \Magento\Framework\App\Request\Http $request
      * @param \Mageants\MultipleWishlist\Model\ResourceModel\Wishlist\CollectionFactory $wishlistColl
      * @param \Mageants\MultipleWishlist\Model\WishlistFactory $favoriteList
      * @param \Magento\Framework\Message\ManagerInterface $messageManager
      */

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Request\Http $request,
        \Mageants\MultipleWishlist\Model\ResourceModel\Wishlist\CollectionFactory $wishlistColl,
        \Mageants\MultipleWishlist\Model\WishlistFactory $wishlistFactory,
        TypeListInterface $cacheTypeList, 
        Pool $cacheFrontendPool,
        \Magento\Framework\Message\ManagerInterface $messageManager
    ) {
        $this->request = $request;
        $this->wishlistColl = $wishlistColl;
        $this->wishlistFactory = $wishlistFactory;
        $this->messageManager = $messageManager;
        $this->cacheTypeList = $cacheTypeList;
        $this->cacheFrontendPool = $cacheFrontendPool;
        parent::__construct($context);
    }
    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $wishlistFactory = $this->wishlistFactory->create();

        if ($this->request->getParam('ajaxfavlist') != "" &&
            $this->request->getParam('ajaxfavlist') == 'magewishlist') {
            $productId = $this->request->getParam('product_id');
            $customerId = $this->request->getParam('customer_id');
            $wishlistName = $this->request->getParam('wishlist_name');
            $wishlistColl = $this->wishlistColl->create()
                    ->addFieldToFilter('customer_id', array('eq' => $customerId))
                    ->addFieldToFilter('mage_wishlist_name', array('eq' => $wishlistName));
        
            if (!$wishlistColl->getSize() && $wishlistName != 'Default') {
                if ($wishlistName== '') {
                    $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
                    $message = "empty";
                    $resultJson->setData($message);
                    return $resultJson;
                } elseif (strlen($wishlistName) >= 21) {
                    $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
                    $message = "tooLong";
                    $resultJson->setData($message);
                    return $resultJson;
                } else {
                    $data=array('mage_wishlist_name'=>$wishlistName,'customer_id'=>$customerId);
                    $id = $wishlistFactory->setData($data)->save()->getId();
                    $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
                    $message = "Wishlist created successfully";
                    $resultJson->setData($message);
                    $this->messageManager->addSuccess(__("Wishlist created successfully"));
                    $_types = [
                            'full_page'
                            ];
                 
                    foreach ($_types as $type) {
                        $this->cacheTypeList->cleanType($type);
                    }
                    return $resultJson;
                }
            } else {
                $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
                $message = "exist";
                $resultJson->setData($message);
                return $resultJson;
            }
        }
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        return $resultPage;
    }
}
