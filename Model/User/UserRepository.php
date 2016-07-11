<?php
namespace Model\User;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository{
  public function findByUsername($username){
    $resultSet = $this->createQueryBuilder('u')
         ->where('u.userName = :username')
         ->setParameter('username', $username)
         ->getQuery()
         ->getResult();

    return array_shift($resultSet);
  }
}
