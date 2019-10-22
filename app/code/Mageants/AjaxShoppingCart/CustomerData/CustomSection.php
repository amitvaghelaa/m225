<?php
namespace Mageants\AjaxShoppingCart\CustomerData		;
use Magento\Customer\CustomerData\SectionSourceInterface;
 
class CustomSection implements SectionSourceInterface
{
	protected $helperData;
	public function __construct(\Mageants\AjaxShoppingCart\Helper\Data $helperData) {
	    $this->helperData = $helperData;
	}
    /**
     * {@inheritdoc}
     */
    public function getSectionData()
    {
    	$flying_image = $this->helperData->getConfigValue('mageants_ajaxshoppingcart/display/flying_image');
    	$popupclose = $this->helperData->getConfigValue('mageants_ajaxshoppingcart/general/popupclose');
        return [
            'flying_image' =>$flying_image,
            'popupclose' =>$popupclose,
        ];
    }
}