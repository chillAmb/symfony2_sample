<?php

namespace Plugin\Portfolio\Entity;

use Doctrine\ORM\Mapping as ORM;

class PortfolioData extends \Eccube\Entity\AbstractEntity
{
    private $id;
    private $order_id;
    private $customer_id;
    private $name;
    private $product_id;
    private $type;
    private $typeform;
    private $img;
    private $page_no;
    private $publish;
    private $comment;
    private $rank;
    private $create_date;
    private $update_date;
    private $del_flg;


    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }


    public function getOrderid()
    {
        return $this->order_id;
    }

    public function setOrderid($order_id)
    {
        $this->order_id = $order_id;
        return $this;
    }


    public function getCustomerid()
    {
        return $this->customer_id;
    }

    public function setCustomerid($customer_id)
    {
        $this->customer_id = $customer_id;
        return $this;
    }


    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }


    public function getProductid()
    {
        return $this->product_id;
    }

    public function setProductid($product_id)
    {
        $this->product_id = $product_id;
        return $this;
    }


    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }


    public function getTypeform()
    {
        return $this->typeform;
    }

    public function setTypeform($typeform)
    {
        $this->typeform = $typeform;
        return $this;
    }


    public function getImg()
    {
        return $this->img;
    }

    public function setImg($img)
    {
        $this->img = $img;
        return $this;
    }


    public function getPageno()
    {
        return $this->page_no;
    }

    public function setPageno($page_no)
    {
        $this->page_no = $page_no;
        return $this;
    }


    public function getPublish()
    {
        return $this->publish;
    }

    public function setPublish($publish)
    {
        $this->publish = $publish;
        return $this;
    }


    public function getComment()
    {
        return $this->comment;
    }

    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }


    public function getRank()
    {
        return $this->rank;
    }

    public function setRank($rank)
    {
        $this->rank = $rank;
        return $this;
    }


    public function setCreateDate($create_date)
    {
        $this->create_date = $create_date;
        return $this;
    }

    public function getCreateDate()
    {
        return $this->create_date;
    }


    public function setUpdateDate($update_date)
    {
        $this->update_date = $update_date;
        return $this;
    }


    public function getUpdateDate()
    {
        return $this->update_date;
    }


    public function getDelFlg()
    {
        return $this->del_flg;
    }


    public function setDelFlg($delflg)
    {
        $this->del_flg = $delflg;
        return $this;
    }


}