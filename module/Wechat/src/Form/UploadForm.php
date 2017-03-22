<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/21
 * Time: 14:52
 */
namespace Wechat\Form;
use Zend\InputFilter;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Validator\File\Extension;
use Zend\Validator\File\Size;
use Zend\Validator\File\MimeType;

class UploadForm extends Form
{
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
        $this->addElements();
        $this->addInputFilter();
    }

    public function addElements()
    {
        // File Input
        $file = new Element\File('image-file');
        $file->setLabel('Avatar Image Upload')
            ->setAttribute('id', 'image-file');
        $this->add($file);
    }

    public function addInputFilter()
    {
        $inputFilter = new InputFilter\InputFilter();

        // File Input
        //验证文件扩展名
        $extention = new Extension('png,jpeg,jpg,bmp,gif,mp4');
        //验证文件大小
        $size = new Size(array('min'=>'1kB', 'max'=>'10KB'));
        //验证文件MIME类型
        $mime = new MimeType(array('image/png','image/jpeg','image/jpg','image/bmp','image/gif','video/mp4','enableHeaderCheck' => true));
        $fileInput = new InputFilter\FileInput('image-file');
        $fileInput->setRequired(true);
        $fileInput->getFilterChain()->attachByName(
            'filerenameupload',
            array(
                'target'    => './data/upload',
                'randomize' => true,
                "use_upload_name" => true,
            )
        );
        $inputFilter->add($fileInput);

        $this->setInputFilter($inputFilter);
    }
}