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


class WeichatMsg implements IStatus
{
    public function WriteCode($w)
    {
        // TODO: Implement WriteCode() method.
        if($w->type == 'weichatmsg')
        {
            return 'weichatmsg';
        }else{
            $w->SetState(new WeimobMsg());
            return $w->WriteCode();
        }
    }
}