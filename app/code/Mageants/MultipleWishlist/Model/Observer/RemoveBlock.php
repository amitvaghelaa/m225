<?php

namespace Mageants\MultipleWishlist\Model\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class RemoveBlock implements ObserverInterface
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    */

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->_scopeConfig = $scopeConfig;
    }
    /**
     * @return \Magento\Framework\View\Layout  
     */
    public function execute(Observer $observer)
    {
        $layout = $observer->getLayout();
        $block = $layout->getBlock('customer-account-navigation-wish-list-link');
        if ($block) {
            $remove = $this->_scopeConfig->getValue(
                'favorite/general/enable',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );

            if ($remove) {
                $layout->unsetElement('customer-account-navigation-wish-list-link');
            }
        }
    }
}