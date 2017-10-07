<?php

/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 09-12-16
 * Time: 09:46
 */
class Article
{
    private $_id;
    private $_idWP;
    private $_title;
    private $_image;
    private $_category;
    private $_tags;
    private $_posted = 0;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->_title = $title;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->_image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->_image = $image;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->_category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->_category = $category;
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->_tags;
    }

    /**
     * @param mixed $tags
     */
    public function setTags($tags)
    {
        $this->_tags = $tags;
    }

    /**
     * @return mixed
     */
    public function getIdWP()
    {
        return $this->_idWP;
    }

    /**
     * @param mixed $idWP
     */
    public function setIdWP($idWP)
    {
        $this->_idWP = $idWP;
    }

    /**
     * @return int
     */
    public function getPosted()
    {
        return $this->_posted;
    }

    /**
     * @param int $posted
     */
    public function setPosted($posted)
    {
        $this->_posted = $posted;
    }

    public function downloadImage()
    {
        file_put_contents("/images/tempImage", file_get_contents($this->getImage()));
    }


}