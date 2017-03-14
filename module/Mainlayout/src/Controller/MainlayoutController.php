<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Mainlayout\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;



class MainlayoutController extends AbstractActionController
{
    private $_tplPath;
    public $loginName;
    public $contentTemplatePath;
    public $contentFileName;

    /**
     * MainlayoutController constructor.
     */
    public function __construct()
    {
        $this->_tplPath = 'Mainlayout/Mainlayout';
    }

    /**
     * content macro use
     * @param bool $contentTemplatePath   '{namespace name}/{module name}/action name}
     * @param bool $loginName
     * @param bool $contentFileName 'your_page'
     * @return ViewModel
     */
    public function contentMacroAction($contentTemplatePath=false,$loginName=false,$contentFileName=false)
    {
        return $this->_init();
    }

    /**
     * page macro use
     * @return ViewModel
     */
    public function pageMacroAction()
    {
        $page = new ViewModel();
        $page->setTemplate($this->_tplPath.'/pageNum');
        $page->setTerminal(true);
        return $page;
    }

    /**
     * init
     * @param bool $contentTemplatePath
     * @param bool $loginName
     * @param bool $contentFileName
     * @return ViewModel
     */
    private function _init($contentTemplatePath=false,$loginName=false,$contentFileName=false)
    {
        list($this->contentTemplatePath,$this->loginName,$this->contentFileName) = [$contentTemplatePath,$loginName,$contentFileName];

        $view = new ViewModel();

        // this is not needed since it matches "module/controller/action"
        $view->setTemplate('Mainlayout/index/index');

        $picBoxView = new ViewModel();
        $picBoxView->setTemplate($this->_tplPath.'/picBox');

        $wrap_left = new ViewModel();
        $wrap_left->setTemplate($this->_tplPath.'/wrap_left');

        $wrap_right = new ViewModel();
        $wrap_right->setTemplate($this->_tplPath.'/wrap_right');

        $menu_list = new ViewModel();
        $menu_list->setTemplate($this->_tplPath.'/menu_list');
        $wrap_left->addChild($menu_list,'menu_list');

        $navi = new ViewModel();
        $navi->setTemplate($this->_tplPath.'/navi');

        $sysLoginUsername = new ViewModel(['loginName'=>$this->loginName]);
        $sysLoginUsername->setTemplate($this->_tplPath.'/sysLoginUsername');

        $this->content = new ViewModel();
        $tplPath = $this->contentTemplatePath ? $this->contentTemplatePath : $this->_tplPath;
        $filePath = !empty($this->contentFileName) ? '/'.$this->contentFileName : '/cnt';
        $this->content->setTemplate($tplPath.$filePath);

        $foooter = new ViewModel();
        $foooter->setTemplate($this->_tplPath.'/footer');

        $wrap_right->addChild($navi,'Navi')
            ->addChild($sysLoginUsername,'sysLoginUsername')
            ->addChild($this->content,'cnt')
            ->addChild($foooter,'footer');

        $view->addChild($picBoxView, 'picBox')
            ->addChild($wrap_left, 'wrap_left')
            ->addChild($wrap_right, 'wrap_right');
        return $view;
    }

    /**
     * index
     */
    public function indexAction()
    {
        return $this->_init();
    }

    /**
     * test
     * @return ViewModel
     */
    public function testAction()
    {
        return $this->_init('Mainlayout/Mainlayout','admasasin','test');
    }

    /**
     * home page
     */
    public function homeAction()
    {
        exit();
    }

}


