<?php
/**
 *
 * PHP Version ～7.1
 * @package   GoodNoon.php
 * @author    yanchao <yanchao563@yahoo.com>
 * @time      2017/03/27 22:54
 * @copyright 2017
 * @license   www.guanlunsm.com license
 * @link      yanchao563@yahoo.com
 */

namespace Blog\Status;


class GoodNoon implements IStatus
{
    public function WriteCode($w)
    {
        // TODO: Implement WriteCode() method.
        if($w->hour<14)
        {
            return Yii::t('yii','Good noon');
        }else{
            $w->SetState(new GoodAfternoon());
            return $w->WriteCode();
        }
    }
}