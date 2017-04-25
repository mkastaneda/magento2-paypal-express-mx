/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
define(
    [
        'underscore',
        'jquery',
        'ko',
        'Magento_Checkout/js/model/quote',
        'Magento_Paypal/js/view/payment/method-renderer/paypal-express-abstract',
        'Magento_Checkout/js/action/select-payment-method',
        'Magento_Paypal/js/action/set-payment-method',
        'Magento_Checkout/js/model/payment/additional-validators',
        'Magento_Ui/js/lib/view/utils/dom-observer',
        'paypalInContextExpressCheckout',
        'Magento_Customer/js/customer-data',
        'Magento_Checkout/js/checkout-data'
    ],
    function (
        _,
        $,
        ko,
        quote,
        Component,
        selectPaymentMethodAction,
        setPaymentMethodAction,
        additionalValidators,
        domObserver,
        paypalExpressCheckout,
        customerData,
        checkoutData
    ) {
        'use strict';
        return Component.extend({
            defaults: {
                template: 'qbo_PayPalMX/payment/paypal-express-in-context',
                clientConfig: {
                    /**
                     * @param {Object} event
                     */
                    click: function (event) {
                        event.preventDefault();
                        if (additionalValidators.validate()) {
                            paypalExpressCheckout.checkout.initXO();
                            this.selectPaymentMethod();
                            setPaymentMethodAction(this.messageContainer).done(
                                function () {
                                    $('body').trigger('processStart');
                                    $.get(
                                        this.path,
                                        {
                                            button: 0
                                        }
                                    ).done(
                                        function (response) {
                                            if (response && response.url) {
                                                paypalExpressCheckout.checkout.startFlow(response.url);

                                                return;
                                            }
                                            paypalExpressCheckout.checkout.closeFlow();
                                            window.location.reload();
                                        }
                                    ).fail(
                                        function () {
                                            paypalExpressCheckout.checkout.closeFlow();
                                            window.location.reload();
                                        }
                                    ).always(
                                        function () {
                                            $('body').trigger('processStop');
                                            customerData.invalidate(['cart']);
                                        }
                                    );
                                }.bind(this)
                            );
                        }
                    }
                }
            },
            /**
             * @returns {Object}
             */
            initialize: function () {
                this._super();
                this.initClient();
                return this;
            },
            /**
             * @returns {Object}
             */
            initClient: function () {
                _.each(this.clientConfig, function (fn, name) {
                    if (typeof fn === 'function') {
                        this.clientConfig[name] = fn.bind(this);
                    }
                }, this);
                
                domObserver.get("#" + this.getButtonId(), function () {
                    paypalExpressCheckout.checkout.setup(this.merchantId, this.clientConfig);
                }.bind(this)); 

                return this;
            },
            /**
             * @returns {String}
             */         
            getButtonId: function () {
            	return this.inContextId;
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
            	var classButtonActive = jQuery(".checkRadio:checked").val();
            	if(classButtonActive){
            		$(".buttonRadioActive").appendTo("#"+classButtonActive+"_button");
            		$(".buttonRadioActive").show();
                    $(".buttonRadioActive").prop('disabled', false);
            	}else{
            		$(".buttonRadioActive").hide();
            	    $(".buttonRadioActive").prop('disabled', true);
            	}
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