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


class WeimobMsg implements IStatus
{
    public function WriteCode($w)
    {
        // TODO: Implement WriteCode() method.
        if($w->type == 'weimobmsg')
        {
            return 'weimobmsg';
        }else{
            $w->SetState(new Image());
            return $w->WriteCode();
        }
    }
}