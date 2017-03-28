<?php
/**
 *
 * PHP Version ～7.1
 * @package   Auth.php
 * @author    yanchao <yanchao563@yahoo.com>
 * @time      2017/03/15 21:43
 * @copyright 2017
 * @license   www.guanlunsm.com license
 * @link      yanchao563@yahoo.com
 */
namespace Mainlayout\Model;

use DomainException;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Filter\ToInt;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\StringLength;

class Role implements InputFilterAwareInterface
{
    public $roleid;
    public $roletitle;
    public $wechatid;
    public $rpid;
    

    /**
     * @param array $data
     */
    public function exchangeArray(array $data)
    {
        $this->roleid     = !empty($data['roleid']) ? $data['roleid'] : null;
        $this->roletitle = !empty($data['roletitle']) ? $data['roletitle'] : null;
        $this->wechatid  = !empty($data['wechatid']) ? $data['wechatid'] : null;
        $this->rpid  = !empty($data['rpid']) ? $data['rpid'] : 0;
    }

    /**
     * @param InputFilterInterface $inputFilter
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }

    /**
     * @return InputFilter
     */
    public function getInputFilter()
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }

        $inputFilter = new InputFilter();
        
        $inputFilter->add([
            'name' => 'roletitle',
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
            'name' => 'wechatid',
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