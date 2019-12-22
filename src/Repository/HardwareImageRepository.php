<?php

namespace App\Repository;

use App\Entity\HardwareImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method HardwareImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method HardwareImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method HardwareImage[]    findAll()
 * @method HardwareImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HardwareImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HardwareImage::class);
    }

    // /**
    //  * @return HardwareImage[] Returns an array of HardwareImage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HardwareImage
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function exist(HardwareImage $image): bool
    {
        return (bool) $this->findOneBy(['link' => $image->getLink()]);
    }
}
