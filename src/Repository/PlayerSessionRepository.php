<?php

namespace App\Repository;

use App\Entity\PlayerSession;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PlayerSession>
 *
 * @method PlayerSession|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlayerSession|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlayerSession[]    findAll()
 * @method PlayerSession[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayerSessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlayerSession::class);
    }

    public function findByIdWithInfo(int $id): ?PlayerSession
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT playerSession,p,s,gr
            FROM App\Entity\PlayerSession playerSession
            INNER JOIN playerSession.session s
            INNER JOIN playerSession.player p
            INNER JOIN playerSession.gameRole gr
            WHERE playerSession.id = :id'
        )->setParameter('id',$id);
        
        return $query->getOneOrNullResult();
    }
    public function findAllWithInfo(): ?array
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT playerSession,p,s, gr
            FROM App\Entity\PlayerSession playerSession
            INNER JOIN playerSession.session s
            INNER JOIN playerSession.player p
            INNER JOIN playerSession.gameRole gr
            ORDER BY s.date desc,playerSession.score desc'
        );
        
        return $query->getResult();
    }
    //    /**
    //     * @return PlayerSession[] Returns an array of PlayerSession objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?PlayerSession
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
