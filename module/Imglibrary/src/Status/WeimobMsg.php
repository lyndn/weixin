<?php
/**
 *
 * PHP Version ～7.1
 * @package   GoodMorning.php
 * @author    yanchao <yanchao563@yahoo.com>
 * @time      2017/03/27 22:52
 * @copyright 2017
 * @license   www.guanlunsm.com license
 * @link      yanchao563@yahoo.com
 */

namespace Imglibrary\Status;
use Imglibrary\Model\MaterialTable;
use Zend\Json\Json;

class WeimobMsg implements IStatus
{
    private $msg;
    public function WriteCode($w)
    {
        // TODO: Implement WriteCode() method.
        /**
         * ajax  前端调用素材接口
         *  /material/index/weimobmsg?materialStatus=1&pageNum=20&pageIndex=1&beginTime=2016-01-11&endTime=2016-05-16
         */
        if($w->type == 'weimobmsg')
        {
            //
            //{[{},{},{}],[{},{}],[{},{},{},{}]};

            //图片素材库ajax GET接口
            $pageSize = $w->pageNum;
            $pageIndex = $w->page;
            $materialStatus = $w->materialStatus;

            if (!isset($pageIndex) || !$pageIndex) {
                $this->msg[] = 'page';
            }
            if (!isset($pageSize) || !$pageSize) {
                $this->msg[] = 'pageNum';
            }
            if (!isset($materialStatus) || !$materialStatus) {
                $this->msg[] = 'materialStatus';
            }

            //Search field
            $materialStatus = "";
            if (!empty($w->materialStatus) && $w->materialStatus != 'undefined' && $w->materialStatus != '') {
                $materialStatus = trim($w->materialStatus);
            }
            $beginTime = "";
            if (!empty($w->beginTime) && $w->beginTime != 'undefined' && $w->beginTime != '') {
                $beginTime = $w->beginTime;
            }
            $endTime = "";
            if (!empty($w->endTime) && $w->endTime != 'undefined' && $w->endTime != '') {
                $endTime = $w->endTime;
            }

            if (empty($this->msg)) {
                $materialTable = $w->container->get(MaterialTable::class);
                $arr = $materialTable->getMaterialList($w->user->userid,$pageSize, $pageIndex, $materialStatus, $beginTime, $endTime);
                $cnt = $arr['rowCount'];
                $arr = $arr['rows'];
                $resp = [];
                foreach ($arr as $key=>$value)
                {
                    if($value->first == 2){
                        $z = [];
                        $z[] = $value;
                        foreach ($arr as $k=>$v)
                        {
                            if($value->tid == $v->tid && $v->first != 2)
                            {
                                $z[] = $v;
                            }
                        }
                        $resp[] = $z;
                    }
                }
                $json = Json::encode($resp,true,['silenceCyclicalExceptions' => true]);
                echo $json;
                exit();
            } else {
                $s = null;
                foreach ($this->msg as $key => $value) {
                    $s .= $value . "、";
                }
                echo  "缺少参数：" . $s;
                die;
            }
            return 'weimobmsg';
        }else{
            $w->SetState(new Image());
            return $w->WriteCode();
        }
    }

}