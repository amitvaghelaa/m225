define([
    "jquery",
    'mage/url'
], function($,url) {
    "use strict";

    $.widget('magently.ajax', {
        options: {
            url: url.build('ajaxshoppingcart/cart/couponpost'),
            method: 'post',
            triggerEvent: 'submit'
        },

        _create: function() {
            this._bind();
        },

        _bind: function() {
            var self = this;
            self.element.on(self.options.triggerEvent, function() {
                self._ajaxSubmit();
            });
        },

        _ajaxSubmit: function() {
            var self = this;
            var data=$("#discount-coupon-form").serialize();
            $.ajax({
                url: self.options.url,
                type: self.options.method,
                data:data,
                dataType: 'json',
                beforeSend: function() {
                    $('body').trigger('processStart');
                },
                success: function(res) {
                    if(res.success==0)
                    {
                        $("#block-discount .mage-error").html(res.message);
                        $("#block-discount .mage-error").show();
                    }else{
                        $("#block-discount .mage-success").text(res.message);
                    }
                    $('body').trigger('processStop');
                    setTimeout(self._resetAll(),6000);
                }
            });
        },

        _resetAll: function(){
            setTimeout(function(){
                $("#block-discount .mage-error").fadeOut();
                $('#block-discount .mage-success').fadeOut();
            },3000);
            
             // Removing it as with next form submit you will be adding the div again in your code. 
        },

    });

    return $.magently.ajax;
});