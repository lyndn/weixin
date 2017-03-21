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

    /**
     * MyRole constructor.
     * @param TableGatewayInterface $tableGateway
     * @param ContainerInterface $serviceManager
     */
    public function __construct(TableGatewayInterface $tableGateway,ContainerInterface $serviceManager)
    {
        $this->serviceManager = $serviceManager->get('ServiceManager')->getServiceLocator();
        $this->tableGateway = $tableGateway;
        $auth = new \Zend\Authentication\AuthenticationService();

        $this->group = $auth->getIdentity()->role;
        $this->rbac = new Rbac();
        if(isset($this->group)){
            $this->role = new Role($this->group);
        }
        $this->setRole();
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
    public function isGranted($code = null)
    {
        if($this->role && !$this->rbac->isGranted($this->role,$code))
        {
            return $this->serviceManager->get('ViewHelperManager')
                ->get('inlineScript')->appendScript('alert("您没有这个权限！");history.go(-1);');
        }else{
            return true;
        }
    }
}