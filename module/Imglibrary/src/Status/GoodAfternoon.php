<?php
/**
 *
 * PHP Version ～7.1
 * @package   GoodAfternoon.php
 * @author    yanchao <yanchao563@yahoo.com>
 * @time      2017/03/27 22:54
 * @copyright 2017
 * @license   www.guanlunsm.com license
 * @link      yanchao563@yahoo.com
 */

namespace Imglibrary\Status;


class GoodAfternoon implements IStatus
{
    public function WriteCode($w)
    {
        // TODO: Implement WriteCode() method.
        if($w->hour<17)
        {
            return Yii::t('yii','Good afternoon');
        }else{
            $w->SetState(new GoodDusk());
            return $w->WriteCode();
        }
    }
}