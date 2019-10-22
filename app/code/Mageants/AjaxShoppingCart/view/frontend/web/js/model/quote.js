/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
/**
 * @api
 */
define([
    'ko',
    'jquery',
    'underscore'
], function (ko,$, _) {
    'use strict';
    var quoteDataSet="";var basePriceFormatset="";var priceFormatset=""; var storeCodeset="";
    var totalsDataset="";
    if(window.checkoutConfig!==undefined){
        if(window.checkoutConfig.quoteData !== undefined){
            quoteDataSet= window.checkoutConfig.quoteData;
            basePriceFormatset=window.checkoutConfig.basePriceFormat;
            priceFormatset=window.checkoutConfig.priceFormat;
            storeCodeset=window.checkoutConfig.storeCode
            totalsDataset= window.checkoutConfig.totalsData;
        }
    }
    /**
     * Get totals data from the extension attributes.
     * @param {*} data
     * @returns {*}
     */
    var proceedTotalsData = function (data) {
            if (_.isObject(data) && _.isObject(data['extension_attributes'])) {
                _.each(data['extension_attributes'], function (element, index) {
                    data[index] = element;
                });
            }

            return data;
        },
        /*if(window.checkoutConfig.quoteData !== 'undefine'){window.checkoutConfig.quoteData}else{

        }*/
        billingAddress = ko.observable(null),
        shippingAddress = ko.observable(null),
        shippingMethod = ko.observable(null),
        paymentMethod = ko.observable(null),
        quoteData = quoteDataSet,
        basePriceFormat = basePriceFormatset,
        priceFormat = priceFormatset,
        storeCode = storeCodeset,
        totalsData = proceedTotalsData(totalsDataset),
        totals = ko.observable(totalsData),
        collectedTotals = ko.observable({});

    return {
        totals: totals,
        shippingAddress: shippingAddress,
        shippingMethod: shippingMethod,
        billingAddress: billingAddress,
        paymentMethod: paymentMethod,
        guestEmail: null,

        /**
         * @return {*}
         */
        getQuoteId: function () {
            /*if(quoteData['entity_id']!==""){
                $.cookie("quoteidcookie",quoteData['entity_id']);
            //$.cookie("quoteidcookie",quoteData['entity_id']);
        }*/
            return quoteData['entity_id'];
        },

        /**
         * @return {Boolean}
         */
        isVirtual: function () {
            return !!Number(quoteData['is_virtual']);
        },

        /**
         * @return {*}
         */
        getPriceFormat: function () {
            return priceFormat;
        },

        /**
         * @return {*}
         */
        getBasePriceFormat: function () {
            return basePriceFormat;
        },

        /**
         * @return {*}
         */
        getItems: function () {
            return window.checkoutConfig.quoteItemData;
        },

        /**
         *
         * @return {*}
         */
        getTotals: function () {
            return totals;
        },

        /**
         * @param {Object} data
         */
        setTotals: function (data) {
            data = proceedTotalsData(data);
            totals(data);
            this.setCollectedTotals('subtotal_with_discount', parseFloat(data['subtotal_with_discount']));
        },

        /**
         * @param {*} paymentMethodCode
         */
        setPaymentMethod: function (paymentMethodCode) {
            paymentMethod(paymentMethodCode);
        },

        /**
         * @return {*}
         */
        getPaymentMethod: function () {
            return paymentMethod;
        },

        /**
         * @return {*}
         */
        getStoreCode: function () {
            return storeCode;
        },

        /**
         * @param {String} code
         * @param {*} value
         */
        setCollectedTotals: function (code, value) {
            var colTotals = collectedTotals();

            colTotals[code] = value;
            collectedTotals(colTotals);
        },

        /**
         * @return {Number}
         */
        getCalculatedTotal: function () {
            var total = 0.; //eslint-disable-line no-floating-decimal

            _.each(collectedTotals(), function (value) {
                total += value;
            });

            return total;
        }
    };
});
