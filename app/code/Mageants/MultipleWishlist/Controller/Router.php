<?php
/**
 * @category   Mageants Shopbylook
 * @package    Mageants_Shopbylook
 * @copyright  Copyright (c) 2017 Mageants
 * @author     Mageants Team <support@Mageants.com>
 */
namespace Mageants\MultipleWishlist\Controller;

use Magento\Framework\Controller\ResultFactory;

class Router implements \Magento\Framework\App\RouterInterface
{
    protected $actionFactory;
    protected $_response;
    public function __construct(
        \Magento\Framework\App\ActionFactory $actionFactory,
        \Mageants\MultipleWishlist\Helper\Data $wishlistHelper,
        \Magento\Framework\App\ResponseFactory $responseFactory,
        \Magento\Framework\UrlInterface $url,
        \Magento\Framework\App\ResponseInterface $response
    ) {
        $this->actionFactory = $actionFactory;
        $this->wishlistHelper = $wishlistHelper;
        $this->_responseFactory = $responseFactory;
        $this->_url = $url;
        $this->_response = $response;
    }
    public function match(\Magento\Framework\App\RequestInterface $request)
    {
        $isActive = $this->wishlistHelper->getFavoriteEnable();
        $identifier = trim($request->getPathInfo(), '/');
        if ($isActive == 1) {
            if (strpos($identifier, 'wishlist/index/index') !== false || $identifier == "wishlist" || $identifier == "wishlist/index") {
                $customerBeforeAuthUrl = $this->_url->getUrl('favorite/index/index');
                $this->_responseFactory->create()->setRedirect($customerBeforeAuthUrl)->sendResponse();
                return $this;
            } else {
                return false;
            }
        } else {
            if (strpos($identifier, 'favorite/index/index') !== false) {
                $customerBeforeAuthUrl = $this->_url->getUrl('wishlist/index/index');
                $this->_responseFactory->create()->setRedirect($customerBeforeAuthUrl)->sendResponse();
                return $this;
            } else {
                return false;
            }
        }
    }
}
