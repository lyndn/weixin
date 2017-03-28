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

class AuthTable
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
            return $this->fetchPaginatedResults($where);
        }

        return $this->tableGateway->select($where);
    }


    /**
     * @return Paginator
     */
    private function fetchPaginatedResults($where = null)
    {
        // Create a new Select object for the table:
        $select = new Select($this->tableGateway->getTable($where));
        if($where){
            $select->where($where);
        }
        $select->order("id desc");
        // Create a new result set based on the Album entity:
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Auth());

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
    public function getAuth($username)
    {
        $rowset = $this->tableGateway->select(['username' => $username]);
        $row = $rowset->current();
        if (! $row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $username
            ));
        }

        return $row;
    }

    /**
     * @param $where
     * @return mixed
     */
    public function getAuthWhere($where = null)
    {
        $rowset = $this->tableGateway->select($where);
        $row = $rowset->current();
        return $row;
    }

    /**
     * @param Auth $auth
     */
    public function saveAuth(Auth $auth)
    {
        $data = [
            'id' => $auth->id,
            'username' => $auth->username,
            'passwd'  => $auth->passwd,
            'realname' => $auth->realname,
            'password_salt' => $auth->password_salt,
            'role' => $auth->role,
            'createdate' => $auth->createdate,
            'active' => $auth->active,
            'pid' => $auth->pid
        ];

        $id = (int) $auth->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        if (! $this->getAuthWhere(['id'=>$id])) {
            throw new RuntimeException(sprintf(
                'Cannot update album with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update(array_filter($data), ['id' => $id]);
    }

    /**
     * @param $id
     */
    public function deleteAlbum($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}