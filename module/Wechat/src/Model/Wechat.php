<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/15
 * Time: 9:26
 */
namespace Wechat\Model;
use DomainException;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\StringLength;

class Wechat implements InputFilterAwareInterface
{
    public $id;
    public $uid;
    public $wxname;
    public $wxid;
    public $weixin;
    public $headerpic;
    public $token;
    public $typeid;
    public $appid;
    public $appsecret;
    public $serverUrl;
    public $EncodeType;
    public $AesEncodingKey;
    public $qrcode;
    public $addtime;
    public $mch_id;
    public $key;
    public $cert_path;
    public $key_path;
    public $operId;

    // Add this property:
    private $inputFilter;

    public function exchangeArray(array $data)
    {
        $this->id     = !empty($data['id']) ? $data['id'] : 0;
        $this->uid  = !empty($data['uid']) ? $data['uid'] : 0;
        $this->operId  = !empty($data['operId']) ? $data['operId'] : 0;
        $this->wxname  = !empty($data['wxname']) ? $data['wxname'] : null;
        $this->wxid  = !empty($data['wxid']) ? $data['wxid'] : 0;
        $this->weixin  = !empty($data['weixin']) ? $data['weixin'] : null;
        $this->headerpic  = !empty($data['headerpic']) ? $data['headerpic'] : null;
        $this->token  = !empty($data['token']) ? $data['token'] : null;
        $this->typeid  = !empty($data['typeid']) ? $data['typeid'] : null;
        $this->appid  = !empty($data['appid']) ? $data['appid'] : null;
        $this->appsecret  = !empty($data['appsecret']) ? $data['appsecret'] : null;
        $this->serverUrl  = !empty($data['serverUrl']) ? $data['serverUrl'] : null;
        $this->EncodeType  = !empty($data['EncodeType']) ? $data['EncodeType'] : null;
        $this->AesEncodingKey  = !empty($data['AesEncodingKey']) ? $data['AesEncodingKey'] : null;
        $this->qrcode  = !empty($data['qrcode']) ? $data['qrcode'] : null;
        $this->addtime  = !empty($data['addtime']) ? $data['addtime'] : null;
        $this->mch_id  = !empty($data['mch_id']) ? $data['mch_id'] : null;
        $this->key  = !empty($data['key']) ? $data['key'] : null;
        $this->cert_path  = !empty($data['cert_path']) ? $data['cert_path'] : null;
        $this->key_path  = !empty($data['key_path']) ? $data['key_path'] : null;
    }

    /* Add the following methods: */

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }

    public function getInputFilter()
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }

        $inputFilter = new InputFilter();
        $inputFilter->add([
            'name' => 'wxname',
            'required' => false,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],
        ]);
        $inputFilter->add([
            'name' => 'wxid',
            'required' => false,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],
        ]);
        $inputFilter->add([
            'name'=>'typeid',
            'required'=>false
        ]);
        $inputFilter->add([
            'name'=>'EncodeType',
            'required'=>false
        ]);
        $this->inputFilter = $inputFilter;
        return $this->inputFilter;
    }
    // Add the following method:
    public function getArrayCopy()
    {
        return [
            'id'        => $this->id,
            'wxname'    => $this->wxname,
            'wxid'      => $this->wxid,
            'weixin'    => $this->weixin,
            'headerpic' => $this->headerpic,
            'token'     => $this->token,
            'typeid'    => $this->typeid,
            'appid'     => $this->appid,
            'appsecret' => $this->appsecret,
            'qrcode'    => $this->qrcode,
            'EncodeType'=> $this->EncodeType,
            'AesEncodingKey'=>$this->AesEncodingKey
        ];
    }
    //随机字符
    public function create_noncestr($length=32)
    {
        $chars="abcdefghijklmnopqsrtuvwxyzABCDEFGHIJKLMNOPQSRTUVWXYZ0123456789";
        $str='';
        for($i=0;$i<$length;$i++)
        {
            $str.=substr($chars,mt_rand(0,strlen($chars)-1),1);
        }
        return $str;
    }
}