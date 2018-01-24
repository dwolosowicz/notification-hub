<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity()
 * @UniqueEntity(fields="email", message="Email is already taken")
 * @UniqueEntity(fields="username", message="Username is already taken")
 */
class User implements AdvancedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true)
     *
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @ORM\Column(type="string")
     */
    private $usernameCanonical;

    /**
     * @ORM\Column(type="string", unique=true)
     *
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string")
     */
    private $emailCanonical;

    /**
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="json_array")
     */
    private $roles;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isEnabled;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isLocked;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isExpired;

    /**
     * @ORM\Column(type="boolean")
     */
    private $areCredentialsExpired;

    public function __construct()
    {
        $this->isEnabled = true;
        $this->isLocked = false;
        $this->isExpired = false;
        $this->areCredentialsExpired = false;

        $this->roles = ['ROLE_USER'];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    public function getUsernameCanonical(): string
    {
        return $this->usernameCanonical;
    }

    public function setUsernameCanonical(string $usernameCanonical): void
    {
        $this->usernameCanonical = $usernameCanonical;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getEmailCanonical(): string
    {
        return $this->emailCanonical;
    }

    public function setEmailCanonical(string $emailCanonical): void
    {
        $this->emailCanonical = $emailCanonical;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function isAccountNonExpired(): bool
    {
        return !$this->isExpired;
    }

    public function isAccountNonLocked(): bool
    {
        return !$this->isLocked;
    }

    public function isCredentialsNonExpired(): bool
    {
        return !$this->areCredentialsExpired;
    }

    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }

    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }
}