<?php
/**
 * author:ashu
 * Class file_upload
 */
namespace Imglibrary\Status;

//图片默认上传根目录
define('TARGETFOLDER', '/Users/ashu/wwwroot/weixin/public/static/image/');
//默认图片根目录对应站点路径
define('IMG_DOMAIN', 'http://'.$_SERVER['HTTP_HOST'].'/static/image');
//默认图片上传大小限制
define('MAXFILESIZE', 1000000);

class File_upload{

    //上传根目录
    public $targetFolder = TARGETFOLDER;

    //图片根目录对应站点路径
    public $img_domain = IMG_DOMAIN;
    //限制大小
    public $maxFileSize = MAXFILESIZE;

    //put your code here
    function uploadFile($file) {
        if (!empty($file)) {
            $tempFile = $file['tmp_name'];
            $fileTypes = array('jpg', 'jpeg', 'gif', 'png'); // File extensions
            $fileParts = pathinfo($file['name']);
            $ext = strtolower($fileParts['extension']);
            $fileSize = $file["size"];
            if ($fileSize > $this->maxFileSize) {
                $arr = array("msg" => "文件大小超过" . $this->maxFileSize . "B", "status" => false);
                return $arr;
            }
            if (in_array($ext, $fileTypes)) {
                date_default_timezone_set('PRC');
                $subFloder = date('Y/m/d');
                $floder = $this->targetFolder . $subFloder;
                $this->crate_dir($floder);
                $fileName = uniqid() . '.' . $ext;
                $targetFile = $floder . '/' . $fileName;
                $flag = move_uploaded_file($tempFile, $targetFile);
                if ($flag > 0) {
                    $arr = array("msg" => "上传文件成功", "status" => true, "img_url" => $this->img_domain .'/' . $subFloder . '/' . $fileName);
                    return $arr;
                }
                //$arr = array("msg" => "上传文件失败", "status" => false);
                //return $arr;
                return -1;
            } else {
                //$arr = array("msg" => "不允许上传" . $ext . "类型文件", "status" => false);
                //return $arr;
                return -2;
            }
        }
        //$arr = array("msg" => "不存在需要上传的文件", "status" => false);
        //return $arr;
        return -3;
    }

    function uploadBase64($base64_image_content){
        date_default_timezone_set('PRC');
        $fileName=time().rand(100,999);
        //保存base64字符串为图片 匹配出图片的格式
        $subFloder = date('Y/m/d');
        $floder = $this->targetFolder . $subFloder;
        $this->crate_dir($floder);
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
            $type = $result[2];
            $new_file = $floder . "/" . $fileName . "{$type}";
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))){
                $arr = array("msg" => "新文件保存成功", "status" => true, "img_url" => $this->img_domain . $subFloder . '/' . $fileName);
                return $arr;
            }
        }else{
            return -4;
        }
    }

    /**
     * 创建文件夹
     *
     * @param 文件夹完整路径 $path
     * @param 权限模式 $mode
     * @return boolean
     */
    function crate_dir($path, $mode = 0777) {
        $aimUrl = str_replace('', '/', $path);
        $aimDir = '';
        $arr = explode('/', $aimUrl);
        $result = true;
        foreach ($arr as $str) {
            $aimDir .= $str . '/';
            if (!file_exists($aimDir)) {
                $result = mkdir($aimDir, $mode);
            }
        }
        return $result;
    }

    /**
     * 生成缩略图
     * @author yangzhiguo0903@163.com
     * @param string     源图绝对完整地址{带文件名及后缀名}
     * @param string     目标图绝对完整地址{带文件名及后缀名}
     * @param int        缩略图宽{0:此时目标高度不能为0，目标宽度为源图宽*(目标高度/源图高)}
     * @param int        缩略图高{0:此时目标宽度不能为0，目标高度为源图高*(目标宽度/源图宽)}
     * @param int        是否裁切{宽,高必须非0}
     * @param int/float  缩放{0:不缩放, 0<this<1:缩放到相应比例(此时宽高限制和裁切均失效)}
     * @return boolean
     */
    function img2thumb($src_img, $dst_img, $width = 75, $height = 75, $cut = 0, $proportion = 0) {
        if (!is_file($src_img)) {
            return false;
        }
        $dir=pathinfo($dst_img)['dirname'];
        $this->crate_dir($dir);
        $ot =strtolower(pathinfo($dst_img)['extension']);
        $otfunc = 'image' . ($ot == 'jpg' ? 'jpeg' : $ot);
        $srcinfo = getimagesize($src_img);
        $src_w = $srcinfo[0];
        $src_h = $srcinfo[1];
        $type = strtolower(substr(image_type_to_extension($srcinfo[2]), 1));
        $createfun = 'imagecreatefrom' . ($type == 'jpg' ? 'jpeg' : $type);

        $dst_h = $height;
        $dst_w = $width;
        $x = $y = 0;

        /**
         * 缩略图不超过源图尺寸（前提是宽或高只有一个）
         */
        if (($width > $src_w && $height > $src_h) || ($height > $src_h && $width == 0) || ($width > $src_w && $height == 0)) {
            $proportion = 1;
        }
        if ($width > $src_w) {
            $dst_w = $width = $src_w;
        }
        if ($height > $src_h) {
            $dst_h = $height = $src_h;
        }

        if (!$width && !$height && !$proportion) {
            return false;
        }
        if (!$proportion) {
            if ($cut == 0) {
                if ($dst_w && $dst_h) {
                    if ($dst_w / $src_w > $dst_h / $src_h) {
                        $dst_w = $src_w * ($dst_h / $src_h);
                        $x = 0 - ($dst_w - $width) / 2;
                    } else {
                        $dst_h = $src_h * ($dst_w / $src_w);
                        $y = 0 - ($dst_h - $height) / 2;
                    }
                } else if ($dst_w xor $dst_h) {
                    if ($dst_w && !$dst_h) {  //有宽无高
                        $propor = $dst_w / $src_w;
                        $height = $dst_h = $src_h * $propor;
                    } else if (!$dst_w && $dst_h) {  //有高无宽
                        $propor = $dst_h / $src_h;
                        $width = $dst_w = $src_w * $propor;
                    }
                }
            } else {
                if (!$dst_h) {  //裁剪时无高
                    $height = $dst_h = $dst_w;
                }
                if (!$dst_w) {  //裁剪时无宽
                    $width = $dst_w = $dst_h;
                }
                $propor = min(max($dst_w / $src_w, $dst_h / $src_h), 1);
                $dst_w = (int) round($src_w * $propor);
                $dst_h = (int) round($src_h * $propor);
                $x = ($width - $dst_w) / 2;
                $y = ($height - $dst_h) / 2;
            }
        } else {
            $proportion = min($proportion, 1);
            $height = $dst_h = $src_h * $proportion;
            $width = $dst_w = $src_w * $proportion;
        }

        $src = $createfun($src_img);
        $dst = imagecreatetruecolor($width ? $width : $dst_w, $height ? $height : $dst_h);
        $white = imagecolorallocate($dst, 255, 255, 255);
        imagefill($dst, 0, 0, $white);

        if (function_exists('imagecopyresampled')) {
            imagecopyresampled($dst, $src, $x, $y, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
        } else {
            imagecopyresized($dst, $src, $x, $y, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
        }
        $otfunc($dst, $dst_img);
        imagedestroy($dst);
        imagedestroy($src);
        return true;
    }


}
