<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class RegisterRequest
{
    /**
     * @Assert\NotBlank
     */
    public string $username;

    /**
     * @Assert\NotBlank
     * @Assert\Email
     */
    public string $email;

    /**
     * @Assert\NotBlank
     * @Assert\Length(min=8)
     */
    public string $password;
}
