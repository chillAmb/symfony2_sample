<?php

namespace Plugin\Stamp\Entity;

use Doctrine\ORM\Mapping as ORM;

class StampData extends \Eccube\Entity\AbstractEntity
{
    private $id;
    private $name;
    private $type;
    private $typeform;
    private $publish;
    private $img;
    private $rank;
    private $create_date;
    private $update_date;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
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

    public function getRank()
    {
        return $this->rank;
    }

    public function setRank($rank)
    {
        $this->rank = $rank;
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

    public function getPublish()
    {
        return $this->publish;
    }

    public function setPublish($publish)
    {
        $this->publish = $publish;
        return $this;
    }

    public function setCreateDate($createDate)
    {
        $this->create_date = $createDate;
        return $this;
    }

    public function getCreateDate()
    {
        return $this->create_date;
    }

    public function setUpdateDate($updateDate)
    {
        $this->update_date = $updateDate;
        return $this;
    }

    public function getUpdateDate()
    {
        return $this->update_date;
    }


}