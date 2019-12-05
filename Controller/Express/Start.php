<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Qbo\PayPalMX\Controller\Express;
use Magento\Framework\App\Action\AbstractAction;
use Magento\Paypal\Controller\Express\AbstractExpress;

/**
 * Extended Start controller to override PayPal Express Config Type and get custom BN Code
 * @see Qbo\PayPalMX\Model\Config
 *
 * @author kasta
 */
class Start extends \Magento\Paypal\Controller\Express\Start {
    /**
     * Config mode type
     *
     * @var string
     */
    protected $_configType = 'Qbo\PayPalMX\Model\Config';

}
