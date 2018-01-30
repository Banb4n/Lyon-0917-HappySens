<?php

namespace AppBundle\Repository;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{
    const ADMIN_COMPANY = 2;
    /**
     * @param $companyId
     * @return mixed
     */
    public function getProjectsInCompany($companyId) {
        $qb = $this
            ->createQueryBuilder('u')
            ->join('u.authorProject', 'p')
            ->setParameter('idCompany', $companyId)
            ->where('u.company=:idCompany')
            ->getQuery();
        return $qb->getResult();
    }

    /**
     * Return user by type (salary or happyCoach)
     * @return mixed
     */
    public function getUserByType($status)
    {
        $qb = $this
            ->createQueryBuilder('u');
        if ($status == 'salary') {
            $qb = $qb->where('u.status = 2 or u.status = 3')
                ->orderBy('c.name', 'DESC')
                ->join('u.company', 'c');
        } else if ($status == 'happyCoach') {
            $qb = $qb->where('u.status = 4');
        }
        $qb = $qb->getQuery();
        return $qb->getResult();
    }

    /**
     * Return count number of user by Role
     * @return mixed
     */
    public function getNumberUserByRole() {
        $qb = $this
            ->createQueryBuilder('u')
            ->select('COUNT(u) as nbUser', 'u.status', 'u.isActive')
            ->groupBy('u.status')
            ->addGroupBy('u.isActive')
            ->getQuery();
        return $qb->getResult();
    }

    /**
     * @param $email
     * @return array
     */
    public function findByEmail($email) {
        $qb = $this
            ->createQueryBuilder('u')
            ->setParameter('email', $email)
            ->where('u.email=:email')
            ->getQuery();
        return $qb->getResult();
    }

    /**
     * @param $id
     * @return array
     */
    public function findById($id) {
        $qb = $this
            ->createQueryBuilder('u')
            ->setParameter('id', $id)
            ->where('u.id=:id')
            ->getQuery();
        return $qb->getResult();
    }

    /**
     * @param $idComp
     * @return array
     */
    public function findByIdComp($idComp) {
        $qb = $this
            ->createQueryBuilder('u')
            ->setParameter('id', $idComp)
            ->where('u.company=:id')
            ->getQuery();
        return $qb->getResult();
    }

    /**
     * @return mixed
     */
    public function findAdminComp() {
        $qb = $this
            ->createQueryBuilder('u')
            ->setParameter('id', self::ADMIN_COMPANY)
            ->select('u.email as email')
            ->where('u.id=:id')
            ->getQuery();
        return $qb->getSingleResult();
    }

    public function getSlugIsUnique($text) {
        $text = $text . '%';
        $qb = $this
            ->createQueryBuilder('u')
            ->setParameter('slug', $text)
            ->select('count(u)')
            ->where('u.slug LIKE :slug')
            ->getQuery();
        return $qb->getSingleScalarResult();
    }
}
