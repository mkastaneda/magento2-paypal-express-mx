/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
/*browser:true*/
/*global define*/
define(
    [
        'ko',
        'Magento_Checkout/js/model/quote',
        'Magento_Paypal/js/view/payment/method-renderer/paypal-express-abstract',
        'Magento_Checkout/js/action/select-payment-method',
        'Magento_Checkout/js/checkout-data'
    ],
    function (ko,quote,Component,selectPaymentMethodAction,checkoutData) {
        'use strict';   
        // data = {};
        return Component.extend({
            defaults: {
                template: 'qbo_PayPalMX/payment/paypal-express'
            },
            id: null,

            /** Returns payment acceptance mark image path */
            getPaymentAcceptanceCcSrc: function() {
                return "https://www.paypalobjects.com/webstatic/es_MX/mktg/logos-buttons/redesign/TDC_btn_1.png";
            },

            /** Returns payment acceptance mark image path */
            getPaymentAcceptanceDcSrc: function() {
                return "https://www.paypalobjects.com/digitalassets/c/website/marketing/latam/mx/logos-buttons/tarjetas-debito-2-1.png";
            },

            /** Returns payment acceptance mark image path */
            getPaymentAcceptanceSrc: function() {
                return "https://www.paypalobjects.com/webstatic/es_MX/mktg/logos-buttons/redesign/btn_msi_1.png";
            },
            getCode: function (id) {
                return this.item.method +"_" + id;
            },
            isChecked: ko.computed(function () {
                return quote.paymentMethod() ? quote.paymentMethod().method : null;
            }),
            getIsInstallmentEnabled: function(){
            	var enable = window.checkoutConfig.payment.paypalExpress.config.enable_installment;
            	return enable == 0 ? false : true;
            },
            selectPaymentMethodExpress: function() {
                var data = this.getData();
                data.method = data.method + "_Express";
                selectPaymentMethodAction(data);
                checkoutData.setSelectedPaymentMethod(this.item.method);
                return true;
            },
            selectPaymentMethodCarts: function() {
                var data = this.getData();
                data.method = data.method + "_Cards";
                selectPaymentMethodAction(data);
                checkoutData.setSelectedPaymentMethod(this.item.method);
                return true;
            },
            selectPaymentMethodInstallments: function() {
                var data = this.getData();
                data.method = data.method + "_Installments";
                selectPaymentMethodAction(data);
                checkoutData.setSelectedPaymentMethod(this.item.method);
                return true;
            }


        });
    }
);
