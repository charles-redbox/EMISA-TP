<?php

namespace App\Repository;

use App\Entity\Student;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Student>
 *
 * @method Student|null find($id, $lockMode = null, $lockVersion = null)
 * @method Student|null findOneBy(array $criteria, array $orderBy = null)
 * @method Student[]    findAll()
 * @method Student[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Student::class);
    }

    public function add(Student $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Student $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findStudentsByFirstName(string $firstname): array
    {
        return $this->createQueryBuilder('s')
            ->where('s.firstName LIKE :firstname')
            ->setParameter('firstname','%' . $firstname . '%')
            ->orderBy('s.firstName', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findStudentsByEmail(string $email): array
    {
        return $this->createQueryBuilder('s')
            ->where('s.email LIKE :email')
            ->setParameter('email','%' . $email . '%')
            ->orderBy('s.email', 'ASC')
            ->getQuery()
            ->getResult();
    }

}
