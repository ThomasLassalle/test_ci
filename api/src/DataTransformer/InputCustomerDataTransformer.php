<?php

declare(strict_types=1);

namespace App\DataTransformer;


use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Entity\Customer;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class InputCustomerDataTransformer implements DataTransformerInterface
{
    /** @var UserPasswordEncoderInterface */
    private $encoder;

    /**
     * InputCustomerDataTransformer constructor.
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function transform($object, string $to, array $context = [])
    {
        $customer = new Customer();
        $customer->setUsername($object->pseudo);
        $customer->setPassword($this->encoder->encodePassword($customer, 'password'));

        return $customer;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof Customer) {
            return false;
        }

        return $to === Customer::class;
    }

}
