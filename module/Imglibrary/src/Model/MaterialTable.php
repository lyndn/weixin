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
use Zend\Db\Sql\Expression;

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


    public function fetchAllThread($where,$order)
    {
        $sqlSelect = $this->tableGateway->getSql()->select();
        $sqlSelect->where($where);
        $sqlSelect->order($order);
        $resultSet = $this->tableGateway->selectWith($sqlSelect);
        return $resultSet;
    }


    /**
     * ajax获取素材
     * @param $pageSize
     * @param $pageIndex
     * @param $materialStatus
     * @param $beginTime
     * @param $endTime
     * @return null
     */
    public function getMaterialList($uid,$pageSize, $pageIndex, $materialStatus, $beginTime, $endTime)
    {
        $sqlSelect = $this->tableGateway->getSql()->select();
        $sqlCntSelect = $this->tableGateway->getSql()->select();
        $sqlCntSelect->columns(['id'=> new Expression('COUNT(1)')]);
        if (trim($materialStatus) != "") {
            $sqlSelect->where(['synchronization'=>$materialStatus]);
            $sqlCntSelect->where(['synchronization'=>$materialStatus]);
        }
        if (trim($beginTime) != "") {
            $sqlSelect->where('updatetime >="'.$beginTime.'"');
            $sqlCntSelect->where('updatetime >="'.$beginTime.'"');
        }
        if (trim($endTime) != "") {
            $sqlSelect->where('updatetime <="'.$endTime.'"');
            $sqlCntSelect->where('updatetime <="'.$endTime.'"');
        }
        $sqlSelect->where(['userid'=>$uid]);
        $sqlCntSelect->where(['userid'=>$uid]);
        if ($pageSize > 0 && $pageIndex > 0) {
            $begin = ($pageIndex - 1) * $pageSize;
            $sqlSelect->order('first DESC');
            $sqlSelect->limit($pageSize);
            $sqlSelect->offset($begin);
            $resultSet = $this->tableGateway->selectWith($sqlSelect);
            $rowCount = $this->tableGateway->selectWith($sqlCntSelect);
            $row = $rowCount->current();
            $rows = [];
            foreach ($resultSet as $v)
            {
                $v->content = htmlspecialchars_decode($v->content);
                $rows[] = $v;
            }
            $resp = array('rowCount' => $row->id, 'rows' => $rows);
            return $resp;
        }
        return null;
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


    public function getMaterialWhere($where)
    {
        $rowset = $this->tableGateway->select($where);
        $row = $rowset->current();
        return $row;
    }



    /**
     * @param Material $material
     */
    public function saveMaterial(Material $material)
    {
        $data = [
            'title'  => $material->title,
            'author' => $material->author,
            'abstract' => $material->digest,
            'content' => $material->content,
            'cover' => $material->cover,
            'cntlink' => $material->cntlink,
            'current_url' => $material->fileid,
            'filetype' => $material->filetype,
            'filesize' => $material->filesize,
            'updatetime' => $material->updatetime,
            'current_dir_path' => $material->current_dir_path,
            'synchronization' => $material->synchronization,
            'active' => $material->active,
            'wechatid' => $material->wechatid,
            'userid' => $material->userid,
            'filename' => $material->filename,
            'first' => $material->first,
            'sourcelink' => $material->sourceurl,
            'tid' => $material->tid
        ];

        $id = (int) $material->id;
        if ($id === 0) {
            return $this->tableGateway->insert($data);
        }

        if (! $this->getMaterial($id)) {
            throw new RuntimeException(sprintf(
                'Cannot update album with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
        return null;
    }



    public function deleteMaterial($where)
    {
        $this->tableGateway->delete($where);
    }
}