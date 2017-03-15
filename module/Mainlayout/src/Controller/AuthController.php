<?php
/**
 *
 * PHP Version ～7.1
 * @package   AuthController.php
 * @author    yanchao <yanchao563@yahoo.com>
 * @time      2017/03/15 21:52
 * @copyright 2017
 * @license   www.guanlunsm.com license
 * @link      yanchao563@yahoo.com
 */
namespace Mainlayout\Controller;

use Zend\Mvc\Console\View\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;
use Mainlayout\Model\AuthInterface;
use Mainlayout\Form\loginForm;


class AuthController extends AbstractActionController
{
    public $auth;
    public function __construct(AuthInterface $auth)
    {
        $this->auth = $auth;
    }

    public function indexAction()
    {
        $form = new loginForm();
        $form->get('submit')->setValue('登陆');


        $request = $this->getRequest();

        if (! $request->isPost()) {
            $view = new ViewModel(['form' => $form]);
            return $view;
        }


//        $album = new Album();
//        $form->setInputFilter($album->getInputFilter());
//        $form->setData($request->getPost());
//
//        if (! $form->isValid()) {
//            return ['form' => $form];
//        }
//
//        $album->exchangeArray($form->getData());
//        $this->table->saveAlbum($album);
//        return $this->redirect()->toRoute('album');
    }
}