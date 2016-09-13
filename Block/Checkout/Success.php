<?php

namespace qbo\PayPalMX\Block\Checkout;

class Success extends \Magento\Checkout\Block\Onepage\Success
{
    const PAYPAL_LOGO                      = 'https://www.paypalobjects.com/webstatic/mktg/logo-center/logotipo_paypal_pagos_seguros.png';
    const PENDING_PAYMENT_STATUS_CODE      = 'payment_review';
    
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterfaced
     */
    private $_scopeconfig;
    /**
     *
     * @var \Magento\Sales\Model\OrderFactory 
     */
    private $_orderFactory;
    /**
     *
     * @var \Magento\Sales\Model\Order 
     */
    private $_order = false;    
    /**
     *
     * @var \Magento\Sales\Model\Order 
     */
    private $_methods = ['paypal_express','qbo_paypalplusmx'];
    /**
     * Constructor method
     * 
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Sales\Model\OrderFactory $orderFactory
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Sales\Model\Order\Config $orderConfig,
        \Magento\Framework\App\Http\Context $httpContext,
        array $data = [],
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Sales\Model\OrderFactory $orderFactory
    )
    {
        parent::__construct($context, $checkoutSession, $orderConfig, $httpContext, $data);
        $this->_scopeconfig = $scopeConfig;
        $this->_orderFactory = $orderFactory;
    }
    /**
     * Get if method is active
     * 
     * @return bool
     */
    public function getIsMethodActive()
    {
        $this->_initOrder();
        if($this->_order->getPayment() && in_array($this->_order->getPayment()->getMethod(), $this->_methods)) {
            $code = $this->_order->getPayment()->getMethod();
            return $this->getConfigValue("payment/{$code}/active");
        }
        return false;
    }

    /**
     * Load current Order
     * 
     * @return \Magento\Sales\Model\Order
     */
    protected function _initOrder()
    {
        /** @var \Magento\Sales\Model\Order $order */
        $this->_order = $this->_orderFactory->create()->loadByIncrementId($this->getOrderId());
    }
    /**
     * Check if order has pending payment status
     * 
     * @return boolean
     */
    public function isPaymentPending()
    {
        if($this->_order->getStatus() == self::PENDING_PAYMENT_STATUS_CODE){
            return true;
        }
        return false;
    }
    /**
     * Get if payment has pending status
     * 
     * @return string
     */
    public function getPendingMessage()
    {
        if($this->isPaymentPending()) {
            $code = $this->_order->getPayment()->getMethod();
            return $this->getConfigValue("payment/{$code}/pending_payment_message");
        }
        return '';
    }
    /**
     * Get Paypal logo for success page
     * 
     * @return srtring
     */
    public function getPayPalLogo()
    {
        $this->_initOrder();
        if(!$this->_order->getPayment()){
            return;
        }
        return self::PAYPAL_LOGO;
    }
   /**
     * Get payment store config
     * 
     * @return string
     */
    public function getConfigValue($configPath)
    {
        $value =  $this->_scopeConfig->getValue(
            $configPath,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        ); 
        return $value;
    }
}
