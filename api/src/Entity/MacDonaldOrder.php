<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class MacDonaldOrder
 * @package App\Entity
 * @ApiResource(
 *     mercure=true,
 *     description="MacDonald Orders placed by a customer.",
 *     collectionOperations={
 *          "get" = {"normalization_context" = {"groups" = {"MacDonaldOrder/Read"}}},
 *          "post",
 *          "customRoute" = {"path" = "mac_donald_orders/custom", "method" = "GET"}
 *     },
 *     itemOperations={
 *          "get",
 *          "put" = {"security" = "is_granted('ROLE_ADMIN') or object.getCustomer() === user"}
 *     },
 *     attributes={"order" = {"name" = "ASC"}}
 * )
 * @ORM\Entity
 * @ApiFilter(PropertyFilter::class)
 * @ApiFilter(SearchFilter::class, properties={"name"="ipartial"})
 * @ApiFilter(OrderFilter::class, properties={"name"="status"})
 */
class MacDonaldOrder
{
    /**
     * @var string unique identifier for an order.
     * @ORM\Id
     * @ORM\Column
     * @ORM\GeneratedValue
     */
    public $number;
    /**
     * @var string Customer's name.
     * @ORM\Column
     * @Assert\NotBlank
     * @Assert\Length(min="1")
     * @ApiProperty(iri="http://schema.org/name")
     * @Groups({"Customer/Read", "Customer/Item/Read", "MacDonaldOrder/Read"})
     */
    public $name;
    /**
     * @var string Order's status.
     * @ORM\Column
     * @Assert\Choice({"En préparation", "Prêt", "Emporté"})
     * @Assert\Type("string")
     * @Groups({"Customer/Read", "MacDonaldOrder/Read"})
     */
    public $status;
    /**
     * @var integer Order's price amount.
     * @ORM\Column(type="integer")
     * @Assert\Type("integer")
     * @Assert\GreaterThan(0)
     * @Groups({"Customer/Read", "MacDonaldOrder/Read"})
     */
    public $total;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="macDonaldOrders")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"MacDonaldOrder/Read"})
     */
    private $customer;

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }
}
