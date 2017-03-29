<?php
/**
 *
 * PHP Version ～7.1
 * @package   Work.php
 * @author    yanchao <yanchao563@yahoo.com>
 * @time      2017/03/27 22:48
 * @copyright 2017
 * @license   www.guanlunsm.com license
 * @link      yanchao563@yahoo.com
 */

namespace Imglibrary\Status;


class Work
{
    private $current;
    public $type;

    public function __construct()
    {
        $this->current = new WeichatMsg();
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