var config = {
    map: {
        '*': {
        	'magnific/popup': 'Mageants_AjaxShoppingCart/js/jquery.magnific-popup',
        	'Magento_Checkout/template/minicart/content.html':'Mageants_AjaxShoppingCart/template/minicart/content.html',
            'Magento_Checkout/template/minicart/item/default.html':'Mageants_AjaxShoppingCart/template/minicart/item/default.html',
            'Magento_Checkout/template/cart/shipping-rates.html':'Mageants_AjaxShoppingCart/template/cart/shipping-rates.html',
            'Magento_Checkout/js/view/minicart':'Mageants_AjaxShoppingCart/js/view/minicart',
            'Magento_Checkout/js/model/cart/totals-processor/default':'Mageants_AjaxShoppingCart/js/model/cart/totals-processor/default',
            'catalogAddToCart':'Mageants_AjaxShoppingCart/js/cart/catalog-add-to-cart',
            'Magento_Checkout/js/model/quote':'Mageants_AjaxShoppingCart/js/model/quote',
            'Magento_Checkout/js/model/url-builder':'Mageants_AjaxShoppingCart/js/model/url-builder',
            'Magento_Checkout/js/model/shipping-rates-validator':'Mageants_AjaxShoppingCart/js/model/shipping-rates-validator',
            'Magento_Checkout/js/model/shipping-rate-processor/new-address':'Mageants_AjaxShoppingCart/js/model/shipping-rate-processor/new-address',
            sidebar:'Mageants_AjaxShoppingCart/js/sidebar',
            magentlyJs: 'Mageants_AjaxShoppingCart/js/magently-js',
        }
  },
  shim: {
        "Mageants_AjaxShoppingCart/js/owl.carousel": ["jquery"],
    }
};
