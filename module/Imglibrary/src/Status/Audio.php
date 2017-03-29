<?php
/**
 *
 * PHP Version ～7.1
 * @package   Audio.php
 * @author    yanchao <yanchao563@yahoo.com>
 * @time      2017/03/29 11:26
 * @copyright 2017
 * @license   www.guanlunsm.com license
 * @link      yanchao563@yahoo.com
 */

namespace Imglibrary\Status;


class Audio implements IStatus
{
    public function WriteCode($w)
    {
        // TODO: Implement WriteCode() method.
        if($w->type == 'audio')
        {
            return 'audio';
        }else{
            $w->SetState(new Video());
            return $w->WriteCode();
        }
    }
}