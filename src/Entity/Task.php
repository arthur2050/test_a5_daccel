<?php

namespace App\Entity;

use App\Entity\Traits\CreatedAtTrait;
use App\Enum\Api\TaskStatus;
use App\Repository\TaskRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Ignore;
use OpenApi\Attributes as OA;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
#[ORM\Index(name: "status_idx", columns: ["status"])]
#[ORM\Index(name: "deadline_idx", columns: ["deadline"])]
class Task
{
	use CreatedAtTrait;

	#[OA\Property(type: 'integer', description: 'ID задачи')]
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column(type: 'integer')]
	private ?int $id = null;
	#[OA\Property(type: 'string', description: 'Заголовок задачи')]
	#[ORM\Column(type: 'string', length: 255)]
	private string $title;

	#[OA\Property(type: 'string', nullable: true, description: 'Описание задачи')]
	#[ORM\Column(type: 'text', nullable: true)]
	private ?string $description = null;
	#[OA\Property(type: 'string', description: 'Статус задачи')]
	#[ORM\Column(type: 'string', enumType: TaskStatus::class)]
	private TaskStatus $status = TaskStatus::NEW;

	#[OA\Property(type: 'string', format: 'date-time', description: 'Дедлайн задачи')]
	#[ORM\Column(type: 'datetime')]
	private \DateTimeInterface $deadline;

	#[ORM\ManyToOne(targetEntity: User::class)]
	#[ORM\JoinColumn(nullable: false)]
	#[Ignore]
	private User $owner;


	public function __construct()
	{
		$this->createdAt = new \DateTime();
	}

	/**
	 * @return int|null
	 */
	public function getId(): ?int
	{
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getTitle(): string
	{
		return $this->title;
	}

	/**
	 * @param string $title title
	 *
	 * @return $this
	 */
	public function setTitle(string $title): static
	{
		$this->title = $title;

		return $this;
	}

	/**
	 * @return string|null
	 */

	public function getDescription(): ?string
	{
		return $this->description;
	}

	/**
	 * @param string|null $description description
	 *
	 * @return $this
	 */
	public function setDescription(?string $description): static
	{
		$this->description = $description;

		return $this;
	}

	/**
	 * @return TaskStatus
	 */
	public function getStatus(): TaskStatus
	{
		return $this->status;
	}

	/**
	 * @param TaskStatus $status status
	 *
	 * @return $this
	 */
	public function setStatus(TaskStatus $status): static
	{
		$this->status = $status;

		return $this;
	}

	/**
	 * @return \DateTimeInterface
	 */

	public function getDeadline(): \DateTimeInterface
	{
		return $this->deadline;
	}

	/**
	 * @param \DateTimeInterface $deadline deadline
	 *
	 * @return $this
	 */
	public function setDeadline(\DateTimeInterface $deadline): static
	{
		$this->deadline = $deadline;

		return $this;
	}

	/**
	 * @return User
	 */
	public function getOwner(): User
	{
		return $this->owner;
	}

	/**
	 * @param User $owner owner
	 *
	 * @return $this
	 */
	public function setOwner(User $owner): static
	{
		$this->owner = $owner;

		return $this;
	}
}
