<?php

namespace App\Repository;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TaskRepository extends ServiceEntityRepository
{
	/**
	 * @param ManagerRegistry $registry registry
	 */
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Task::class);
	}

	/**
	 * @param User $user user
	 *
	 * @return array
	 */
	public function findByUser(User $user): array
	{
		return $this->findBy(['owner' => $user]);
	}

	/**
	 * @param int $id id
	 * @param User $user user
	 *
	 * @return mixed
	 */
	public function findByIdAndUser(int $id, User $user)
	{
		$qb = $this->createQueryBuilder('t')
			->where('t.id = :id')
			->andWhere('t.owner = :user')
			->setParameter('id', $id)
			->setParameter('user', $user)
		;

		return $qb->getQuery()
			->setResultCacheLifetime(300)
			->setResultCacheId('task_'.$id.'_user_'.$user->getId())->getOneOrNullResult();
	}

	/**
	 * @param User $user user
	 * @param string|null $status status
	 * @param string|null $deadline deadline
	 *
	 * @return array
	 */
	public function filterByStatusAndDeadline(User $user, ?string $status, ?string $deadline): array
	{
		$qb = $this->createQueryBuilder('t')
			->where('t.owner = :user')
			->setParameter('user', $user);

		if ($status) {
			$qb->andWhere('t.status = :status')
				->setParameter('status', $status);
		}

		if ($deadline) {
			$qb->andWhere('t.deadline <= :deadline')
				->setParameter('deadline', $deadline);
		}

		return $qb->getQuery()->getResult();
	}
}
