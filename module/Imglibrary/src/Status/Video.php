<?php
/**
 *
 * PHP Version ～7.1
 * @package   Video.php
 * @author    yanchao <yanchao563@yahoo.com>
 * @time      2017/03/29 11:27
 * @copyright 2017
 * @license   www.guanlunsm.com license
 * @link      yanchao563@yahoo.com
 */

namespace Imglibrary\Status;


class Video implements IStatus
{
    public function WriteCode($w)
    {
        // TODO: Implement WriteCode() method.
        if($w->type == 'video')
        {
            return 'video';
        }
    }
}