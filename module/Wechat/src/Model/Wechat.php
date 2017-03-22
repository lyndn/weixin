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
use Zend\Filter\ToInt;
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

    // Add this property:
    private $inputFilter;

    public function exchangeArray(array $data)
    {
        $this->id     = !empty($data['id']) ? $data['id'] : null;
        $this->uid  = !empty($data['uid']) ? $data['uid'] : null;
        $this->wxname  = !empty($data['wxname']) ? $data['wxname'] : null;
        $this->wxid  = !empty($data['wxid']) ? $data['wxid'] : null;
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
        $this->addtime  = !empty($data['addtime']) ? $data['addtime'] : null;;
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
            'required' => true,
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
            'required' => true,
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
        $this->inputFilter = $inputFilter;
        return $this->inputFilter;
    }
}