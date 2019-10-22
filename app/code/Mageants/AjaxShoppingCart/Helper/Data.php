<?php
/**
 * @category  Mageants AjaxShoppingCart
 * @package   Mageants_AjaxShoppingCart
 * @copyright Copyright (c) 2019 Magento
 * @author    Mageants Team <support@mageants.com>
 */

namespace Mageants\AjaxShoppingCart\Helper;

use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\ObjectManagerInterface;

/**
 * SocialLogin Helper Data class
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{   
   	
   /**
	 * @type array
	 */
	protected $_data = [];

	/**
	 * @type \Magento\Store\Model\StoreManagerInterface
	 */
	protected $storeManager;

	/**
	 * @type \Magento\Framework\ObjectManagerInterface
	 */
	protected $objectManager;
    
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
	protected $scopeConfig;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Customer\Model\SessionFactory $customerSession,
        ObjectManagerInterface $objectManager,
		StoreManagerInterface $storeManager
    ){
        parent::__construct($context);
        $this->customerSession = $customerSession;
        $this->scopeConfig = $context->getscopeConfig();
        $this->objectManager = $objectManager;
		$this->storeManager  = $storeManager;
	}
	
    /** 
     * return customer Login or Not
     */
    public function isLoggedIn()
    {
        return $this->customerSession->create()->isLoggedIn();
    }
    
  	/**
	 * @param $field
	 * @param null $storeId
	 * @return mixed
	 */
	public function getConfigValue($field)
	{

		return $this->scopeConfig->getValue(
			$field,
			\Magento\Store\Model\ScopeInterface::SCOPE_STORE
		);
	}

	public function getBaseUrl()
	{
		return $this->storeManager->getStore()->getBaseUrl();
	}

	public function getCurrentUrl() 
	{
	    return $this->storeManager->getStore()->getCurrentUrl();
	}

	public function getCustomerGroup(){
		return $this->customerSession->create()->getCustomer()->getGroupId();
	}

}
