<?php

namespace qbo\PayPalMX\Block\Checkout;

class Success extends \Magento\Checkout\Block\Onepage\Success
{
    const SCOPE_STORE = 'store';
    const PAYPAL_LOGO                      = 'https://www.paypalobjects.com/webstatic/mktg/logo-center/logotipo_paypal_pagos_seguros.png';
    const PENDING_PAYMENT_STATUS_CODE      = 'payment_review';
    const XML_PATH_PENDING_MESSAGE = 'payment/express_checkout_other/express_checkout_required/penging_payment_message';
    const XML_PATH_IS_ACTIVE       = 'payment/paypal_express/active';
    
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
    protected $_order;    
    /**
     *
     * @var \Magento\Sales\Model\Order 
     */
    private $_methods = ['paypal_express','express_checkout_other','qbo_paypalplusmx'];
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
        \Magento\Sales\Model\OrderFactory $orderFactory,
        array $data = []
    )
    {
	$this->_scopeconfig = $context->getScopeConfig();
        $this->_orderFactory = $orderFactory;
        $this->_initOrder();
        
        parent::__construct($context, $checkoutSession, $orderConfig, $httpContext, $data);
       
    }
    /**
     * Get if method is active
     * 
     * @return bool
     */
    public function getIsMethodActive()
    {
        if($this->_order->getPayment() && in_array($this->_order->getPayment()->getMethod(), $this->_methods)) {
            $code = $this->_order->getPayment()->getMethod();
            return $this->getConfigValue(self::XML_PATH_IS_ACTIVE);
        }
        return false;
    }

    /**
     * Load current Order
     * 
     * @return \Magento\Sales\Model\Order
     */
    public function _initOrder()
    {
        /** @var \Magento\Sales\Model\Order $order */
        $this->_order = $this->_orderFactory->create()->loadByIncrementId($this->getOrderId());

	return $this->_order;
    }
    /**
     * Check if order has pending payment status
     * 
     * @return boolean
     */
    public function isPaymentPending()
    {
        //$this->_initOrder();

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
            return $this->getConfigValue(self::XML_PATH_PENDING_MESSAGE);
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
        $value = $this->_scopeConfig->getValue(
            $configPath,
            self::SCOPE_STORE
        ); 
        return $value;
    }
}
