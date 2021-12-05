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
use Doctrine\Common\Collections\Collection;
use SCM\News\Entity\{ Post, Comment };
use SCM\User\Message\{ UserPersist, UserRemove };

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
    denormalizationContext: ['groups' => ['user:create']],
    normalizationContext: ['groups' => ['user:get']],
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

    public const MESSAGES = [
        'persist' => UserPersist::class,
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(["user:get"])]
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Assert\NotBlank(message:'Merci de renseigner une adresse email')]
    #[Groups(["user:get", "user:create"])]
    private string $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    #[Groups(["user:get", "user:create", "post:get", "post:comment:get"])]
    private ?string $avatar;

    /**
     * @ORM\Embedded(class="SCM\User\ValueObject\Person")
     */
    #[Groups(["user:get", "user:create", "post:get", "post:comment:get"])]
    #[Assert\Valid]
    private Person $person;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    #[Groups(["user:create"])]
    private ?string $plainPassword = '';

    /**
     * @ORM\OneToMany(targetEntity=Post::class, mappedBy="author")
     */
    private $posts;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="author")
     */
    private $comments;

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

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setAuthor($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getAuthor() === $this) {
                $post->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setAuthor($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getAuthor() === $this) {
                $comment->setAuthor(null);
            }
        }

        return $this;
    }
}
