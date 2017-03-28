<?php
/**
 *
 * PHP Version ～7.1
 * @package   GoodMorning.php
 * @author    yanchao <yanchao563@yahoo.com>
 * @time      2017/03/27 22:52
 * @copyright 2017
 * @license   www.guanlunsm.com license
 * @link      yanchao563@yahoo.com
 */

namespace Imglibrary\Status;


class GoodMorning implements IStatus
{
    public function WriteCode($w)
    {
        // TODO: Implement WriteCode() method.
        if($w->hour<9)
        {
            return Yii::t('yii','Good morning');
        }else{
            $w->SetState(new GoodForenoon());
            return $w->WriteCode();
        }
    }
}