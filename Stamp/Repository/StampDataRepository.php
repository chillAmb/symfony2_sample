<?php

namespace Plugin\Stamp\Repository;

use Doctrine\ORM\EntityRepository;
use Plugin\Stamp\Entity\StampData;

class StampDataRepository extends EntityRepository
{
    public function findAll()
    {
        try {
            $qb = $this->createQueryBuilder('st')
                ->orderBy('st.rank', 'DESC');
            $info = $qb->getQuery()->getResult();
            return $info;
        } catch (NoResultException $e) {
            return null;
        }
    }

    public function findCurrentId()
    {
        try {
            $qb = $this->createQueryBuilder('st')
                ->orderBy('st.id', 'DESC')
                ->setMaxResults(1);
            $info = $qb->getQuery()->getResult();
            return $info;
        } catch (NoResultException $e) {
            return null;
        }
    }

    /**
     * 検索条件での検索を行う。
     * s
     * @param unknown $searchData
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQueryBuilderBySearchData($searchData)
    {
        $qb = $this->createQueryBuilder('st');

        if (isset($searchData['multi'])) {
            $clean_key_multi = preg_replace('/\s+|[　]+/u', '',$searchData['multi']);
            if (preg_match('/^\d+$/', $clean_key_multi)) {
                $qb->select("st")
                    ->where("st.id like :id")
                    ->setParameter("id", $clean_key_multi);
            } else {
                $qb->select("st")
                    ->where("st.name like :name")
                    ->setParameter("name", '%' .$clean_key_multi. '%');
            }
        }
        // type
        if (!empty($searchData['typeform'])) {
            $qb
               ->andWhere('st.type like :type')
               ->setParameter('type', $searchData['typeform']);
        }
        // create_date
        if (!empty($searchData['create_date_start']) && $searchData['create_date_start']) {
            $date = $searchData['create_date_start']
                ->format('Y-m-d H:i:s');
            $qb
                ->andWhere('st.create_date >= :create_date_start')
                ->setParameter('create_date_start', $date);
        }
        if (!empty($searchData['create_date_end']) && $searchData['create_date_end']) {
            $date = $searchData['create_date_end']
                ->modify('+1 days')
                ->format('Y-m-d H:i:s');
            $qb
                ->andWhere('st.create_date < :create_date_end')
                ->setParameter('create_date_end', $date);
        }
        // update_date
        if (!empty($searchData['update_date_start']) && $searchData['update_date_start']) {
            $date = $searchData['update_date_start']
                ->format('Y-m-d H:i:s');
            $qb
                ->andWhere('st.update_date >= :update_date_start')
                ->setParameter('update_date_start', $date);
        }
        if (!empty($searchData['update_date_end']) && $searchData['update_date_end']) {
            $date = $searchData['update_date_end']
                ->modify('+1 days')
                ->format('Y-m-d H:i:s');
            $qb
                ->andWhere('st.update_date < :update_date_end')
                ->setParameter('update_date_end', $date);
        }

        // Order By
        $qb->addOrderBy('st.rank', 'ASC');
        return $qb;
    }

    public function update(\Plugin\Stamp\Entity\StampData $stamp)
    {
        $em = $this->getEntityManager();
        $em->getConnection()->beginTransaction();
        try {
            $em->persist($stamp);
            $em->flush();

            $em->getConnection()->commit();
        } catch (\Exception $e) {
            $em->getConnection()->rollback();
            throw $e;

            return false;
        }

        return true;

    }

    public function create(\Plugin\Stamp\Entity\StampData $stamp)
    {

        $em = $this->getEntityManager();
        $em->getConnection()->beginTransaction();
        try {
            $em->persist($stamp);
            $em->flush();

            $em->getConnection()->commit();
        } catch (\Exception $e) {
            $em->getConnection()->rollback();
            throw $e;
            return false;
        }

        return true;

    }

    public function save($column_name, $column_type, $csv_id, $column_id = null)
    {
    }
}
