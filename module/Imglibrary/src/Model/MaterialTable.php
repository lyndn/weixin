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

class MaterialTable
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
     * @param $id
     * @return mixed
     */
    public function getMaterial($id)
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
    public function saveAlbum(Material $material)
    {
        $data = [
            'artist' => $material->artist,
            'title'  => $material->title,
        ];

        $id = (int) $material->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        if (! $this->getMaterial($id)) {
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
    public function deleteAlbum($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}