<?php

namespace App\Repository;

use App\Entity\Caracter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Caracter>
 *
 * @method Caracter|null find($id, $lockMode = null, $lockVersion = null)
 * @method Caracter|null findOneBy(array $criteria, array $orderBy = null)
 * @method Caracter[]    findAll()
 * @method Caracter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CaracterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Caracter::class);
    }

    public function add(Caracter $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Caracter $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOneByIdentifier(string $identifier): ?Player
    {
        return $this->createQueryBuilder('c')
           ->select('c', 'p')
           ->leftJoin('c.players', 'p')
           ->where('c.identifier = :identifier')
           ->setParameter('identifier', $identifier)
           ->getQuery()
           ->getOneOrNullResult()
        ;
    }

    public function findByIntelligence(int $intelligence): ?array
    {
        return $this->createQueryBuilder('c')
            ->select('c')
            ->where('c.intelligence >= :intelligence')   
            ->setParameter('intelligence', $intelligence)
            ->getQuery()
            ->getArrayResult()
        ; 
    }

//    /**
//     * @return Caracter[] Returns an array of Caracter objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Caracter
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
