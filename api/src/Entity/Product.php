<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource(
 *     itemOperations={"get"},
 *     collectionOperations={"get"}
 * )
 */
class Product
{
    /**
     * @var string uuid
     * @ApiProperty(identifier=true)
     */
    public $id;

    /**
     * @var string product name
     */
    public $name;

    /**
     * @var integer product price
     */
    public $price;
}
