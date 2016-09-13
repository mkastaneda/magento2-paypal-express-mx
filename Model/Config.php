<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace qbo\PayPalMX\Model;

/**
 * Config model that is aware of all \Magento\Paypal payment methods
 * Added custom BN code support por PayPal EC (MX Edition)
 */
class Config extends \Magento\Paypal\Model\Config
{
    private static $bnCodeMx = 'ECMgt20_SI_Custom_%s';
    /**
     * BN code getter
     *
     * @return string
     */
    public function getBuildNotationCode()
    {
        return sprintf(self::$bnCodeMx, $this->getProductMetadata()->getEdition());
    }
}
