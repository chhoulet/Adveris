<?php

namespace UserBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository
{
	public function getUsersByProject($idProject)
	{
		$query=$this->getEntityManager()->createQuery('
			SELECT u 
			FROM UserBundle:User u 
			JOIN u.team_members t 
			JOIN t.project = :idProject')
		->setParameter('idProject', $idProject);
	}

	public function getUsersByProjectQB($idProject)
	{
		$qb = $this->createQueryBuilder('q');
		$qb->select('u')
		   ->from('UserBundle:User', 'u')
		   ->leftjoin('u.team_members','t')
		   ->leftjoin('t.project','p')
		   ->where('p.id = :idProject')
		   ->orderBy('u.id', 'ASC')
		   ->setParameter('idProject', $idProject);

		return $qb;
	}
}
 