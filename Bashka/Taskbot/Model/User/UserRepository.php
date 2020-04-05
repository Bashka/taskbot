<?php

namespace Bashka\Taskbot\Model\User;

use Doctrine\ORM\EntityRepository;

/**
 * Class UserRepository
 */
class UserRepository extends EntityRepository
{
    /**
     * @param $username
     * @return mixed
     */
    public function findByUsername($username)
    {
        $resultSet = $this->createQueryBuilder('u')
            ->where('u.userName = :username')
            ->setParameter('username', $username)
            ->getQuery()
            ->getResult();

        return array_shift($resultSet);
    }
}
