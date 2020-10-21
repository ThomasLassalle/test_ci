<?php

declare(strict_types=1);

namespace App\DataTransformer;


use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\DTO\CustomerDto;
use App\Entity\Customer;

class OutputCustomerDataTransformer implements DataTransformerInterface
{
    public function transform($object, string $to, array $context = [])
    {
        $customerDto = new CustomerDto();
        $customerDto->pseudo = $object->getUsername();

        return $customerDto;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return $data instanceof Customer && CustomerDto::class === $to;
    }

}
