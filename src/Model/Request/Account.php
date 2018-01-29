<?php

namespace App\Model\Request;

use Symfony\Component\Validator\Constraints as Assert;

class Account
{
    /**
     * @Assert\NotBlank()
     */
    protected $username;

    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    protected $email;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     */
    protected $password;

    public function __construct(string $username, string $email, string $password)
    {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }
}