<?php

namespace App\Entity\Traits;


use Doctrine\ORM\Mapping as ORM;

trait CreatedAtTrait
{
	#[ORM\Column(type: 'datetime_immutable')]
	protected $createdAt;

	/**
	 * @return \DateTime
	 */
	public function getCreatedAt(): \DateTimeImmutable
	{
		return $this->createdAt;
	}

	/**
	 * @param \DateTimeImmutable $createdAt created at
	 */
	public function setCreatedAt(\DateTimeImmutable $createdAt): static
	{
		$this->createdAt = $createdAt;

		return $this;
	}
}