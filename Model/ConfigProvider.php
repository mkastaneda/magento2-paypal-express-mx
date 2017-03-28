<?php
namespace qbo\PayPalMX\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\UrlInterface;
use Magento\Payment\Helper\Data as PaymentHelper;


class ConfigProvider implements ConfigProviderInterface
{
	const XML_CONFIG_ENABLE_INSTALLMENT = 'payment/express_checkout_other/express_checkout_required/enable_installment';
	const IFRAME_CONFIG_CODE_NAME      = 'paypalExpress';
	/**
	 * @var Connection
	 */
	protected $_quote;
	protected $_logger;
	protected $_objectManager;
	protected $_localeResolver;
	/**
	 *
	 * @var \Magento\Checkout\Model\Cart
	 */
	protected $_httpConnection;
	
	/**
	 * @param PaymentHelper $paymentHelper
	 * @param UrlInterface $urlBuilder
	 */
	public function __construct(
			\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
			\Magento\Checkout\Model\Cart $cart,
			\Psr\Log\LoggerInterface $logger,
			\Magento\Framework\ObjectManagerInterface $objectManager,
			\Magento\Framework\Locale\ResolverInterface $localeResolver
			) {
				$this->scopeConfig = $scopeConfig;
				$this->_quote = $cart->getQuote();
				$this->_logger = $logger;
				$this->_objectManager = $objectManager;
				$this->_localeResolver = $localeResolver;
	}
	/**
	 * {@inheritdoc}
	 */
	public function getConfig()
	{
		$config['payment'][self::IFRAME_CONFIG_CODE_NAME]['config']['enable_installment']    
		= $this->getStoreConfig(self::XML_CONFIG_ENABLE_INSTALLMENT);
		
		return $config;
	}
	/**
	 * Get payment store config
	 * @return string
	 */
	public function getStoreConfig($configPath)
	{
		$value =  $this->scopeConfig->getValue(
				$configPath,
				\Magento\Store\Model\ScopeInterface::SCOPE_STORE
				);
		return $value;
	}
}
