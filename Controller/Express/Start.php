<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace qbo\PayPalMX\Controller\Express;
/**
 * Extended Start controller to override PayPal Express Config Type and get custom BN Code
 * @see qbo\PayPalMX\Model\Config
 *
 * @author kasta
 */
class Start extends \Magento\Paypal\Controller\Express\Start {
    /**
     * Config mode type
     *
     * @var string
     */
    protected $_configType = 'qbo\PayPalMX\Model\Config';
}
