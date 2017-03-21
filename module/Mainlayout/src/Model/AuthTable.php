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
    public function fetchAll()
    {
        return $this->tableGateway->select();
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
     * @param Auth $auth
     */
    public function saveAuth(Auth $auth)
    {
        $data = [
            'username' => $auth->username,
            'passwd'  => $auth->passwd
        ];

        $id = (int) $auth->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        if (! $this->getAlbum($id)) {
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