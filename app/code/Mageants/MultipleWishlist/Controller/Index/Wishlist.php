<?php
/**
 * @category Mageants MultipleWishlist
 * @package Mageants_MultipleWishlist
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <info@mageants.com>
 */
namespace Mageants\MultipleWishlist\Controller\Index;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\UrlInterface;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Cache\Frontend\Pool;

class Wishlist extends \Magento\Framework\App\Action\Action
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
     * @var \Magento\Customer\Model\CustomerFactory
     */
    public $customerFactory;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    public $scopeConfig;
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;
    /**
     * @var \Magento\Catalog\Model\Product
     */
    public $product;
    /**
     * @var \Mageants\CustomerApproval\Helper\Data
     */
    public $helperData;
    /**
     * @var UrlInterface
     */
    public $urlBuilder;

    /**
      * @param \Magento\Framework\App\Action\Context $context
      * @param \Magento\Framework\App\Request\Http $request
      * @param \Mageants\MultipleWishlist\Model\ResourceModel\Wishlist\CollectionFactory $wishlistColl
      * @param \Mageants\MultipleWishlist\Model\WishlistFactory $wishlistFactory
      * @param \Magento\Customer\Model\CustomerFactory $customerFactory
      * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
      * @param \Magento\Framework\Registry $coreRegistry
      * @param \Magento\Catalog\Model\Product $product
      * @param \Magento\Framework\Data\Form\FormKey $formKey
      * @param \Magento\Checkout\Model\Cart $cart
      * @param \Mageants\CustomerApproval\Helper\Data $helperData
      * @param UrlInterface $urlBuilder
      */

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Request\Http $request,
        \Mageants\MultipleWishlist\Model\ResourceModel\Wishlist\CollectionFactory $wishlistColl,
        \Magento\Wishlist\Model\ResourceModel\Item\Option\CollectionFactory $wishlistCollection,
        \Mageants\MultipleWishlist\Model\WishlistFactory $wishlistFactory,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Wishlist\Model\ItemFactory $itemFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Checkout\Model\Cart $cart,
        \Magento\Framework\Data\Form\FormKey $formKey,
        \Magento\Catalog\Model\Product $product,
        \Mageants\MultipleWishlist\Helper\Data $helperData,
        \Magento\Wishlist\Model\Wishlist $wishlist,
        \Magento\Wishlist\Model\WishlistFactory $wishlistRepository,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        TypeListInterface $cacheTypeList, 
        Pool $cacheFrontendPool,
        UrlInterface $urlBuilder
    ) {
        $this->wishlistCollection = $wishlistCollection;
        $this->request = $request;
        $this->wishlistColl = $wishlistColl;
        $this->wishlistFactory = $wishlistFactory;
        $this->customerFactory = $customerFactory;
        $this->scopeConfig = $scopeConfig;
        $this->_coreRegistry = $coreRegistry;
        $this->product = $product;
        $this->formKey = $formKey;
        $this->cart = $cart;
        $this->helperData = $helperData;        
        $this->itemFactory = $itemFactory;
        $this->wishlist = $wishlist;
        $this->cacheTypeList = $cacheTypeList;
        $this->cacheFrontendPool = $cacheFrontendPool;
        $this->_wishlistRepository = $wishlistRepository;
        $this->_productRepository = $productRepository;
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context);
    }
    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $wishlistColl = $this->wishlistColl->create();
        $wishlistFactory = $this->wishlistFactory->create();

        if ($this->request->getParam('wishlistdata') == "defaultRemove") {
            $customerId = $this->request->getParam('customer_id');
            $productId = $this->request->getParam('product_id');
            $mageWishlistId = $this->request->getParam('mageWishlist_id');
            $wish = $this->wishlist->loadByCustomerId($customerId);
            $items = $wish->getItemCollection();

            /** @var \Magento\Wishlist\Model\Item $item */
            foreach ($items as $item) {
                if ($item->getWishlistItemId() == $productId) {
                    $item->delete();
                    $wish->save();
                }
            }

            $this->_redirect(
                'favorite/index/index',
                array('_secure' => true,
                    'mage_wishlist_id' => $mageWishlistId,
                    'customer_id' => $customerId
                )
            );
        }
        
        if ($this->request->getParam('wishlistdata') == 'addtocart') {
            $productId =$this->request->getParam('product_id');
            $product = $this->product->load($productId);
            $typeId = $product->getTypeId();
            $params = array(
                        'form_key' => $this->formKey->getFormKey(),
                        'product' => $productId
                    );
            $this->cart->addProduct($product, $params);
            $this->cart->save();
        }

        if ($this->request->getParam('wishlistdata') == 'moveto') {
            $customerId = $this->request->getParam('customer_id');
            $mageWishlistId = $this->request->getParam('mage_wishlist_id');
            $productId = $this->request->getParam('product_id');
            $itemId = $this->request->getParam('item_id');
            $wishlistChangeId = $this->request->getParam('wishlist_change_id');        
                if ($productId) {
                    $existProducts = $this->_wishlistRepository->create()->loadByCustomerId($customerId, true)->getWishItemCollection($wishlistChangeId)->addFieldToFilter('product_id', $productId);
                }

                if (count($existProducts) != 0) {     
                    foreach ($existProducts as $existProduct) {
                        $existProductId = $existProduct['wishlist_item_id'];
                        $existProductQty = $existProduct['qty'];
                    }

                    $changProOption = $this->wishlistCollection->create()->addFieldToFilter('wishlist_item_id', $itemId)->getData();
                    $existProOption = $this->wishlistCollection->create()->addFieldToFilter('wishlist_item_id', $existProductId)->getData();
                    $isSame = 0;
                    if (count($existProOption) == count($changProOption)) {
                        for ($i=0; $i < count($existProOption); $i++) { 
                            if ($existProOption[$i]['product_id'] == $changProOption[$i]['product_id']) {
                                if ($isSame != 1) {
                                    $isSame = 0;
                                }
                            }
                            else{
                                $isSame = 1;
                            }
                        }
                    }
                    else{
                        $isSame = 1;
                    }

                    if ($isSame == 0) {
                        $changProducts = $this->_wishlistRepository->create()->loadByCustomerId($customerId, true)->getItemCollection()->addFieldToFilter('wishlist_item_id', $itemId);
                        $changeQty = 0;
                        foreach ($changProducts as $changProduct) {
                            $changeQty = $changProduct['qty'];
                            $changProduct->delete();
                        }

                        foreach ($existProducts as $existProduct) {
                            $qty = $changeQty + $existProductQty;
                            $existProduct->setQty($qty)->save();
                        }
                    }
                    else{
                        $wishlist = $this->_wishlistRepository->create()->loadByCustomerId($customerId, true);
                        $items = $wishlist->getItemCollection()->addFieldToFilter('wishlist_item_id', $itemId);
                        foreach ($items as $item) {
                            $item->setMageWishlistCategory($wishlistChangeId)->save();
                        }
                    }                   
                }
                else{
                    $wishlist = $this->_wishlistRepository->create()->loadByCustomerId($customerId, true);
                    $items = $wishlist->getItemCollection()->addFieldToFilter('wishlist_item_id', $itemId);
                    foreach ($items as $item) {
                        $item->setMageWishlistCategory($wishlistChangeId)->save();
                    }
                }

            $this->_redirect(
                'favorite/index/index',
                array('_secure' => true,
                    'mage_wishlist_id' => $mageWishlistId,
                    'customer_id' =>
                    $customerId
                )
            );
        }
 
        if ($this->request->getParam('wishlistdata') == 'createMageWishlist') {
            $magewishlistName = $this->request->getParam('magewishlist_name');
            $customerId = $this->request->getParam('customer_id');
            $wishlistColl = $this->wishlistColl->create()
                    ->addFieldToFilter('customer_id', array('eq' => $customerId))
                    ->addFieldToFilter('mage_wishlist_name', array('eq' => $magewishlistName));
            if (!$wishlistColl->getSize()) {
                if ($magewishlistName== '') {
                    $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
                    $message = "empty";
                    $resultJson->setData($message);
                    return $resultJson;
                } elseif (strlen($magewishlistName) >= 21) {
                    $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
                    $message = "tooLong";
                    $resultJson->setData($message);
                    return $resultJson;
                } else {
                    $data=array('mage_wishlist_name'=>$magewishlistName,'customer_id'=>$customerId);
                    $wishlistFactory->setData($data)->save();
                    $wishlistColl = $this->wishlistColl->create()
                    ->addFieldToFilter('customer_id', array('eq' => $customerId))
                    ->addFieldToFilter('mage_wishlist_name', array('eq' => $magewishlistName));
                    foreach ($wishlistColl as $coll) {
                        $listId = $coll->getMageWishlistId();
                    }

                    $this->_redirect(
                        'favorite/index/index',
                        array('_secure' => true,
                            'mage_wishlist_id' => $listId,
                            'customer_id' => $customerId
                        )
                    );
                }
            } else {
                $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
                $message = "exist";
                $resultJson->setData($message);
                return $resultJson;
            }
        }
 
        if ($this->request->getParam('wishlistdata') == 'shareMageWishlist') {
            if ($this->request->getParam('shareMageWishlist_email') == '') {
                $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
                $message = "empty";
                $resultJson->setData($message);
                return $resultJson;
            } else {
                $mageWishlistId = $this->request->getParam('mage_wishlist_id');
                $customerId = $this->request->getParam('customer_id');
                $shareEmail = $this->request->getParam('shareMageWishlist_email');
                $customerFactory = $this->customerFactory->create()->load($customerId);
                $customerEmailSender = $customerFactory->getEmail();
                $customerName = $customerFactory->getName();
                $wishlistLink = $this->urlBuilder->getUrl(
                    'favorite/index/index',
                    array('_secure' => true,
                        'mage_wishlist_id' => $mageWishlistId,
                        'customer_id' => $customerId
                    )
                );

                $shareAllEmail = explode(',', $shareEmail);
                $emailLimit = $this->helperData->getEmailLimit();
                if (count($shareAllEmail) > $emailLimit) {
                    $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
                    $message = array('message'=>'limit','data'=>$emailLimit);
                    $resultJson->setData($message);
                    return $resultJson;
                } else {
                    foreach ($shareAllEmail as $allEmail) {
                        $email = trim($allEmail);
                        $data = preg_replace('/\\\\/', '', $email);
                        $data = htmlspecialchars($data);
                        // check if e-mail address is well-formed
                        if (!filter_var($data, FILTER_VALIDATE_EMAIL)) {
                            $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
                            $message = "invalid";
                            $resultJson->setData($message);
                            return $resultJson;
                        } else {
                            $this->helperData->shareWishlist($customerName, $allEmail, $wishlistLink);
                            $this->_redirect(
                                'favorite/index/index',
                                array('_secure' => true,
                                    'mage_wishlist_id' => $mageWishlistId,
                                    'customer_id' => $customerId
                                )
                            );
                        }
                    }
                }
            }
        }

        if ($this->request->getParam('wishlistdata') == 'remove_magewishlist') {
            $customerId = $this->request->getParam('customer_id');
            $mageWishlistId = $this->request->getParam('mage_wishlist_id');

            $wish = $this->wishlist->loadByCustomerId($customerId);
            $items = $wish->getItemCollection()->addFieldToFilter('mage_wishlist_category', $mageWishlistId);
            foreach ($items as $item) {
                $item->delete();
                $wish->save();
            }
            $wishlistToRemove = $wishlistColl->addFieldToFilter('customer_id', array('eq' => $customerId))
                                               ->addFieldToFilter('mage_wishlist_id', array('eq' => $mageWishlistId));
            $wishlistToRemove->walk("delete");
             $_types = [
                    'full_page'
                    ];
         
            foreach ($_types as $type) {
                $this->cacheTypeList->cleanType($type);
            }
            $this->_redirect(
                'favorite/index/index',
                array('_secure' => true,
                    'mage_wishlist_id' => $mageWishlistId,
                    'customer_id' => $customerId
                )
            );
        }

        if ($this->request->getParam('wishlistdata') == 'updatewishlist') {
            if ($this->request->getParam('wishlist_input')!= '') {
                $customerId = $this->request->getParam('customer_id');
                $mageWishlistId = $this->request->getParam('mage_wishlist_id');
                $wishlistInput = $this->request->getParam('wishlist_input');
                $dataCheck = $wishlistColl->addFieldToFilter('customer_id', array('eq' => $customerId))
                                          ->addFieldToFilter('mage_wishlist_name', array('eq' => $wishlistInput))
                                          ->addFieldToFilter('mage_wishlist_id', array('nin' => $mageWishlistId));
                if ($dataCheck->getSize()) {
                    $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
                    $message = "exist";
                    $resultJson->setData($message);
                    return $resultJson;
                } else {
                    $wishlistFactory->load($mageWishlistId)->setData('mage_wishlist_name', $wishlistInput)->save();
                    
                     $_types = [
                            'full_page'
                            ];
                 
                    foreach ($_types as $type) {
                        $this->cacheTypeList->cleanType($type);
                    }
                    $this->_redirect(
                        'favorite/index/index',
                        array('_secure' => true,
                            'mage_wishlist_id' => $mageWishlistId,
                            'customer_id' => $customerId
                        )
                    );
                }
            } else {
                $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
                $message = "empty";
                $resultJson->setData($message);
                return $resultJson;
            }
        }
    }
}
