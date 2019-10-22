<?php
/**
 * @category Mageants MultipleWishlist
 * @package Mageants_MultipleWishlist
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <info@mageants.com>
 */ 
namespace Mageants\MultipleWishlist\Helper;
use Magento\Framework\App\Helper\AbstractHelper;
class Data extends AbstractHelper
{
    /**
   * @var \Magento\Framework\App\Config\ScopeConfigInterface
   */
   protected $scopeConfig;
   /**
   * @var \Magento\Framework\Mail\Template\TransportBuilder
   */
   private $transportBuilder;
   /**
   * @var \Magento\Framework\Translate\Inline\StateInterface
   */
   /**
   * @var \Magento\Store\Model\StoreManagerInterface
   */
   private $storeManager;
   const XML_PATH_MODULE_ENABLE = 'favorite/general/enable';
   const XML_PATH_SHARE_WISHLIST_TEMPLATE = 'favorite/email/email_template';
   const XML_PATH_WISHLIST_EMAIL_SENDER = 'favorite/email/email_identity';
   const XML_PATH_WISHLIST_EMAIL_LIMIT = 'favorite/email/number_limit';
   const XML_PATH_SHARE_WISHLIST_ENABLE = 'favorite/email/share_enable';
   

   /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig 
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     * @param \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */ 

   public function __construct(
      \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
      \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
      \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
      \Magento\Store\Model\StoreManagerInterface $storeManager
    )
   {
      $this->scopeConfig = $scopeConfig;
      $this->transportBuilder = $transportBuilder;
      $this->inlineTranslation = $inlineTranslation;
      $this->storeManager = $storeManager;
   }
   /**
     * @return bool
     */
  public function getFavoriteEnable() {
     $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
     return $this->scopeConfig->getValue(self::XML_PATH_MODULE_ENABLE, $storeScope);
  }
  public function getShareWishlistEnable() {
     $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
     return $this->scopeConfig->getValue(self::XML_PATH_SHARE_WISHLIST_ENABLE, $storeScope);
  }
  public function getWishlistTemplateId() {

    $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
    return $this->scopeConfig->getValue(self::XML_PATH_SHARE_WISHLIST_TEMPLATE, $storeScope);
     
  }
  public function wishlistEmailSender()
  {
    $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
    return $this->scopeConfig->getValue(self::XML_PATH_WISHLIST_EMAIL_SENDER,$storeScope);
  }
  public function getEmailLimit()
  {
    $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
    return $this->scopeConfig->getValue(self::XML_PATH_WISHLIST_EMAIL_LIMIT,$storeScope);
  }
  public function generateWishlistTemplate($variable, $receiverInfo, $templateId)
  {     
      $this->transportBuilder->setTemplateIdentifier($templateId)
          ->setTemplateOptions(
              [
                  'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                  'store' => $this->storeManager->getStore()->getId(),
              ]
          )
          ->setTemplateVars($variable)
          ->setFrom($this->wishlistEmailSender())
          ->addTo($receiverInfo['email']);

      return $this;
  } 
  public function shareWishlist($customer_name, $shareEmail, $wishlistLink)
  {
      $receiverInfo = [
          'email' => $shareEmail
      ];
      $variable = [];
      $variable['wishlistLink'] = $wishlistLink;
      $variable['customer_name'] = $customer_name;
      $templateId = $this->getWishlistTemplateId();
     
      $this->inlineTranslation->suspend();
      $this->generateWishlistTemplate($variable, $receiverInfo, $templateId);
      $transport = $this->transportBuilder->getTransport();
      $transport->sendMessage();
      $this->inlineTranslation->resume();
      
      return $this;
  }

}
