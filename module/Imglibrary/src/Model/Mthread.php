<?php
/**
 *
 * PHP Version ～7.1
 * @package   Album.php
 * @author    yanchao <yanchao563@yahoo.com>
 * @time      2017/03/12 20:55
 * @copyright 2017
 * @license   www.guanlunsm.com license
 * @link      yanchao563@yahoo.com
 */

namespace Imglibrary\Model;

class Mthread
{
    public $id;
    public $subject;
    public $insert_id;


    public function exchangeArray(array $data)
    {
        $this->id     = !empty($data['id']) ? $data['id'] : 0;
        $this->subject  = !empty($data['subject']) ? $data['subject'] : null;
    }


}