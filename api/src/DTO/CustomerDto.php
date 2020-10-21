<?php

declare(strict_types=1);

namespace App\DTO;


use Symfony\Component\Serializer\Annotation\Groups;

class CustomerDto
{
    /**
     * @var string username
     * @Groups({"Customer/Item/Read", "Customer/Read"})
     */
    public $pseudo;
}
