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
            ->select('c.id, c.name')
            ->addSelect('COUNT(DISTINCT r.id) AS runnercount')
            ->addSelect('COUNT(DISTINCT m.id) AS markers')
            ->leftJoin('c.runners', 'r')
            ->leftJoin('c.markers', 'm')
            ->groupBy('c.id');

        if (!empty($user)) {
            $qb->andWhere('c.user = :user')
                ->setParameter('user', $user);
        }

        if (!empty($id)) {
            $qb->andWhere('c.id = :id')
                ->setParameter('id', $id);

            // Concatène les informations des markers (latitude, longitude, QR code et ID)
            $qb->addSelect("group_concat(DISTINCT CONCAT(m.id, ':', st_x(m.point), ':', st_y(m.point), ':', COALESCE(m.qrCode, ''), ':', COALESCE(m.type, ''), ':', COALESCE(m.name, ''))) AS markersData");

            $qb->addSelect('group_concat(DISTINCT r.name) AS runners');
        }

        return $qb->getQuery()
            ->getResult();
    }


}
