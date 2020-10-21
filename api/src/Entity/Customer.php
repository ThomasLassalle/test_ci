<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\DTO\CustomerDto;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity
 * @ApiResource(
 *     normalizationContext={"groups"={"Customer/Read"}},
 *     collectionOperations={
 *          "get" = {"output" = CustomerDto::class},
 *          "post" = {"input" = CustomerDto::class, "output" = CustomerDto::class}
 *     },
 *     itemOperations={
 *           "get" = {
 *              "output" = CustomerDto::class,
 *              "normalization_context" = {"groups"={"Customer/Item/Read"}},
 *              "security" = "is_granted('ROLE_ADMIN') or object === user"
 *          },
 *          "forgotPassword" = {
 *              "method" = "patch",
 *              "path" = "customers/{id}/resetPassword",
 *              "denormalization_context" = {"groups" = {"Customer:Item:ResetPassword"}},
 *              "output" = false,
 *              "status" = 202,
 *              "messenger" = true
 *          }
 *     }
 * )
 */
class Customer implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @ApiProperty(iri="http://schema.org/name")
     * @Groups({"Customer/Read", "Customer/Item/Read"})
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @var string The plain password
     * @Groups({"Customer:Item:ResetPassword"})
     */
    private $plainPassword;

    /**
     * @ORM\OneToMany(targetEntity=MacDonaldOrder::class, mappedBy="customer")
     * @Groups({"Customer/Read", "Customer/Item/Read"})
     * @ApiSubresource
     */
    private $macDonaldOrders;

    public function __construct()
    {
        $this->macDonaldOrders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     */
    public function setPlainPassword(string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|MacDonaldOrder[]
     */
    public function getMacDonaldOrders(): Collection
    {
        return $this->macDonaldOrders;
    }

    public function addMacDonaldOrder(MacDonaldOrder $macDonaldOrder): self
    {
        if (!$this->macDonaldOrders->contains($macDonaldOrder)) {
            $this->macDonaldOrders[] = $macDonaldOrder;
            $macDonaldOrder->setCustomer($this);
        }

        return $this;
    }

    public function removeMacDonaldOrder(MacDonaldOrder $macDonaldOrder): self
    {
        if ($this->macDonaldOrders->contains($macDonaldOrder)) {
            $this->macDonaldOrders->removeElement($macDonaldOrder);
            // set the owning side to null (unless already changed)
            if ($macDonaldOrder->getCustomer() === $this) {
                $macDonaldOrder->setCustomer(null);
            }
        }

        return $this;
    }
}
