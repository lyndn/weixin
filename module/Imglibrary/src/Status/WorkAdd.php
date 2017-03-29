<?php
/**
 *
 * PHP Version ～7.1
 * @package   WorkAdd.php
 * @author    yanchao <yanchao563@yahoo.com>
 * @time      2017/03/29 11:44
 * @copyright 2017
 * @license   www.guanlunsm.com license
 * @link      yanchao563@yahoo.com
 */

namespace Imglibrary\Status;


class WorkAdd
{
    private $current;
    public $type;

    public function __construct()
    {
        $this->current = new WeichatMsgAdd();
    }
    //设置状态
    public function SetState($s)
    {
        $this->current = $s;
    }

    public function WriteCode()
    {
        return $this->current->WriteCode($this);
    }

}