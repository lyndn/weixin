<?php
/**
 *
 * PHP Version ～7.1
 * @package   GoodNight.php
 * @author    yanchao <yanchao563@yahoo.com>
 * @time      2017/03/27 22:55
 * @copyright 2017
 * @license   www.guanlunsm.com license
 * @link      yanchao563@yahoo.com
 */

namespace Imglibrary\Status;


class GoodNight implements IStatus
{
    public function WriteCode($w)
    {
        // TODO: Implement WriteCode() method.
        if($w->hour<22)
        {
            return Yii::t('yii','Good night');
        }else{
            $w->SetState(new GoodAtNight());
            return $w->WriteCode();
        }
    }
}