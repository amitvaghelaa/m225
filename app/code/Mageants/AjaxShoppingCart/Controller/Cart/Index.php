<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Mageants\AjaxShoppingCart\Controller\Cart;

use Magento\Framework\Controller\ResultFactory;

class Index extends \Magento\Checkout\Controller\Cart\Index
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Magento\Checkout\Model\Cart
     */
    protected $cart;

    /**
     * @var \Mageants\AjaxShoppingCart\Helper\Data
     */
    protected $helperData;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $_url;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     * @param \Magento\Checkout\Model\Cart $cart
     * @param \Mageants\AjaxShoppingCart\Helper\Data $helperData
     * @param \Magento\Framework\UrlInterface $url
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @codeCoverageIgnore
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        \Magento\Checkout\Model\Cart $cart,
        \Mageants\AjaxShoppingCart\Helper\Data $helperData,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct(
            $context,
            $scopeConfig,
            $checkoutSession,
            $storeManager,
            $formKeyValidator,
            $cart,
            $resultPageFactory
        );
        $this->resultPageFactory = $resultPageFactory;
        $this->cart = $cart;
        $this->helperData = $helperData;
        $this->_url = $context->getUrl();
    }

    /**
     * Shopping cart display action
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {        
        $isEnable = $this->helperData->getConfigValue("mageants_ajaxshoppingcart/general/canvas_cart");

        if ($isEnable == 1) {
            $items = $this->cart->getQuote()->getAllVisibleItems();
            if (count($items) < 1) {
                $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
                $resultRedirect->setUrl($this->_redirect->getRefererUrl());
                setcookie("no_items_count", 0, time() + (86400 * 30), "/");
                return $resultRedirect;
            }
            else{
                $backUrl = $this->_url->getUrl('checkout');
                $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
                $resultRedirect->setUrl($backUrl);
                return $resultRedirect;
            }
        }
        else{
            $resultPage = $this->resultPageFactory->create();
            $resultPage->getConfig()->getTitle()->set(__('Shopping Cart'));
            return $resultPage;
        }
    }
}
