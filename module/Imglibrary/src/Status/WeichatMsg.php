<?php
/**
 *
 * PHP Version ～7.1
 * @package   EarlyMorning.php
 * @author    yanchao <yanchao563@yahoo.com>
 * @time      2017/03/27 22:50
 * @copyright 2017
 * @license   www.guanlunsm.com license
 * @link      yanchao563@yahoo.com
 */

namespace Imglibrary\Status;
use Imglibrary\Model\MaterialTable;

class WeichatMsg implements IStatus
{
    public function WriteCode($w)
    {
        // TODO: Implement WriteCode() method.
        if($w->type == 'weichatmsg')
        {
            $user = new \Zend\Authentication\AuthenticationService();
            if ($user->hasIdentity())
            {
                $user = $user->getIdentity();
            }
            $material = $w->container->get(MaterialTable::class);
            $materialData = $material->fetchAll(true,['wechatid'=>$user->wechatID,'userid'=>$user->userid]);
            $materialData->setCurrentPageNumber($w->page);
            $materialData->setItemCountPerPage($w->pageNum);
            return ['materialData'=>$materialData];
        }else{
            $w->SetState(new WeimobMsg());
            return $w->WriteCode();
        }
    }
}