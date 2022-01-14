<?php

namespace SCM\User\ValueObject;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use DateTime;

/**
 * @ORM\MappedSuperclass
 */
class Person
{
    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string|null
     */
    #[Assert\NotBlank(message:'Merci de renseigner le nom')]
    #[Groups(["user:get", "user:create", "post:get", "post:comment:get"])]
    private ?string $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string|null
     */
    #[Assert\NotBlank(message:'Merci de renseigner le prénom')]
    #[Groups(["user:get", "user:create", "post:get", "post:comment:get"])]
    private ?string $firstName;

    /**
     * @ORM\Column(type="datetime")
     */
    #[Assert\NotBlank(message:'Merci de renseigner le date de naissance')]
    #[Groups(["user:get", "user:create"])]
    private ?DateTime $dateOfBirthday;

    /**
     * @ORM\Column(type="boolean")
     */
    #[Groups(["user:get", "user:create"])]
    private ?bool $gender;

    public function __construct(
        string $firstName = '',
        string $lastName = '',
        Datetime $dateOfBirthday = null,
        bool $gender = true
    ) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->dateOfBirthday = $dateOfBirthday;
        $this->gender = $gender;
    }

    /**
     * Get the value of lastName
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * Set the value of lastName
     *
     * @return  self
     */
    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get the value of firstName
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * Set the value of firstName
     *
     * @return  self
     */
    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get the value of dateOfBirthday
     */
    public function getDateOfBirthday(): ?DateTime
    {
        return $this->dateOfBirthday;
    }

    /**
     * Set the value of dateOfBirthday
     *
     * @return  self
     */
    public function setDateOfBirthday(?Datetime $dateOfBirthday)
    {
        $this->dateOfBirthday = $dateOfBirthday;

        return $this;
    }

    public function getAge(): int
    {
        if (null === $this->getDateOfBirthday()) {
            return 0;
        }

        $diff = $this->getDateOfBirthday()
            ->diff(new DateTime('now'));

        return $diff->y;
    }

    /**
     * Get fullname from firstName and lastName
     *
     * @return string
     */
    public function getFullname(): string
    {
        return $this->getFirstName()  . ' ' . $this->getLastName();
    }

    /**
     * Get the value of gender
     */
    public function getGender(): ?bool
    {
        return $this->gender;
    }

    /**
     * Set the value of gender
     *
     * @return  self
     */
    public function setGender(?bool $gender): self
    {
        $this->gender = $gender;

        return $this;
    }
}
