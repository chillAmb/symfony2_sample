<?php

namespace Plugin\Portfolio\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Plugin\Portfolio\Entity\PortfolioImageData;

class PortfolioImageDataRepository extends EntityRepository
{

    public function __construct(EntityManager $em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
    }


    public function findAll()
    {
        try {
            $qb = $this->createQueryBuilder('pf')
                ->orderBy('pf.rank', 'DESC');
            $info = $qb->getQuery()->getResult();
            return $info;
        } catch (NoResultException $e) {
            return null;
        }
    }


    public function findByPortfolioid($portfolio_id)
    {
        try {
            $qb = $this->createQueryBuilder('pf')
                ->where('pf.portfolio_id = :portfolio_id')
                ->setParameter('portfolio_id', $portfolio_id)
                ->orderBy('pf.rank', 'DESC');
            $info = $qb->getQuery()->getResult();
            return $info;
        } catch (NoResultException $e) {
            return null;
        }
    }


    public function findCurrentId()
    {
        try {
            $qb = $this->createQueryBuilder('pf')
                ->orderBy('pf.image_id', 'DESC')
                ->setMaxResults(1);
            $info = $qb->getQuery()->getResult();
            return $info;
        } catch (NoResultException $e) {
            return null;
        }
    }


    public function findCurrentRank()
    {
        try {
            $qb = $this->createQueryBuilder('pf')
                ->orderBy('pf.rank', 'DESC')
                ->setMaxResults(1);
            $info = $qb->getQuery()->getResult();
            return $info;
        } catch (NoResultException $e) {
            return null;
        }
    }


    public function findByName($file_name)
    {
        try {
            $qb = $this->createQueryBuilder('pf')
                ->where('pf.file_name = :file_name')
                ->setParameter('file_name', $file_name);
            $info = $qb->getQuery()->getResult();
            return $info;
        } catch (NoResultException $e) {
            return null;
        }
    }


    public function create(\Plugin\Portfolio\Entity\PortfolioImageData $PortfolioImage)
    {
        $pfi = new PortfolioImageData();

        $em = $this->getEntityManager();
        $em->getConnection()->beginTransaction();
        try {
            $em->persist($PortfolioImage);
            $em->flush();
            $em->getConnection()->commit();
        } catch (\Exception $e) {
            $em->getConnection()->rollback();
            throw $e;
            return false;
        }

        return true;
    }


    public function delete(\Plugin\Portfolio\Entity\PortfolioImageData $PortfolioImage)
    {
        $em = $this->getEntityManager();
        $em->getConnection()->beginTransaction();
        try {
            $em->remove($PortfolioImage);
            $em->flush();
            $em->getConnection()->commit();
        } catch (\Exception $e) {
            $em->getConnection()->rollback();
            return false;
        }

        return true;
    }


    public function save($column_name, $column_type, $csv_id, $column_id = null)
    {
    }
}
