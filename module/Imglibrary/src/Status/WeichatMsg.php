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
            $materialData = null;
            $user = new \Zend\Authentication\AuthenticationService();
            if ($user->hasIdentity())
            {
                $user = $user->getIdentity();
            }
            if($w->ac == 'search'){
                $word = (!empty($w->keyword_py)) ? trim($w->keyword_py) : $w->keyword_py;
                $material = $w->container->get(MaterialTable::class);
                $materialData = $material->fetchAll(true,['wechatid'=>$user->wechatID,'userid'=>$user->userid,'active'=>1,'title like "%'.$word.'%" OR content like "%'.$word.'%" ']);
                $materialData->setCurrentPageNumber($w->page);
                $materialData->setItemCountPerPage($w->pageNum);

            }else{
                $material = $w->container->get(MaterialTable::class);
                $materialData = $material->fetchAll(true,['wechatid'=>$user->wechatID,'userid'=>$user->userid,'active'=>1]);
                $materialData->setCurrentPageNumber($w->page);
                $materialData->setItemCountPerPage($w->pageNum);
            }
            return ['materialData'=>$materialData];
        }else{
            $w->SetState(new WeimobMsg());
            return $w->WriteCode();
        }
    }
}