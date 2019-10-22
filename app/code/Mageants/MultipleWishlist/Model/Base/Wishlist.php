<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Mageants\MultipleWishlist\Model\Base;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Wishlist\Model\ResourceModel\Item\CollectionFactory;
use Magento\Wishlist\Model\ResourceModel\Wishlist as ResourceWishlist;
use Magento\Wishlist\Model\ResourceModel\Wishlist\Collection;

/**
 * Wishlist model
 *
 * @method int getShared()
 * @method \Magento\Wishlist\Model\Wishlist setShared(int $value)
 * @method string getSharingCode()
 * @method \Magento\Wishlist\Model\Wishlist setSharingCode(string $value)
 * @method string getUpdatedAt()
 * @method \Magento\Wishlist\Model\Wishlist setUpdatedAt(string $value)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.TooManyFields)
 *
 * @api
 * @since 100.0.2
 */
class Wishlist extends \Magento\Wishlist\Model\Wishlist
{
    /**
     * Cache tag
     */
    const CACHE_TAG = 'wishlist';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'wishlist';

    /**
     * Wishlist item collection
     *
     * @var \Magento\Wishlist\Model\ResourceModel\Item\Collection
     */
    protected $_itemCollection;

    /**
     * Store filter for wishlist
     *
     * @var \Magento\Store\Model\Store
     */
    protected $_store;

    /**
     * Shared store ids (website stores)
     *
     * @var array
     */
    protected $_storeIds;

    /**
     * Wishlist data
     *
     * @var \Magento\Wishlist\Helper\Data
     */
    protected $_wishlistData;

    /**
     * Catalog product
     *
     * @var \Magento\Catalog\Helper\Product
     */
    protected $_catalogProduct;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_date;

    /**
     * @var ItemFactory
     */
    protected $_wishlistItemFactory;

    /**
     * @var CollectionFactory
     */
    protected $_wishlistCollectionFactory;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;

    /**
     * @var \Magento\Framework\Math\Random
     */
    protected $mathRandom;

    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    protected $dateTime;

    /**
     * @var bool
     */
    protected $_useCurrentWebsite;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var Json
     */
    private $serializer;

    /**
     * Constructor
     *
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Catalog\Helper\Product $catalogProduct
     * @param \Magento\Wishlist\Helper\Data $wishlistData
     * @param ResourceWishlist $resource
     * @param Collection $resourceCollection
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param ItemFactory $wishlistItemFactory
     * @param CollectionFactory $wishlistCollectionFactory
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Framework\Math\Random $mathRandom
     * @param \Magento\Framework\Stdlib\DateTime $dateTime
     * @param ProductRepositoryInterface $productRepository
     * @param bool $useCurrentWebsite
     * @param array $data
     * @param Json|null $serializer
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Helper\Product $catalogProduct,
        \Magento\Wishlist\Helper\Data $wishlistData,
        ResourceWishlist $resource,
        Collection $resourceCollection,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Wishlist\Model\ItemFactory $wishlistItemFactory,
        CollectionFactory $wishlistCollectionFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Framework\Math\Random $mathRandom,
        \Magento\Framework\Stdlib\DateTime $dateTime,
        ProductRepositoryInterface $productRepository,
        $useCurrentWebsite = true,
        array $data = array(),
        Json $serializer = null
    ) {
        $this->_useCurrentWebsite = $useCurrentWebsite;
        $this->_catalogProduct = $catalogProduct;
        $this->_wishlistData = $wishlistData;
        $this->_storeManager = $storeManager;
        $this->_date = $date;
        $this->_wishlistItemFactory = $wishlistItemFactory;
        $this->_wishlistCollectionFactory = $wishlistCollectionFactory;
        $this->_productFactory = $productFactory;
        $this->mathRandom = $mathRandom;
        $this->request = $request;
        $this->dateTime = $dateTime;
        $this->serializer = $serializer ?: ObjectManager::getInstance()->get(Json::class);
        parent::__construct(
            $context,
            $registry,
            $catalogProduct,
            $wishlistData,
            $resource,
            $resourceCollection,
            $storeManager,
            $date,
            $wishlistItemFactory,
            $wishlistCollectionFactory,
            $productFactory,
            $mathRandom,
            $dateTime,
            $productRepository,
            true,
            $data,
            $serializer
        );
        $this->productRepository = $productRepository;
    }

    /**
     * Add catalog product object data to wishlist
     *
     * @param   \Magento\Catalog\Model\Product $product
     * @param   int $qty
     * @param   bool $forciblySetQty
     *
     * @return  Item
     */
    protected function _addCatalogProduct(\Magento\Catalog\Model\Product $product, $qty = 1, $forciblySetQty = false)
    {        
        $wish_category = $this->request->getParam('mage_wishlist_category');
        if (!$wish_category) {
            if (!$product->getMageWishlistCategory()) {
                $wish_category = 0;
            }
            else{
                $wish_category = $product->getMageWishlistCategory();
            }
        }

        $item = null;
        foreach ($this->getWishItemCollection($wish_category) as $_item) {
            if ($_item->representProduct($product)) {
                $item = $_item;
                break;
            }
        }

        if ($item === null) {
            $storeId = $product->hasWishlistStoreId() ? $product->getWishlistStoreId() : $this->getStore()->getId();
            $item = $this->_wishlistItemFactory->create();
            $item->setProductId($product->getId());
            $item->setWishlistId($this->getId());
            $item->setAddedAt((new \DateTime())->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT));
            $item->setStoreId($storeId);
            $item->setOptions($product->getCustomOptions());
            $item->setProduct($product);
            $item->setMageWishlistCategory($wish_category);
            $item->setQty($qty);
            $item->save();
            if ($item->getId()) {
                $this->getItemCollection()->addItem($item);
            }
        } else {
            $qty = $forciblySetQty ? $qty : $item->getQty() + $qty;
            $item->setQty($qty)->save();
        }

        $this->addItem($item);

        return $item;
    }

    /**
     * Retrieve wishlist item collection
     *
     * @return \Magento\Wishlist\Model\ResourceModel\Item\Collection
     */
    public function getItemCollection()
    {
        if ($this->_itemCollection === null) { 
            $this->_itemCollection = $this->_wishlistCollectionFactory->create()->addWishlistFilter(
                $this
            )->addStoreFilter(
                $this->getSharedStoreIds()
            )->setVisibilityFilter();
        }

        return $this->_itemCollection;
    }

    public function getWishItemCollection($wish_category)
    {
        if ($this->_itemCollection === null) {
            if ($wish_category == 0) {
                $this->_itemCollection = $this->_wishlistCollectionFactory->create()->addFieldToFilter('mage_wishlist_category', array('in' => array(null,0)))->addWishlistFilter(
                    $this
                )->addStoreFilter(
                    $this->getSharedStoreIds()
                )->setVisibilityFilter();
            }
            else{
                $this->_itemCollection = $this->_wishlistCollectionFactory->create()->addFieldToFilter('mage_wishlist_category', $wish_category)->addWishlistFilter(
                    $this
                )->addStoreFilter(
                    $this->getSharedStoreIds()
                )->setVisibilityFilter();
            }            
        }

        return $this->_itemCollection;
    }


    /**
     * Adds new product to wishlist.
     *
     * Returns new item or string on error.
     *
     * @param int|\Magento\Catalog\Model\Product $product
     * @param \Magento\Framework\DataObject|array|string|null $buyRequest
     * @param bool $forciblySetQty
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return Item|string
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function addNewItem($product, $buyRequest = null, $forciblySetQty = false)
    {
        if (is_object($product)) {
            $wish_category = $product->getMageWishlistCategory();
        }
        else{
            $wish_category = 0;
        }
        
        /*
         * Always load product, to ensure:
         * a) we have new instance and do not interfere with other products in wishlist
         * b) product has full set of attributes
         */
        if ($product instanceof \Magento\Catalog\Model\Product) {
            $productId = $product->getId();
            // Maybe force some store by wishlist internal properties
            $storeId = $product->hasWishlistStoreId() ? $product->getWishlistStoreId() : $product->getStoreId();
        } else {
            $productId = (int)$product;
            if (isset($buyRequest) && $buyRequest->getStoreId()) {
                $storeId = $buyRequest->getStoreId();
            } else {
                $storeId = $this->_storeManager->getStore()->getId();
            }
        }

        try {
            $product = $this->productRepository->getById($productId, false, $storeId);
        } catch (NoSuchEntityException $e) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Cannot specify product.'));
        }

        if ($buyRequest instanceof \Magento\Framework\DataObject) {
            $_buyRequest = $buyRequest;
        } elseif (is_string($buyRequest)) {
            $isInvalidItemConfiguration = false;
            try {
                $buyRequestData = $this->serializer->unserialize($buyRequest);
                if (!is_array($buyRequestData)) {
                    $isInvalidItemConfiguration = true;
                }
            } catch (\InvalidArgumentException $exception) {
                $isInvalidItemConfiguration = true;
            }
            if ($isInvalidItemConfiguration) {
                throw new \InvalidArgumentException('Invalid wishlist item configuration.');
            }
            $_buyRequest = new \Magento\Framework\DataObject($buyRequestData);
        } elseif (is_array($buyRequest)) {
            $_buyRequest = new \Magento\Framework\DataObject($buyRequest);
        } else {
            $_buyRequest = new \Magento\Framework\DataObject();
        }

        /* @var $product \Magento\Catalog\Model\Product */
        $cartCandidates = $product->getTypeInstance()->processConfiguration($_buyRequest, clone $product);

        /**
         * Error message
         */
        if (is_string($cartCandidates)) {
            return $cartCandidates;
        }

        /**
         * If prepare process return one object
         */
        if (!is_array($cartCandidates)) {
            $cartCandidates = [$cartCandidates];
        }

        $errors = [];
        $items = [];

        foreach ($cartCandidates as $candidate) {
            if ($candidate->getParentProductId()) {
                continue;
            }
            $candidate->setWishlistStoreId($storeId);
            $candidate->setMageWishlistCategory($wish_category);

            $qty = $candidate->getQty() ? $candidate->getQty() : 1;
            // No null values as qty. Convert zero to 1.
            $item = $this->_addCatalogProduct($candidate, $qty, $forciblySetQty);
            $items[] = $item;

            // Collect errors instead of throwing first one
            if ($item->getHasError()) {
                $errors[] = $item->getMessage();
            }
        }

        $this->_eventManager->dispatch('wishlist_product_add_after', ['items' => $items]);

        return $item;
    }


    /**
     * Update wishlist Item and set data from request
     *
     * The $params sets how current item configuration must be taken into account and additional options.
     * It's passed to \Magento\Catalog\Helper\Product->addParamsToBuyRequest() to compose resulting buyRequest.
     *
     * Basically it can hold
     * - 'current_config', \Magento\Framework\DataObject or array - current buyRequest
     *   that configures product in this item, used to restore currently attached files
     * - 'files_prefix': string[a-z0-9_] - prefix that was added at frontend to names of file options (file inputs),
     * so they won't intersect with other submitted options
     *
     * For more options see \Magento\Catalog\Helper\Product->addParamsToBuyRequest()
     *
     * @param int|Item $itemId
     * @param \Magento\Framework\DataObject $buyRequest
     * @param null|array|\Magento\Framework\DataObject $params
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     *
     * @see \Magento\Catalog\Helper\Product::addParamsToBuyRequest()
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function updateItem($itemId, $buyRequest, $params = null)
    {
        $item = null;
        if ($itemId instanceof Item) {
            $item = $itemId;
            $itemId = $item->getId();
        } else {
            $item = $this->getItem((int)$itemId);
        }
        if (!$item) {
            throw new \Magento\Framework\Exception\LocalizedException(__('We can\'t specify a wish list item.'));
        }

        $product = $item->getProduct();
        $productId = $product->getId();
        if ($productId) {
            if (!$params) {
                $params = new \Magento\Framework\DataObject();
            } elseif (is_array($params)) {
                $params = new \Magento\Framework\DataObject($params);
            }
            $params->setCurrentConfig($item->getBuyRequest());
            $buyRequest = $this->_catalogProduct->addParamsToBuyRequest($buyRequest, $params);

            $product->setWishlistStoreId($item->getStoreId());
            $items = $this->getItemCollection();
            $isForceSetQuantity = true;
            foreach ($items as $_item) {
                /* @var $_item Item */
                if ($_item->getProductId() == $product->getId() && $_item->representProduct(
                    $product
                ) && $_item->getId() != $item->getId()
                ) {
                    // We do not add new wishlist item, but updating the existing one
                    $isForceSetQuantity = false;
                }
            }
            $product->setMageWishlistCategory($item->getMageWishlistCategory());
            $resultItem = $this->addNewItem($product, $buyRequest, $isForceSetQuantity);
            /**
             * Error message
             */
            if (is_string($resultItem)) {
                throw new \Magento\Framework\Exception\LocalizedException(__($resultItem));
            }

            if ($resultItem->getId() != $itemId) {
                if ($resultItem->getDescription() != $item->getDescription()) {
                    $resultItem->setDescription($item->getDescription())->save();
                }
                $item->isDeleted(true);
                $this->setDataChanges(true);
            } else {
                $resultItem->setQty($buyRequest->getQty() * 1);
                $resultItem->setOrigData('qty', 0);
            }
        } else {
            throw new \Magento\Framework\Exception\LocalizedException(__('The product does not exist.'));
        }
        return $this;
    }
}
