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
use Imglibrary\Model\Material;
use Imglibrary\Model\Mthread;
use Imglibrary\Model\MthreadTable;
use Zend\Json\Json;
use Imglibrary\Model\MaterialTable;
use EasyWeChat\Foundation\Application;
use EasyWeChat\Message\Article;

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
           }elseif($w->ac == 'saveToLocal'){
               if (! $w->request->isPost()) {
                   exit();
               }
               if(empty($w->user->wechatID) || empty($w->user->userid)){
                   exit('缺少user参数');
               }

               $JsonData = $w->request->getPost();
               // Decode JSON objects as PHP array
               $phpNative = Json::decode($JsonData['article'], Json::TYPE_ARRAY);
               $tid = $JsonData['newsId'] ? $JsonData['newsId'] : null;

               $mthreadTable = $w->container->get(MthreadTable::class);
               $mthread = new Mthread();

               $data = [];
               $insertData = [];
               foreach ($phpNative as $key => $row)
               {
                   $data['title'] = $row['title'];
                   $data['author'] = $row['author'];
                   $data['abstract'] = $row['digest'];
                   $data['content'] = htmlspecialchars($row['content']);
                   $data['cover'] = 1;
                   if($row['sourceurl']){
                       $data['cntlink'] = 2;
                   }else{
                       $data['cntlink'] = 1;
                   }
                   $data['current_url'] = $row['fileid'];
                   $data['filetype'] = substr(strrchr($row['fileid'], '.'), 1);
                   $data['current_dir_path'] = parse_url($row['fileid'])['path'];
                   $data['filesize'] = filesize(dirname(__FILE__) . '/../../../../public' . parse_url($row['fileid'])['path']);
                   $data['updatetime'] = date('Y-m-d H:i:s');
                   $data['synchronization'] = 1;
                   $data['active'] = 1;
                   $data['wechatid'] = $w->user->wechatID;
                   $data['userid'] = $w->user->userid;
                   $data['filename'] = basename($row['fileid']);
                   if($key > 0)
                   {
                       $data['first'] = 1;
                   }else{
                       if(empty($tid)){
                           $mthread->exchangeArray(['subject' => $row['title']]);
                           $t = $mthreadTable->saveMthread($mthread);
                       }
                       $data['first'] = 2; //one
                       if(!empty($tid)){
                           $mthread->exchangeArray(['id'=>$tid,'subject'=>$row['title']]);
                           $mthreadTable->saveMthread($mthread);
                       }

                   }
                   $data['sourcelink'] = $row['sourceurl'];
                   $data['tid'] = $t ? $t : ($tid ? $tid : 0);
                   $insertData[] = $data;
               }

               $materialTable = $w->container->get(MaterialTable::class);
               $material = new Material();
               $id = null;

               $i = 0;
               foreach ($insertData as $row)
               {
                   if(empty($tid)){
                       $material->exchangeArray($row);
                       $materialTable->saveMaterial($material);
                   }else{
                       if(empty($i)){
                           $materialTable->deleteMaterial(['tid'=>$tid]);
                       }
                       $material->exchangeArray($row);
                       $materialTable->saveMaterial($material);
                       $i = 1;
                   }
               }

               //{"error":0,"message":"\u6210\u529f","newsId":"11555","islogin":1}

               $response = ['error'=>0,'message'=>'成功！','newsId'=>$t,'islogin'=>1];
               $json = Json::encode($response,true);
               echo $json;
               exit();
           }elseif ($w->ac == 'fetchedtor'){
               if (! $w->request->isPost()) {
                   exit();
               }
               $requestData = $w->request->getPost();
               $ids = $requestData['ids'];
               $action = $requestData['action'];
               if($action == 'add'){
                   $materialTable = $w->container->get(MaterialTable::class);
                   $material = new Material();
                   $arr = $materialTable->getMaterial($ids);
                   $res = $materialTable->fetchAllThread(['tid'=>$arr->tid],'first DESC');

                   $v = [];
                   foreach($res as $k)
                   {
                       $v[] = $k;
                   }

                   end($v);
                   $key_last = key($v);
                   $on = null;
                   foreach ($v as $key => $row)
                   {
                       $row->content = htmlspecialchars_decode($row->content);
                       $json = Json::encode($row,true,['silenceCyclicalExceptions' => true]);
                       if($key != $key_last) {
                           $z = ',';
                       }else{
                           $z = '';
                       }
                       $on .= $json.$z;
                   }
                   echo '['.$on.']';
                   exit();
               }else if($action == 'del'){
                   $resp = $status= null;
                   $materialTable = $w->container->get(MaterialTable::class);
                   $material = new Material();
                   $obj = $materialTable->getMaterial($ids);
                   if($obj->first == 2){
                       $arr = $materialTable->getMaterialWhere(['tid'=>$obj->tid,'first'=>1]);
                       if(!empty($arr) && is_object($arr)){
                           $arrRes = $this->std_class_object_to_array($arr);
                           $arrRes['first'] = 2;
                           $arrRes['current_url'] = $arrRes['fileid'];
                           $arrRes['sourcelink'] = $arrRes['sourceurl'];
                           $arrRes['abstract'] = $arrRes['digest'];
                           unset($arrRes['digest']);
                           unset($arrRes['sourceurl']);
                           unset($arrRes['fileid']);
                           $material->exchangeArray($arrRes);
                           $materialTable->saveMaterial($material);
                       }else{
                           $mthreadTable = $w->container->get(MthreadTable::class);
                           $mthreadTable->deleteMthread(['id'=>$obj->tid]);
                           $materialTable->deleteMaterial(['id'=>$ids]);
                           $status = 1;
                       }
                   }elseif($obj->first == 1){
                       $materialTable->deleteMaterial(['id'=>$ids]);
                       $status = 1;
                   }else{
                       $status = 0;
                   }

                   if($status == 1){
                       $resp = ['message'=>'删除成功！'];
                   }else{
                       $resp = ['message'=>'缺少first参数'];
                   }
                   echo Json::encode($resp);
                   exit();
               }elseif($action == 'compound'){
                   $pieces = explode(",", $ids);
                   $materialTable = $w->container->get(MaterialTable::class);
                   end($pieces);
                   $key_last = key($pieces);
                   $on = null;
                   foreach ($pieces as $key => $row)
                   {
                       $line = $materialTable->getMaterial($row);
                       $line->content = htmlspecialchars_decode($line->content);
                       $json = Json::encode($line,true,['silenceCyclicalExceptions' => true]);
                       if($key != $key_last) {
                           $z = ',';
                       }else{
                           $z = '';
                       }
                       $on .= $json.$z;
                   }
                   echo '['.$on.']';
                   exit();
               }
           }elseif($w->ac == 'getweixin'){    //获取微信
               $wxuserTable = $w->container->get(\Wechat\Model\WechatTable::class);
               $resultSet = $wxuserTable->fetchAll(false,['uid'=>$w->user->userid]);
               echo '<div id="appid" style="display: none"></div>';
               echo '<div id="appsecret" style="display: none"></div>';
               foreach ($resultSet as $v){
                   if(empty($v)){
                       echo '<p>暂无任何公众号可选择,请进入右上角:会员中心-公众号管理-授权公众号</p>';
                       exit();
                   }
                   echo '<li data-appid="'.$v->id.'" data-appsecret="'.$this->std_encode($v->appsecret).'"><input type="radio" name="radio-btn" />.'.$v->wxname.'</li>' ;
               }
               exit();
           }elseif($w->ac == 'wxvipmedia'){   //同步素材
               if (! $w->request->isPost()) {
                   exit();
               }
               $JsonData = $w->request->getPost();
               $tid = $JsonData['nid'];
               $appid = $JsonData['appid'];
               $appsecret = $this->std_decode($JsonData['appsecret']);
               $wxuserTable = $w->container->get(\Wechat\Model\WechatTable::class);
               $row = $wxuserTable->getWechat($appid);
               if($row->appsecret == $appsecret)
               {
                   $materialTable = $w->container->get(MaterialTable::class);
                   $resultSet = $materialTable->fetchAllThread(['userid'=>$w->user->userid,'tid'=>$tid],'first DESC');
                   $result = [];
                   foreach ($resultSet as $row){
                       $result[] = $row;
                   }

                   //配置
                   $options = [
                       'debug'  => true,
                       'app_id' => $appid,
                       'secret' => $appsecret,
                       'token'  => $result['token'],
                   ];


                   $app = new Application($options);
                   // 永久素材
                   $material = $app->material;



                   if(count($result) == 1){  //单图文

                       //获取永久素材
                       $resource = $material->get($result[0]['id']);
                       $resource = Json::decode($resource,Json::TYPE_ARRAY);
                       if(empty($resource['news_item']))
                       {
                           // 上传单篇图文
                           $article = new Article([
                               'title' => $result[0]['title'],
                               'thumb_media_id' => $result[0]['id'],
                               'show_cover_pic' => 0,
                               "author"=>$result[0]['author'],
                               'digest'=>$result[0]['digest'],
                               'content'=>$result[0]['content'],
                               'content_source_url'=>$result[0]['sourceurl']
                           ]);
                           $material->uploadArticle($article);
                       }else{
                           $result = $material->updateArticle($result[0]['id'], new Article(
                               [
                                   'title' => $result[0]['title'],
                                   'thumb_media_id' => $result[0]['id'],
                                   'show_cover_pic' => 0,
                                   "author"=>$result[0]['author'],
                                   'digest'=>$result[0]['digest'],
                                   'content'=>$result[0]['content'],
                                   'content_source_url'=>$result[0]['sourceurl']
                               ]
                           ));
                           $mediaId = $result->media_id;
                       }

                   }else{
                       foreach ($result as $value)
                       {
                           $resource = $material->get($value['id']);
                           echo 5;die;
                           $resource = Json::decode($resource,Json::TYPE_ARRAY);
                           if(empty($resource['news_item']))
                           {
                               $numArticle[] = new Article([
                                   'title' => $value['title'],
                                   'thumb_media_id' => $value['id'],
                                   'show_cover_pic' => 0,
                                   "author"=>$value['author'],
                                   'digest'=>$value['digest'],
                                   'content'=>$value['content'],
                                   'content_source_url'=>$value['sourceurl']
                               ]);
                               $material->uploadArticle($numArticle);
                           }else{
                               $material->updateArticle($value['id'], new Article(
                                   [
                                       'title' => $result[0]['title'],
                                       'thumb_media_id' => $result[0]['id'],
                                       'show_cover_pic' => 0,
                                       "author"=>$result[0]['author'],
                                       'digest'=>$result[0]['digest'],
                                       'content'=>$result[0]['content'],
                                       'content_source_url'=>$result[0]['sourceurl']
                                   ]
                               ));
                           }
                       }
                   }
                   exit();
               }else{
                   $resp = ['error'=>1,'msg'=>'appsecret错误!'];
                   $json = Json::encode($resps,true);
                   echo $json;
                   exit();
               }
           }

           return ['user'=>null];
        }else{
            $w->SetState(new WeimobMsgAdd());
            return $w->WriteCode();
        }
    }

    /**
     * [std_class_object_to_array 将对象转成数组]
     * @param [stdclass] $stdclassobject [对象]
     * @return [array] [数组]
     */
    private function std_class_object_to_array($stdclassobject)
    {
        $_array = is_object($stdclassobject) ? get_object_vars($stdclassobject) : $stdclassobject;
        foreach ($_array as $key => $value) {
            $value = (is_array($value) || is_object($value)) ? std_class_object_to_array($value) : $value;
            $array[$key] = $value;
        }
        return $array;
    }

    /**
     * 简单对称加密算法之加密
     * @param String $string 需要加密的字串
     * @param String $skey 加密EKY
     * @return String
     */
    private function std_encode($string = '', $skey = 'yanchao') {
        $strArr = str_split(base64_encode($string));
        $strCount = count($strArr);
        foreach (str_split($skey) as $key => $value)
            $key < $strCount && $strArr[$key].=$value;
        return str_replace(array('=', '+', '/'), array('O0O0O', 'o000o', 'oo00o'), join('', $strArr));
    }
    /**
     * 简单对称加密算法之解密
     * @param String $string 需要解密的字串
     * @param String $skey 解密KEY
     * @return String
     */
    private function std_decode($string = '', $skey = 'yanchao') {
        $strArr = str_split(str_replace(array('O0O0O', 'o000o', 'oo00o'), array('=', '+', '/'), $string), 2);
        $strCount = count($strArr);
        foreach (str_split($skey) as $key => $value)
            $key <= $strCount  && isset($strArr[$key]) && $strArr[$key][1] === $value && $strArr[$key] = $strArr[$key][0];
        return base64_decode(join('', $strArr));
    }

}