<?php

namespace SCM\User\Entity;

use SCM\User\ValueObject\Person;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use Infrastructure\Security\UserInterface;
use SCM\Utils\Entity\BlameableTrait;
use SCM\Utils\Entity\TimestampableTrait;
use Symfony\Component\Serializer\Annotation\Groups;
use Infrastructure\ApiPlatform\DeletedAtFilter;

/**
 * @ORM\Entity(repositoryClass=SCM\User\Repository\UserRepository::class)
 */
#[ApiResource(
    collectionOperations:[
        "post" => ["messenger" => true,  "output" => false, "status" => 201],
        "get"
    ],
    itemOperations: [
        "get",
        "put",
        "patch" => ["messenger" => true,  "output" => false, "status" => 201],
        "delete" => ["messenger" => true,  "output" => false, "status" => 202],
    ],
    denormalizationContext: ['groups' => ['user_create']],
    normalizationContext: ['groups' => ['user_get']],
    paginationItemsPerPage: 6,
)]
#[ApiFilter(DeletedAtFilter::class)]
#[ApiFilter(OrderFilter::class, properties: ['id', 'lastname', 'firstname'])]
#[ApiFilter(SearchFilter::class, properties: [
    'person.gender' => 'exact',
    'person.lastname' => 'partial',
    'person.firstname' => 'partial',
    'email' => 'partial'
])]
class User implements UserInterface
{
    use TimestampableTrait;

    use BlameableTrait;

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
    #[Groups(["user_get","user_create"])]
    private string $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    #[Groups(["user_get","user_create"])]
    private ?string $avatar;

    /**
     * @ORM\Embedded(class="SCM\User\ValueObject\Person")
     */
    #[Groups(["user_get", "user_create"])]
    #[Assert\Valid]
    private Person $person;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    #[Groups(["user_create"])]
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

    public function setPerson(Person $person): self
    {
        $this->person = $person;

        return $this;
    }
}
