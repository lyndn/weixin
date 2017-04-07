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
    public $digest;
    public $content;
    public $cover;
    public $cntlink;
    public $fileid;
    public $filetype;
    public $filesize;
    public $updatetime;
    public $current_dir_path;
    public $synchronization;
    public $active;
    public $wechatid;
    public $userid;
    public $filename;
    public $first;
    public $sourceurl;
    public $tid;

    public function exchangeArray(array $data)
    {
        $this->id     = !empty($data['id']) ? $data['id'] : null;
        $this->title  = !empty($data['title']) ? $data['title'] : null;
        $this->author     = !empty($data['author']) ? $data['author'] : null;
        $this->digest  = !empty($data['abstract']) ? $data['abstract'] : null;
        $this->content     = !empty($data['content']) ? $data['content'] : null;
        $this->cover  = !empty($data['cover']) ? $data['cover'] : null;
        $this->cntlink     = !empty($data['cntlink']) ? $data['cntlink'] : null;
        $this->fileid  = !empty($data['current_url']) ? $data['current_url'] : null;
        $this->filetype     = !empty($data['filetype']) ? $data['filetype'] : null;
        $this->filesize  = !empty($data['filesize']) ? $data['filesize'] : null;
        $this->updatetime     = !empty($data['updatetime']) ? $data['updatetime'] : null;
        $this->current_dir_path  = !empty($data['current_dir_path']) ? $data['current_dir_path'] : null;
        $this->synchronization     = !empty($data['synchronization']) ? $data['synchronization'] : null;
        $this->active     = !empty($data['active']) ? $data['active'] : 1;
        $this->wechatid  = !empty($data['wechatid']) ? $data['wechatid'] : 0;
        $this->userid     = !empty($data['userid']) ? $data['userid'] : 0;
        $this->filename     = !empty($data['filename']) ? $data['filename'] : null;
        $this->first     = !empty($data['first']) ? $data['first'] : 1;
        $this->sourceurl     = !empty($data['sourcelink']) ? $data['sourcelink'] : null;
        $this->tid     = !empty($data['tid']) ? $data['tid'] : 0;
    }


}