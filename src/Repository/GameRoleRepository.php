<?php

namespace App\Repository;

use App\Entity\GameRole;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GameRole>
 *
 * @method GameRole|null find($id, $lockMode = null, $lockVersion = null)
 * @method GameRole|null findOneBy(array $criteria, array $orderBy = null)
 * @method GameRole[]    findAll()
 * @method GameRole[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRoleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GameRole::class);
    }

    public function findGameRoleWithPlayerSession($id): ?GameRole
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT gr,g,ps,p,s
            FROM App\Entity\GameRole gr
            LEFT JOIN gr.game g
            LEFT JOIN gr.playerSessions ps
            INNER JOIN ps.session s
            INNER JOIN ps.player p
            WHERE gr.id = :id'
        )->setParameter('id', $id);
        
        return $query->getOneOrNullResult();
    }

    

    //    /**
    //     * @return GameRole[] Returns an array of GameRole objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('g.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?GameRole
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
