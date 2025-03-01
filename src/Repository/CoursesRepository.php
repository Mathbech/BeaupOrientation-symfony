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
     * @return array
     * @author Mathieu Bechade
     */
    public function getCoursesByUser(
        ?string $user = null,
        ?int $id = null
    ): array {
        $qb = $this->createQueryBuilder('c')
            ->select('c.id, c.name, GROUP_CONCAT(r.name) AS runners, COUNT(m) AS markers, GROUP_CONCAT(DISTINCT CONCAT(ST_X(m.point), ST_Y(m.point))) AS point')
            ->leftJoin('c.runners', 'r')
            ->leftJoin('c.markers', 'm')
            ->andWhere('c.user = :user')
            ->setParameter('user', $user)
            ->groupBy('c.id');

        if ($id !== null) {
            $qb->andWhere('c.id = :id')
                ->setParameter('id', $id);
        }

        return $qb->getQuery()
            ->getResult();
    }
}
