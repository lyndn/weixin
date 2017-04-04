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
use Zend\Json\Json;




class WeichatMsgAdd implements IStatus
{

    /**
     * @param $w
     * @return array
     */
    public function WriteCode($w)
    {
        // TODO: Implement WriteCode() method.
        if($w->type == 'weichatmsg')
        {
           if($w->ac == 'ajaxuserlogin')
           {
               if (! $w->request->isPost()) {
                   exit();
               }
               $json = Json::encode($w->user,true,['silenceCyclicalExceptions' => true]);
               echo $json;
               exit();
           }elseif ($w->ac == 'coverupload') {
               if (! $w->request->isPost()) {
                   exit();
               }
               $file = NULL;
               $file = (!empty($_FILES['files'])) ? $_FILES['files'] : $file;
               $obj = new File_upload();
               if(!empty($file)){
                   $resp = $obj->uploadFile($file);
                   if($resp['status'])
                   {
                       $response = [
                           'error'=>0,
                           'message'=>"[$resp[msg]]",
                           'url'=>$resp['img_url'],
                           "full_url"=>$resp['img_url']
                       ];
                       $json = Json::encode($response,true);
                       echo $json;
                       exit();
                   }
               }
           }
           return ['user'=>null];
        }else{
            $w->SetState(new WeimobMsgAdd());
            return $w->WriteCode();
        }
    }
}