<?php
/**
 *
 * PHP Version ～7.1
 * @package   AlbumTable.php
 * @author    yanchao <yanchao563@yahoo.com>
 * @time      2017/03/12 21:20
 * @copyright 2017
 * @license   www.guanlunsm.com license
 * @link      yanchao563@yahoo.com
 */

namespace Imglibrary\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class MthreadTable
{
    private $tableGateway;

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
    private function fetchPaginatedResults($where=null)
    {
        // Create a new Select object for the table:
        $select = new Select($this->tableGateway->getTable());
        if($where)
        {
            $select->where($where);
        }
        // Create a new result set based on the Album entity:
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Material());

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
     * @param $id
     * @return mixed
     */
    public function getMthread($id)
    {
        $id = (int) $id;
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
     * @param Material $material
     */
    public function saveMthread(Mthread $mthread)
    {
        $data = [
            'subject'  => $mthread->subject
        ];

        $id = (int) $mthread->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            $mthread->insert_id = $this->tableGateway->lastInsertValue;
            return $mthread->insert_id;
        }

        if (! $this->getMthread($id)) {
            throw new RuntimeException(sprintf(
                'Cannot update album with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
        return null;
    }



    public function deleteMthread($where)
    {
        $this->tableGateway->delete($where);
    }
}