<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class RunnerLoginInput
{
    #[Assert\NotBlank(message: 'Code is required')]
    public ?string $code = null;
}
