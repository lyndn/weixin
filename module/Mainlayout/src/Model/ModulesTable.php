<?php
/**
 *
 * PHP Version ～7.1
 * @package   AuthTable.php
 * @author    yanchao <yanchao563@yahoo.com>
 * @time      2017/03/16 19:49
 * @copyright 2017
 * @license   www.guanlunsm.com license
 * @link      yanchao563@yahoo.com
 */
namespace Mainlayout\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;

class ModulesTable
{
    private $tableGateway;

    /**
     * AuthTable constructor.
     * @param TableGatewayInterface $tableGateway
     */
    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    /**
     * @return mixed
     */
    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    /**
     * @param $username
     * @return mixed
     */
    public function getRole($id)
    {
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current();
        if (! $row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
            ));
        }
        return $row;
    }

    /**
     * @param $id
     */
    public function deleteModule($where)
    {
        $this->tableGateway->delete($where);
    }



}