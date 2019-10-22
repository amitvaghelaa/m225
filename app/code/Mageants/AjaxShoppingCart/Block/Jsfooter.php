<?php
/**
 * @category  Mageants AjaxShoppingCart
 * @package   Mageants_AjaxShoppingCart
 * @copyright Copyright (c) 2019 Magento
 * @author    Mageants Team <support@mageants.com>
 */

namespace Mageants\AjaxShoppingCart\Block;

use Magento\Framework\View\Element\Template;

class Jsfooter extends \Magento\Framework\View\Element\Template {
	/**
	 * @var string
	 */
	protected $_template = 'jsfooter.phtml';

	/**
	 * Catalog layer
	 *
	 * @var \Magento\Catalog\Model\Layer
	 */
	protected $_catalogLayer;

	/**
	 * @param \Magento\Framework\View\Element\Template\Context $context
	 * @param \Magento\Catalog\Model\Layer\Resolver $layerResolver
	 * @param array $data
	 */
	public function __construct(
		\Magento\Framework\View\Element\Template\Context $context,
		\Magento\Catalog\Model\Layer\Resolver $layerResolver,
		\Magento\Framework\Registry $registry,
		array $data = []
	) {
		$this->_registry = $registry;
		$this->_catalogLayer = $layerResolver->get();
		parent::__construct($context, $data);
	}

	public function getCurrentCategory() {
		/*echo "<pre>";
			        print_r(($this->_registry->registry('current_category')));
		*/
		return $this->_registry->registry('current_category');
	}
}
