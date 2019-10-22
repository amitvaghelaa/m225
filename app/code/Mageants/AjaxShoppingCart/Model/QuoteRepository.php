<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Mageants\AjaxShoppingCart\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Model\Quote;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Api\Search\FilterGroup;
use Magento\Quote\Model\ResourceModel\Quote\Collection as QuoteCollection;
use Magento\Quote\Model\ResourceModel\Quote\CollectionFactory as QuoteCollectionFactory;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Quote\Model\QuoteRepository\SaveHandler;
use Magento\Quote\Model\QuoteRepository\LoadHandler;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class QuoteRepository extends \Magento\Quote\Model\QuoteRepository
{
    /**
     * {@inheritdoc}
     */
    public function getActive($cartId, array $sharedStoreIds = [])
    {
        $quote = $this->get($cartId, $sharedStoreIds);/*
        if (!$quote->getIsActive()) {
            throw NoSuchEntityException::singleField('cartId', $cartId);
        }*/
        return $quote;
    }

    /**
     * Load quote with different methods
     *
     * @param string $loadMethod
     * @param string $loadField
     * @param int $identifier
     * @param int[] $sharedStoreIds
     * @throws NoSuchEntityException
     * @return Quote
     */
    protected function loadQuote($loadMethod, $loadField, $identifier, array $sharedStoreIds = [])
    {
        /** @var Quote $quote */
        $quote = $this->quoteFactory->create();
        if ($sharedStoreIds) {
            $quote->setSharedStoreIds($sharedStoreIds);
        }
        $quote->$loadMethod($identifier)->setStoreId($this->storeManager->getStore()->getId());
        if($loadField!=="cartId")
        {
            if (!$quote->getId()) {
                throw NoSuchEntityException::singleField($loadField, $identifier);
            }
        }            
        return $quote;
    }
}
