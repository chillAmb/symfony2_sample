<?php

namespace Plugin\Portfolio\Entity;

use Doctrine\ORM\Mapping as ORM;

class PortfolioImageData extends \Eccube\Entity\AbstractEntity
{
    private $image_id;
    private $portfolio_id;
    private $file_name;
    private $rank;
    private $create_date;


    public function getImageid()
    {
        return $this->image_id;
    }

    public function setImageid($image_id)
    {
        $this->image_id = $image_id;
        return $this;
    }


    public function getPortfolioid()
    {
        return $this->portfolio_id;
    }

    public function setPortfolioid($portfolio_id)
    {
        $this->portfolio_id = $portfolio_id;
        return $this;
    }


    public function getFilename()
    {
        return $this->file_name;
    }

    public function setFilename($file_name)
    {
        $this->file_name = $file_name;
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


    public function getCreateDate()
    {
        return $this->create_date;
    }

    public function setCreateDate($create_date)
    {
        $this->create_date = $create_date;
        return $this;
    }


}