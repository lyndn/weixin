<?php
/**
 *
 * PHP Version ～7.1
 * @package   PostRepositoryInterface.php
 * @author    yanchao <yanchao563@yahoo.com>
 * @time      2017/03/15 15:56
 * @copyright 2017
 * @license   www.guanlunsm.com license
 * @link      yanchao563@yahoo.com
 */
namespace Mainlayout\Model;

interface PostRepositoryInterface
{
    /**
     * Return a set of all blog posts that we can iterate over.
     *
     * Each entry should be a Post instance.
     *
     * @return Post[]
     */
    public function findAllPosts();

    /**
     * Return a single blog post.
     *
     * @param  int $id Identifier of the post to return.
     * @return Post
     */
    public function findPost($id);
}