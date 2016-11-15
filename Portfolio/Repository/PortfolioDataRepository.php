<?php

namespace Plugin\Portfolio\Repository;

use Doctrine\ORM\EntityRepository;
use Plugin\Portfolio\Entity\PortfolioData;

class PortfolioDataRepository extends EntityRepository
{
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


    public function findOrder($id)
    {
        try {
            $qb = $this->createQueryBuilder('pf')
                ->andWhere("pf.order_id like :order_id")
                ->setParameter("order_id", $id)
                ->orderBy('pf.id', 'DESC');
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
                ->orderBy('pf.id', 'DESC')
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


    /**
     * 検索条件での検索を行う。
     */
    public function getQueryBuilderBySearchData($searchData)
    {
        $qb = $this->createQueryBuilder('pf')
            ->andWhere('pf.del_flg = 0');

        if (isset($searchData['multi'])) {
            $clean_key_multi = preg_replace('/\s+|[　]+/u', '',$searchData['multi']);
            if (preg_match('/^\d+$/', $clean_key_multi)) {
                $qb->select("pf")
                    ->andWhere("pf.id like :id")
                    ->setParameter("id", $clean_key_multi);
            } else {
                $qb->select("pf")
                    ->andWhere("pf.name like :name")
                    ->setParameter("name", '%' .$clean_key_multi. '%');
            }
        }

        // type
        if (!empty($searchData['typeform'])) {
            $qb
               ->andWhere('pf.type like :type')
               ->setParameter('type', $searchData['typeform']);
        }

        // publish
        if (!empty($searchData['publish'])) {
            $qb
               ->andWhere('pf.publish = :publish')
               ->setParameter('publish', $searchData['publish']);
        }

        // create_date
        if (!empty($searchData['create_date_start']) && $searchData['create_date_start']) {
            $date = $searchData['create_date_start']
                ->format('Y-m-d H:i:s');
            $qb
                ->andWhere('pf.create_date >= :create_date_start')
                ->setParameter('create_date_start', $date);
        }
        if (!empty($searchData['create_date_end']) && $searchData['create_date_end']) {
            $date = $searchData['create_date_end']
                ->modify('+1 days')
                ->format('Y-m-d H:i:s');
            $qb
                ->andWhere('pf.create_date < :create_date_end')
                ->setParameter('create_date_end', $date);
        }

        // update_date
        if (!empty($searchData['update_date_start']) && $searchData['update_date_start']) {
            $date = $searchData['update_date_start']
                ->format('Y-m-d H:i:s');
            $qb
                ->andWhere('pf.update_date >= :update_date_start')
                ->setParameter('update_date_start', $date);
        }
        if (!empty($searchData['update_date_end']) && $searchData['update_date_end']) {
            $date = $searchData['update_date_end']
                ->modify('+1 days')
                ->format('Y-m-d H:i:s');
            $qb
                ->andWhere('pf.update_date < :update_date_end')
                ->setParameter('update_date_end', $date);
        }

        // Order By
        $qb->addOrderBy('pf.rank', 'DESC');

        return $qb;
    }


    public function update(\Plugin\Portfolio\Entity\PortfolioData $Portfolio)
    {
        $em = $this->getEntityManager();
        $em->getConnection()->beginTransaction();
        try {
            $em->persist($Portfolio);
            $em->flush();
            $em->getConnection()->commit();
        } catch (\Exception $e) {
            $em->getConnection()->rollback();
            throw $e;

            return false;
        }

        return true;

    }


    public function create(\Plugin\Portfolio\Entity\PortfolioData $Portfolio)
    {
        $em = $this->getEntityManager();
        $em->getConnection()->beginTransaction();
        try {
            $em->persist($Portfolio);
            $em->flush();
            $em->getConnection()->commit();
        } catch (\Exception $e) {
            $em->getConnection()->rollback();
            throw $e;
            return false;
        }

        return true;

    }


    public function delete(\Plugin\Portfolio\Entity\PortfolioData $Portfolio)
    {
        $em = $this->getEntityManager();
        $em->getConnection()->beginTransaction();
        try {
            $Portfolio->setDelFlg(1);

            $em->persist($Portfolio);
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
