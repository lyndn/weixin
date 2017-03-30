<?php
/**
 *
 * PHP Version ～7.1
 * @package   MyRole.php
 * @author    yanchao <yanchao563@yahoo.com>
 * @time      2017/03/21 13:30
 * @copyright 2017
 * @license   www.guanlunsm.com license
 * @link      yanchao563@yahoo.com
 */
namespace Mainlayout\Model;
use DomainException;
use Zend\Debug\Debug;
use Zend\Permissions\Rbac\AbstractRole;
use Zend\Permissions\Rbac\Rbac;
use Zend\Permissions\Rbac\Role;
use Zend\Db\TableGateway\TableGatewayInterface;
use Interop\Container\ContainerInterface;


class MyRole extends AbstractRole
{
    private $rbac;
    private $role;
    private $group;
    private $tableGateway;
    public $serviceManager;
    private $user;
    private $service;
    private $obj;

    /**
     * MyRole constructor.
     * @param TableGatewayInterface $tableGateway
     * @param ContainerInterface $serviceManager
     */
    public function __construct(TableGatewayInterface $tableGateway,ContainerInterface $serviceManager)
    {
        $this->serviceManager = $serviceManager->get('ServiceManager')->getServiceLocator();
        $this->tableGateway = $tableGateway;
        $this->group = $this->getGroup();
        $this->rbac = new Rbac();
        if(isset($this->group)){
            $this->role = new Role($this->group);
        }
        $this->setRole();

        $auth = new \Zend\Authentication\AuthenticationService();
        if ($auth->hasIdentity())
        {
            $this->user = $auth->getIdentity();
        }
        $this->service = $serviceManager;
    }
    public function getGroup()
    {
        $auth = new \Zend\Authentication\AuthenticationService();
        if($auth->getIdentity()){
            return $auth->getIdentity()->role;
        }
        return null;
    }
    /**
     * @return null
     */
    public function setRole()
    {
        if($this->group){
            $this->setPermission();
            $this->rbac->addRole($this->role);
        }
        return null;
    }


    /**
     * @return null
     */
    private function setPermission()
    {
        $sqlSelect = $this->tableGateway->getSql()->select();
        $sqlSelect->join('power', 'power.powerid = powergroup.powerid',['powercode'], 'left');
        $sqlSelect->where(['powergroup.groupid'=>$this->group]);
        $resultSet = $this->tableGateway->selectWith($sqlSelect);
        foreach ($resultSet as $row) {
            $this->role->addPermission($row->powercode);
        }
        return null;
    }

    /**
     * @return bool
     */
    private function hasRole()
    {
        if ($this->rbac->hasRole($this->group))
        {
            return true;
        }else{
            return false;
        }
    }

    /**
     * check permission
     * @param null $s
     */
    public function isGranted($code = null,$obj=false,$pField=null,$idField=null,$getId=null)
    {
        if ($obj && $pField && $idField && $getId && !$this->check($obj,$pField,$idField,$getId)){
            return $this->serviceManager->get('ViewHelperManager')
                ->get('inlineScript')->appendScript('alert("您没有这个权限！");history.go(-1);');
        }

        if($this->role && !$this->rbac->isGranted($this->role,$code))
        {
            return $this->serviceManager->get('ViewHelperManager')
                ->get('inlineScript')->appendScript('alert("您没有这个权限！");history.go(-1);');
        }else{
            return true;
        }
    }

    /**
     * 是否为超级管理员
     * @return bool
     */
    private function checkRoot()
    {
        return $this->user->userid == 1 ? TRUE : FALSE;
    }

    /**
     * 对象转换
     * @param $objName
     * @return null
     */
    private function overSetObject($objName)
    {
        //$serviceManager->get(\Mainlayout\Model\RoleTable::class);
        $this->obj = $this->service->get($objName);
        return null;
    }


    /**
     * 检查多级权限
     * @param $pField
     * @param $id
     * @param $getId
     * @return bool
     */
    private function check($obj,$pField,$idField,$getId)
    {
        $this->overSetObject($obj);
        if($pField && !$this->checkRoot()){
            $obj = $this->obj->fetchAll(false);
            $obj2 = $this->obj->fetchAll(false);
            $arr = [];
            foreach ($obj as $row)
            {
                if($row->$pField == $this->user->userid || $row->$idField == $this->user->userid)
                {
                    $arr[] = $row->$idField;
                }
            }
            return in_array($getId,$arr) ? TRUE : FALSE;
        }
        return true;
    }



    /**
     * @return mixed
     */
    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    /**
     * @param $groupid
     * @return mixed
     */
    public function getGroupPermission($groupid)
    {
        $resultSet = $this->tableGateway->select(['groupid' => $groupid]);
        if (! $resultSet) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $groupid
            ));
        }
        return $resultSet;
    }


    /**
     * @param $id
     */
    public function deleteGroupPermission($id)
    {
        $this->tableGateway->delete(['groupid' => (int) $id]);
    }


    /**
     * @param $where
     */
    public function deleteGroupRole($where)
    {
        $this->tableGateway->delete($where);
    }

    /**
     * @param $data
     * @return null
     */
    public function setGroupRole($data)
    {
        foreach ($data['powerid'] as $value)
        {
            $this->tableGateway->insert(['groupid' => $data['groupid'], 'powerid' => $value]);
        }
        return null;
    }
}