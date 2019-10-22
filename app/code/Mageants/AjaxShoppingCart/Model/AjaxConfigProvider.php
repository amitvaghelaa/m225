<?php
/**
 * @category  Mageants AjaxShoppingCart
 * @package   Mageants_AjaxShoppingCart
 * @copyright Copyright (c) 2019 Magento
 * @author    Mageants Team <support@mageants.com>
 */
namespace Mageants\AjaxShoppingCart\Model;
use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\View\LayoutInterface;
/**
 *  AjaxCart config provider
 */
class AjaxConfigProvider implements ConfigProviderInterface
{
   /** @var LayoutInterface  */
    protected $_layout;

    public function __construct(
        LayoutInterface $layout,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {
        $this->_layout = $layout;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        
        $ajaxConfig = [];
        /*var_dump($this->scopeConfig->getValue('mageants_ajaxshoppingcart/general/canvas_cart', \Magento\Store\Model\ScopeInterface::SCOPE_STORE));
        exit;*/
        $ajaxConfig['canvas_cart']=$this->scopeConfig->getValue('mageants_ajaxshoppingcart/general/canvas_cart', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $ajaxConfig['enable_fly_cart']=$this->scopeConfig->getValue('mageants_ajaxshoppingcart/display/flying_image', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $ajaxConfig['popupclose_after']=$this->scopeConfig->getValue('mageants_ajaxshoppingcart/general/popupclose', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        
        return $ajaxConfig;
    }
}
