<?php

namespace App\Repository;

use App\Entity\Courses;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Courses>
 */
class CoursesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Courses::class);
    }

    /**
     * Display courses by users
     *
     * @return void
     * @author Mathieu Bechade
     */
    public function getCoursesByUser($user)
    {
        return $this->createQueryBuilder('c')
            ->select('c.id AS course_id, c.name AS course_name, GROUP_CONCAT(r.name) AS runners, COUNT(m) AS markers')
            ->leftJoin('c.runners', 'r')
            ->leftJoin('c.markers', 'm')
            ->where('c.user = :user')
            ->setParameter('user', $user)
            ->groupBy('c.id')
            ->getQuery()
            ->getResult();
    }
}
