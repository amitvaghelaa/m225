/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
    'jquery',
    'mage/translate',
    'mage/url',
    "Magento_Customer/js/customer-data",
    "magnific/popup",
    "mage/cookies",
    'jquery/ui'
], function($, $t, urlBuilder, customerData) {
    "use strict";
    var popup_anchor = '';
    var timeout;

    var enable_fly_cart = $.cookie('flying_image');
    var usepopup = 1;
    var popupclose_after = $.cookie('popupclose');
    var use_on_product_view_page = $.cookie('product_view_page');
    var enabledAjex = $.cookie('enabledAjex');
    var isprodutcproduct = $.cookie('isprodutcproduct');
    if (isprodutcproduct == 1 && use_on_product_view_page == 0) {
        usepopup = 0;
    }

    var product = 0;
    $(document).ready(function($){
            $('.buttonContinue').on('click', function () {      
                clearInterval(timeout);
            }); 
            $(".cp-close").click(function(e) {
                clearInterval(timeout);
            });  
    }); 
    $.widget('mage.catalogAddToCart', {

        options: {
            processStart: null,
            processStop: null,
            bindSubmit: true,
            minicartSelector: '[data-block="minicart"]',
            messagesSelector: '[data-placeholder="messages"]',
            productStatusSelector: '.stock.available',
            addToCartButtonSelector: '.action.tocart',
            addToCartButtonDisabledClass: 'disabled',
            addToCartButtonTextWhileAdding: '',
            addToCartButtonTextAdded: '',
            addToCartButtonTextDefault: ''
        },

        _create: function() {          
            clearInterval(timeout);
            popup_anchor = $('.popup-with-form').magnificPopup({
                type: 'inline',
                preloader: true,
                focus: '#name',
                callbacks: {
                    beforeOpen: function() {
                        if($(window).width() < 700) {
                            this.st.focus = false;
                        } else {
                            this.st.focus = '#name';
                        }
                    }
                }
            });
            if (this.options.bindSubmit) {
                this._bindSubmit();
            }
        },

        _bindSubmit: function() {
            var self = this;
            this.element.on('submit', function(e) {
                e.preventDefault();
                self.submitForm($(this));
            });
        },

        isLoaderEnabled: function() {
            return this.options.processStart && this.options.processStop;
        },

        /**
         * Handler for the form 'submit' event
         *
         * @param {Object} form
         */
        submitForm: function (form) {
            var addToCartButton, self = this;

            if (form.has('input[type="file"]').length && form.find('input[type="file"]').val() !== '') {
                self.element.off('submit');
                // disable 'Add to Cart' button
                addToCartButton = $(form).find(this.options.addToCartButtonSelector);
                addToCartButton.prop('disabled', true);
                addToCartButton.addClass(this.options.addToCartButtonDisabledClass);
                form.submit();
            } else {
                self.ajaxSubmit(form);
            }
        },

        ajaxSubmit: function(form) {
            var self = this;
            product = $(form).find('input[name="product"]').val();
            $(self.options.minicartSelector).trigger('contentLoading');
            self.disableAddToCartButton(form);
            

            $.ajax({
                url: form.attr('action'),
                data: form.serialize(),
                type: 'post',
                dataType: 'json',
                beforeSend: function() {
                    if (self.isLoaderEnabled()) {
                        $('body').trigger(self.options.processStart);
                    }
                },

                success: function(res) {  
                    if (self.isLoaderEnabled()) {
                        $('body').trigger(self.options.processStop);
                    }

                    if (res.backUrl) {
                        window.location = res.backUrl;
                        return;
                    }
                    if (res.messages) {
                        $(self.options.messagesSelector).html(res.messages);
                    }
                    if (res.minicart) {
                        $(self.options.minicartSelector).replaceWith(res.minicart);
                        $(self.options.minicartSelector).trigger('contentUpdated');
                    }
                    if (res.product && res.product.statusText) {
                        $(self.options.productStatusSelector)
                            .removeClass('available')
                            .addClass('unavailable')
                            .find('span')
                            .html(res.product.statusText);
                    }
                    self.enableAddToCartButton(form);
                }
            });
        },

        disableAddToCartButton: function(form) {
            var addToCartButtonTextWhileAdding = this.options.addToCartButtonTextWhileAdding || $t('Adding...');
            var addToCartButton = $(form).find(this.options.addToCartButtonSelector);
            addToCartButton.addClass(this.options.addToCartButtonDisabledClass);
            addToCartButton.find('span').text(addToCartButtonTextWhileAdding);
            addToCartButton.attr('title', addToCartButtonTextWhileAdding);
        },

        _start_time(timer,message){
            var $ = (typeof $ !== 'undefined') ? $ : jQuery.noConflict();
            if (timer == 0){
                clearTimeout(timeout);
                $(".mfp-close").click();
                return false;
            }
            timeout = setTimeout(function(){
                timer--;
                $(message+" #Continue").html('Continue ('+timer+' s )');
                _start_time(timer,message);
            }, 1000);
        },

        enableAddToCartButton: function(form) {
            var addToCartButtonTextAdded = this.options.addToCartButtonTextAdded || $t('Added');
            var self = this,
                addToCartButton = $(form).find(this.options.addToCartButtonSelector);

            addToCartButton.find('span').text(addToCartButtonTextAdded);
            addToCartButton.attr('title', addToCartButtonTextAdded);

            setTimeout(function() {
                var addToCartButtonTextDefault = self.options.addToCartButtonTextDefault || $t('Add to Cart');
                addToCartButton.removeClass(self.options.addToCartButtonDisabledClass);
                addToCartButton.find('span').text(addToCartButtonTextDefault);
                addToCartButton.attr('title', addToCartButtonTextDefault);
            }, 1000);
            if (usepopup == 1 && enabledAjex == 1 && enable_fly_cart != 1 && $(form).validation() && $(form).validation('isValid')) { 
                $(".mfp-close").click();            
                $("#cartpro_modal .cpmodal-wrapper").hide();
                var customerUrl= urlBuilder.build("customer/section/load");
                
                $.ajax({
                    url: customerUrl,
                    type: 'get',
                    dataType: 'JSON',
                    success:function(json){                                   
                        popup_anchor.click();                            
                        $(".cpmodal-loading").hide();
                        $(".ajax-count").html(JSON.stringify(json.cart.summary_count));
                        $(".ajax-subtotal").html(JSON.stringify(json.cart.subtotal).slice(1, -1));
                        $("#cartpro_modal").show();
                        var message='';
                        var message=".cpmodal-message-"+product;
                        $(message+" #Continue").html('Continue ('+popupclose_after+' s )');
                        $("#cartpro_modal .cpmodal-wrapper").show();
                        $(".cpmodal-message-"+product).first().show();
                        $("#cartpro_modal .cpmodal-wrapper").show();
                        var timer = popupclose_after;
                        timeout =  setInterval(function(){
                            if (timer <= 0) {
                                $(".mfp-close").click();
                            }
                            $(message+" #Continue").html('Continue ('+(timer = timer-1)+' s )'); 
                        }, 1000);
                        if(!$(".cpmodal-message-"+product)[0]){
                            jQuery(".cpmodal-close.cp-close.mfp-close").trigger("click");
                        }
                        
                        return false;
                    }
                });                        
            } 
            if (enabledAjex == 1 && enable_fly_cart == 1) {
                $("#cartfly").click();
            }
        }
    });

    return $.mage.catalogAddToCart;
});
