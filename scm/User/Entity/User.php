<?php

namespace SCM\User\Entity;

use SCM\User\ValueObject\Person;
use SCM\User\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use Infrastructure\Security\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
#[ApiResource(
    normalizationContext: ['groups' => ['user_get']],
    paginationItemsPerPage: 6,
)]
#[ApiFilter(OrderFilter::class, properties: ['id', 'lastname', 'firstname'])]
#[ApiFilter(SearchFilter::class, properties: [
    'gender' => 'exact',
    'lastname' => 'partial',
    'firstname' => 'partial',
    'email' => 'partial'
])]
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(["user_get"])]
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Assert\NotBlank(message:'Merci de renseigner une adresse email')]
    #[Groups(["user_get"])]
    private string $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    #[Groups(["user_get"])]
    private ?string $avatar;

    /**
     * @ORM\Embedded(class="SCM\User\ValueObject\Person")
     *
     */
    #[Groups(["user_get"])]
    private Person $person;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $deletedAt = null;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $deletedBy;

    /**
     * @ORM\Column(type="datetime")
     */
    #[Groups(["user_get"])]
    private \DateTimeInterface $createdAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(["user_get"])]
    private string $createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    #[Groups(["user_get"])]
    private \DateTimeInterface $updatedAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(["user_get"])]
    private string $updatedBy;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $plainPassword = '';

    public function __construct()
    {
        $this->person = new Person();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials(): void
    {
    }

    public function getDeletedBy(): ?string
    {
        return $this->deletedBy;
    }

    public function setDeletedBy(?string $deletedBy): self
    {
        $this->deletedBy = $deletedBy;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function isAccountNonExpired(): bool
    {
        return true;
    }

    public function isAccountNonLocked(): bool
    {
        return true;
    }

    public function isCredentialsNonExpired(): bool
    {
        return true;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedBy(): ?string
    {
        return $this->createdBy;
    }

    public function setCreatedBy(string $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUpdatedBy(): ?string
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(string $updatedBy): self
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = ['ROLE_USER'];

        return $roles;
    }

    public function getUsername()
    {
        return $this->email;
    }


    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getPerson(): Person
    {
        return $this->person;
    }
}
