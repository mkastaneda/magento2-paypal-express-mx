<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Qbo\PayPalMX\Block\Express\InContext\Minicart;

use Magento\Paypal\Block\Express\InContext\Minicart\Button as MinicartButton;

/**
 * Class Button
 */
class Button extends MinicartButton
{
                
    public function getImageUrlButtonMx(){
        return $this->getViewFileUrl('qbo_PayPalMX::img/buttonPpMx.png');
    }
}