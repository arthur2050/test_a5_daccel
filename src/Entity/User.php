<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;


#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length:180, unique:true)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
	private ?string $name = null;

    #[ORM\Column(length: 255)]
	private ?string $password = null;

    #[ORM\Column]
    private array $roles = [];

	public function __toString()
	{
		return (string) $this->name;
	}

	/**
	 * @return int|null
	 */
    public function getId(): ?int
    {
        return $this->id;
    }

	/**
	 * @return string|null
	 */
    public function getEmail(): ?string
    {
        return $this->email;
    }

	/**
	 * @param string $email email
	 *
	 * @return $this
	 */
    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

	/**
	 * @return string|null
	 */
    public function getName(): ?string
    {
        return $this->name;
    }

	/**
	 * @param string $name name
	 *
	 * @return $this
	 */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

	/**
	 * @return string|null
	 */
    public function getPassword(): ?string
    {
        return $this->password;
    }

	/**
	 * @param string $password password
	 *
	 * @return $this
	 */
    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

	/**
	 * @return array|string[]
	 */
    public function getRoles(): array
    {
		$roles = $this->roles;
		$roles[] = 'ROLE_USER';
		return array_unique($roles);
	}

	/**
	 * @param array $roles roles
	 *
	 * @return $this
	 */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

	/**
	 * @return string
	 */
	public function getUserIdentifier(): string
	{
		return $this->email;
	}

	/**
	 * @return void
	 */
	public function eraseCredentials(): void
	{
		// If you store temporary sensitive data on the user, clear it here
	}
}
