<?php

namespace SCM\News\Entity;

use SCM\News\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;
use SCM\User\Entity\User;
use SCM\News\Entity\Post;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use SCM\Utils\Entity\BlameableTrait;
use SCM\Utils\Entity\TimestampableTrait;
use Infrastructure\ApiPlatform\DeletedAtFilter;
use SCM\News\Message\Comment\CommentPersist;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 * @Table(name="post_comment")
 */
#[ApiResource(
    shortName:"Post-comment",
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
    denormalizationContext: ['groups' => ["post:comment:create"]],
    normalizationContext: ['groups' => ["post:comment:get"]]
)]
#[ApiFilter(OrderFilter::class, properties: ['id', 'title','slug'])]
#[ApiFilter(SearchFilter::class, properties: ['title' => 'partial', 'slug' => 'partial','forAll' => 'exact'])]
#[ApiFilter(DeletedAtFilter::class)]
class Comment
{
    use TimestampableTrait;

    use BlameableTrait;

    public const MESSAGES = [
        'persist' => CommentPersist::class,
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(["post:comment:get"])]
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    #[Groups(["post:comment:get", "post:comment:create"])]
    private ?string $comment;

    /**
     * @ORM\ManyToOne(targetEntity=Post::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    #[Groups(["post:comment:get", "post:comment:create"])]
    private ?Post $post;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    #[Groups(["post:comment:get", "post:comment:create"])]
    private ?User $author;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

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

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }
}
