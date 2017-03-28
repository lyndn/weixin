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
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class RoleTable
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
    public function fetchAll($paginated = false,$where = null)
    {
        if ($paginated) {
            return $this->fetchPaginatedResults();
        }

        return $this->tableGateway->select($where);
    }

    /**
     * @return Paginator
     */
    private function fetchPaginatedResults()
    {
        // Create a new Select object for the table:
        $select = new Select($this->tableGateway->getTable());

        // Create a new result set based on the Album entity:
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Role());

        // Create a new pagination adapter object:
        $paginatorAdapter = new DbSelect(
        // our configured select object:
            $select,
            // the adapter to run it against:
            $this->tableGateway->getAdapter(),
            // the result set to hydrate:
            $resultSetPrototype
        );

        $paginator = new Paginator($paginatorAdapter);
        return $paginator;
    }



    /**
     * @param $username
     * @return mixed
     */
    public function getRole($roleid)
    {
        $rowset = $this->tableGateway->select(['username' => $roleid]);
        $row = $rowset->current();
        if (! $row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $roleid
            ));
        }
        return $row;
    }

    /**
     * @param Auth $auth
     */
    public function saveRole(Role $role)
    {
        $data = [
            'roletitle' => $role->roletitle,
            'wechatid'  => $role->wechatid
        ];

        $id = (int) $role->roleid;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        if (! $this->getRole($id)) {
            throw new RuntimeException(sprintf(
                'Cannot update album with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    /**
     * @param $id
     */
    public function deleteRole($id)
    {
        $this->tableGateway->delete(['roleid' => (int) $id]);
    }
}