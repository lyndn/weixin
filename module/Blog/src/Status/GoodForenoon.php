<?php
/**
 *
 * PHP Version ～7.1
 * @package   GoodForenoon.php
 * @author    yanchao <yanchao563@yahoo.com>
 * @time      2017/03/27 22:53
 * @copyright 2017
 * @license   www.guanlunsm.com license
 * @link      yanchao563@yahoo.com
 */

namespace Blog\Status;


class GoodForenoon implements IStatus
{
    public function WriteCode($w)
    {
        // TODO: Implement WriteCode() method.
        if($w->hour<12)
        {
            return Yii::t('yii','Good forenoon');
        }else{
            $w->SetState(new GoodNoon());
            return $w->WriteCode();
        }
    }
}