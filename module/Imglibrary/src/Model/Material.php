<?php
/**
 *
 * PHP Version ～7.1
 * @package   Album.php
 * @author    yanchao <yanchao563@yahoo.com>
 * @time      2017/03/12 20:55
 * @copyright 2017
 * @license   www.guanlunsm.com license
 * @link      yanchao563@yahoo.com
 */

namespace Imglibrary\Model;

class Material
{
    public $id;
    public $title;
    public $author;
    public $abstract;
    public $content;
    public $cover;
    public $cntlink;
    public $current_url;
    public $filetype;
    public $filesize;
    public $updatetime;
    public $current_dir_path;
    public $synchronization;
    public $has_file;
    public $active;

    public function exchangeArray(array $data)
    {
        $this->id     = !empty($data['id']) ? $data['id'] : null;
        $this->title  = !empty($data['title']) ? $data['title'] : null;
        $this->author     = !empty($data['author']) ? $data['author'] : null;
        $this->abstract  = !empty($data['abstract']) ? $data['abstract'] : null;
        $this->content     = !empty($data['content']) ? $data['content'] : null;
        $this->cover  = !empty($data['cover']) ? $data['cover'] : null;
        $this->cntlink     = !empty($data['cntlink']) ? $data['cntlink'] : null;
        $this->current_url  = !empty($data['current_url']) ? $data['current_url'] : null;
        $this->filetype     = !empty($data['filetype']) ? $data['filetype'] : null;
        $this->filesize  = !empty($data['filesize']) ? $data['filesize'] : null;
        $this->updatetime     = !empty($data['updatetime']) ? $data['updatetime'] : null;
        $this->current_dir_path  = !empty($data['current_dir_path']) ? $data['current_dir_path'] : null;
        $this->synchronization     = !empty($data['synchronization']) ? $data['synchronization'] : null;
        $this->has_file  = !empty($data['has_file']) ? $data['has_file'] : null;
        $this->active     = !empty($data['active']) ? $data['active'] : null;
    }


}