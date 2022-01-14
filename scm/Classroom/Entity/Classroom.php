<?php

namespace SCM\Classroom\Entity;

use Doctrine\ORM\Mapping as ORM;
use SCM\Utils\Entity\BlameableTrait;
use SCM\Utils\Entity\TimestampableTrait;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Infrastructure\ApiPlatform\DeletedAtFilter;
use SCM\Classroom\Repository\ClassroomRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass=ClassroomRepository::class)
 */
#[ApiResource(
    shortName:"classroom",
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
    denormalizationContext: ['groups' => ["classroom:create"]],
    normalizationContext: ['groups' => ["classroom:get"]]
)]
#[ApiFilter(OrderFilter::class, properties: ['id', 'title','slug'])]
#[ApiFilter(SearchFilter::class, properties: ['title' => 'partial', 'slug' => 'partial','forAll' => 'exact'])]
#[ApiFilter(DeletedAtFilter::class)]
class Classroom
{
    use TimestampableTrait;
    use BlameableTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(["classroom:get"])]
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    #[Groups(["classroom:get"])]
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    #[Groups(["classroom:get"])]
    private $maxStudent;

    /**
     * @ORM\Column(type="integer")
     */
    #[Groups(["classroom:get"])]
    private $minAge;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    /**
     * @ORM\Column(type="date")
     */
    #[Groups(["classroom:get"])]
    private $startDate;

    /**
     * @ORM\Column(type="date")
     */
    #[Groups(["classroom:get"])]
    private $endDate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getMaxStudent(): ?int
    {
        return $this->maxStudent;
    }

    public function setMaxStudent(int $maxStudent): self
    {
        $this->maxStudent = $maxStudent;

        return $this;
    }

    public function getMinAge(): ?int
    {
        return $this->minAge;
    }

    public function setMinAge(int $minAge): self
    {
        $this->minAge = $minAge;

        return $this;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }
}
