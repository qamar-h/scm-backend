<?php

namespace SCM\News\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use SCM\User\Entity\User;
use SCM\News\Repository\PostRepository;
use SCM\Utils\Entity\BlameableTrait;
use SCM\Utils\Entity\TimestampableTrait;
use Infrastructure\ApiPlatform\DeletedAtFilter;
use SCM\News\Entity\Comment;
use Doctrine\Common\Collections\Collection;
use SCM\News\Message\Post\{ PostPersist, PostRemove };

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 */
#[ApiResource(
    collectionOperations:[
        "post" => ["messenger" => true,  "output" => false, "status" => 201],
        "get"
    ],
    itemOperations: [
        "get",
        "put",
        "patch",
        "delete" => ["messenger" => true,  "output" => false, "status" => 202],
    ],
    denormalizationContext: ['groups' => ["post:create"]],
    normalizationContext: ['groups' => ["post:get"]]
)]
#[ApiFilter(OrderFilter::class, properties: ['id', 'title','slug'])]
#[ApiFilter(SearchFilter::class, properties: ['title' => 'partial', 'slug' => 'partial','forAll' => 'exact'])]
#[ApiFilter(DeletedAtFilter::class)]
class Post
{
    use TimestampableTrait;

    use BlameableTrait;

    public const MESSAGES = [
        'persist' => PostPersist::class,
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(["post:get"])]
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(["post:get","post:create"])]
    private ?string $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(["post:get","post:create"])]
    #[Assert\Unique(message:'Le slug est dèja utilisé')]
    private ?string $slug;

    /**
     * @ORM\Column(type="text")
     */
    #[Groups(["post:get","post:create"])]
    private ?string $content;

    /**
     * @ORM\Column(type="boolean")
     */
    #[Groups(["post:get","post:create"])]
    private ?bool $forAll;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     */
    #[Groups(["post:get","post:create"])]
    private ?User $author;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="post")
     */
    private $comments;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getForAll(): ?bool
    {
        return $this->forAll;
    }

    public function setForAll(bool $forAll): self
    {
        $this->forAll = $forAll;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

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
            $comment->setPost($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }
}
