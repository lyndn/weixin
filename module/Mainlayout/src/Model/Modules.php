<?php
/**
 *
 * PHP Version ～7.1
 * @package   Auth.php
 * @author    yanchao <yanchao563@yahoo.com>
 * @time      2017/03/15 21:43
 * @copyright 2017
 * @license   www.guanlunsm.com license
 * @link      yanchao563@yahoo.com
 */
namespace Mainlayout\Model;

use DomainException;


class Modules
{
    public $id;
    public $modulename;
    public $permission;
    public $pid;

    /**
     * @param array $data
     */
    public function exchangeArray(array $data)
    {
        $this->id     = !empty($data['id']) ? $data['id'] : null;
        $this->modulename = !empty($data['modulename']) ? $data['modulename'] : null;
        $this->permission  = !empty($data['permission']) ? $data['permission'] : null;
        $this->pid = !empty($data['pid']) ? $data['pid'] : null;
    }

}