<?php

namespace App\Repository;

use App\Entity\MarkovKey;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MarkovKey|null find($id, $lockMode = null, $lockVersion = null)
 * @method MarkovKey|null findOneBy(array $criteria, array $orderBy = null)
 * @method MarkovKey[]    findAll()
 * @method MarkovKey[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MarkovKeyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MarkovKey::class);
    }

    public function getStartingPrefix()
    {
        /*
         * [todo] This is probably extremely slow
         * Figure out a better way..
         */
        $keys = $this->createQueryBuilder('k')
           ->where('k.pair LIKE :query')
           ->setParameter('query', "\n%")
           ->getQuery()
           ->getResult();

		$keyIndex = array_rand($keys);

		return $keys[$keyIndex];
    }

    // /**
    //  * @return MarkovKey[] Returns an array of MarkovKey objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('k.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MarkovKey
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
