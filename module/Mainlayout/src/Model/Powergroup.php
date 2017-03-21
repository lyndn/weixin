<?php
/**
 *
 * PHP Version ～7.1
 * @package   Powergroup.php
 * @author    yanchao <yanchao563@yahoo.com>
 * @time      2017/03/21 16:16
 * @copyright 2017
 * @license   www.guanlunsm.com license
 * @link      yanchao563@yahoo.com
 */

namespace Mainlayout\Model;


class Powergroup
{
    public $id;
    public $groupid;
    public $powerid;
    public $powercode;
    /**
     * @param array $data
     */
    public function exchangeArray(array $data)
    {
        $this->id = !empty($data['id']) ? $data['id'] : null;
        $this->groupid = !empty($data['groupid']) ? $data['groupid'] : null;
        $this->powerid  = !empty($data['powerid']) ? $data['powerid'] : null;
        $this->powercode = !empty($data['powercode']) ? $data['powercode'] : null;
    }


}