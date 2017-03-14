<?php
/**
 *
 * PHP Version ～7.1
 * @package   layoutinterface.php
 * @author    yanchao <yanchao563@yahoo.com>
 * @time      2017/03/14 21:32
 * @copyright 2017
 * @license   www.guanlunsm.com license
 * @link      yanchao563@yahoo.com
 */

namespace Mainlayout\Controller;


interface Mainlayoutinterface
{
    public function contentMacro($contentTemplatePath=false,$loginName=false,$contentFileName=false);
    public function pageMacro();
    public function _init($contentTemplatePath=false,$loginName=false,$contentFileName=false);
    public function indexAction();
    public function testAction();
    public function homeAction();

}