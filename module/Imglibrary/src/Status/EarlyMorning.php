<?php
/**
 *
 * PHP Version ～7.1
 * @package   EarlyMorning.php
 * @author    yanchao <yanchao563@yahoo.com>
 * @time      2017/03/27 22:50
 * @copyright 2017
 * @license   www.guanlunsm.com license
 * @link      yanchao563@yahoo.com
 */

namespace Imglibrary\Status;


class EarlyMorning implements IStatus
{
    public function WriteCode($w)
    {
        // TODO: Implement WriteCode() method.
        if($w->hour<6)
        {
            return Yii::t('yii','Good Early morning');
        }else{
            $w->SetState(new GoodMorning());
            return $w->WriteCode();  //注意：这里必须都要return返回，否则调用客户端代码的时候无法赋值给$call。
        }
    }
}