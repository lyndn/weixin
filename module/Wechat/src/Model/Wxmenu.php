<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/23
 * Time: 15:06
 */
namespace Wechat\Model;
use DomainException;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\StringLength;

class Wxmenu implements InputFilterAwareInterface
{
    public $id;
    public $wxid;
    public $parentId;
    public $title;
    public $keyword;
    public $is_show;
    public $sort;
    public $url;
    public $wxsys;
    public $content;
    public $type;
    public $longitude;
    public $latitude;
    public $nav;
    public $tel;
    public $val;

    // Add this property:
    private $inputFilter;

    public function exchangeArray(array $data)
    {
        $this->id     = !empty($data['id']) ? $data['id'] : null;
        $this->wxid  = !empty($data['wxid']) ? $data['wxid'] : null;
        $this->parentId = !empty($data['parentId']) ? $data['parentId'] : 0;
        $this->title = !empty($data['title']) ? $data['title'] : null;
        $this->keyword = !empty($data['keyword']) ? $data['keyword'] : null;
        $this->is_show = !empty($data['is_show']) ? $data['is_show'] : 0;
        $this->sort = !empty($data['sort']) ? $data['sort'] : 0;
        $this->url = !empty($data['url']) ? $data['url'] : null;
        $this->wxsys = !empty($data['wxsys']) ? $data['wxsys'] : null;
        $this->content = !empty($data['content']) ? $data['content'] : null;
        $this->type = !empty($data['type']) ? $data['type'] : null;
        $this->tel = !empty($data['tel']) ? $data['tel'] : null;
        $this->nav = !empty($data['nav']) ? $data['nav'] : null;
        $this->longitude = !empty($data['longitude']) ? $data['longitude'] : null;
        $this->latitude = !empty($data['latitude']) ? $data['latitude'] : null;
        $this->val = !empty($data['val']) ? $data['val'] : null;
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
            'name' => 'title',
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
                        'max' => 4,
                    ],
                ],
            ],
        ]);
        $this->inputFilter = $inputFilter;
        return $this->inputFilter;
    }
    // Add the following method:
    public function getArrayCopy()
    {
        return [
            'id'        => $this->id,
            'wxid'      => $this->wxid,
            'parentId'  => $this->parentId,
            'title'     => $this->title,
            'keyword'   => $this->keyword,
            'is_show'   => $this->is_show,
            'sort'      => $this->sort,
            'url'       => $this->url,
            'wxsys'     => $this->wxsys,
            'content'   => $this->content,
            'type'      => $this->type
        ];
    }
}