<?php
/**
 *
 * PHP Version ～7.1
 * @package   CurrencyConverter.php
 * @author    yanchao <yanchao563@yahoo.com>
 * @time      2017/03/15 14:46
 * @copyright 2017
 * @license   www.guanlunsm.com license
 * @link      yanchao563@yahoo.com
 */

namespace Mainlayout\Service;


class CurrencyConverter
{
    // Converts euros to US dollars.
    public function convertEURtoUSD($amount)
    {
        return $amount*1.25;
    }
    //...
}