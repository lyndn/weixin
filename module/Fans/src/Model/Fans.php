<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/28
 * Time: 17:03
 */
namespace Fans\Model;

use DomainException;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\StringLength;

class Fans implements InputFilterAwareInterface
{
    public $id;
    public $tab_name;
    public $uid;
    public $subscribe;
    public $openid;
    public $nickname;
    public $sex;
    public $language;
    public $city;
    public $province;
    public $country;
    public $headimgurl;
    public $subscribe_time;
    public $unionid;
    public $remark;
    public $groupid;
    public $tagid_list;
    public $app_id;
    public $groupname;
    // Add this property:
    private $inputFilter;

    public function exchangeArray(array $data)
    {
        $this->id     = !empty($data['id']) ? $data['id'] : 0;
        $this->tab_name = !empty($data['tab_name'])?$data['tab_name']:null;
        $this->uid      = !empty($data['uid']) ? $data['uid'] : 0;
        $this->subscribe = !empty($data['subscribe'])?$data['subscribe']:0;
        $this->openid = !empty($data['openid'])?$data['openid']:null;
        $this->nickname = !empty($data['nickname'])?$data['nickname']:null;
        $this->sex = !empty($data['sex'])?$data['sex']:0;
        $this->language = !empty($data['language'])?$data['language']:null;
        $this->city = !empty($data['city'])?$data['city']:null;
        $this->province = !empty($data['province'])?$data['province']:null;
        $this->country = !empty($data['country'])?$data['country']:null;
        $this->headimgurl = !empty($data['headimgurl'])?$data['headimgurl']:null;
        $this->subscribe_time = !empty($data['subscribe_time'])?$data['subscribe_time']:null;
        $this->unionid = !empty($data['unionid'])?$data['unionid']:null;
        $this->remark = !empty($data['remark'])?$data['remark']:null;
        $this->groupid = !empty($data['groupid'])?$data['groupid']:0;
        $this->tagid_list = !empty($data['tagid_list'])?$data['tagid_list']:null;
        $this->app_id = !empty($data['app_id'])?$data['app_id']:null;
        $this->groupname = !empty($data['groupname'])?$data['groupname']:null;
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
        $this->inputFilter = $inputFilter;
        return $this->inputFilter;
    }
    // Add the following method:
    public function getArrayCopy()
    {
        return [
            'id'        => $this->id,
        ];
    }
}