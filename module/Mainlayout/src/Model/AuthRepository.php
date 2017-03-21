<?php
/**
 *
 * PHP Version ～7.1
 * @package   Auth.php
 * @author    yanchao <yanchao563@yahoo.com>
 * @time      2017/03/15 19:48
 * @copyright 2017
 * @license   www.guanlunsm.com license
 * @link      yanchao563@yahoo.com
 */

namespace Mainlayout\Model;
use Zend\View\Model\ViewModel;
class AuthRepository implements AuthInterface
{
    public function viewLoginForm($form = null)
    {
        // TODO: Implement loginForm() method.
        $view = new ViewModel(['form' => $form]);
        $view->setTerminal(true);
        return $view;
    }
}