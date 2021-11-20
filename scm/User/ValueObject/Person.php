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
    #[Groups(["user_get", "user_create"])]
    private ?string $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string|null
     */
    #[Assert\NotBlank(message:'Merci de renseigner le prénom')]
    #[Groups(["user_get", "user_create"])]
    private ?string $firstname;

    /**
     * @ORM\Column(type="datetime")
     */
    #[Assert\NotBlank(message:'Merci de renseigner le date de naissance')]
    #[Groups(["user_get", "user_create"])]
    private ?DateTime $dateOfBirthday;

    /**
     * @ORM\Column(type="boolean")
     */
    #[Groups(["user_get", "user_create"])]
    private ?bool $gender;

    public function __construct(
        string $firsntame = '',
        string $lastname = '',
        Datetime $dateOfBirthday = null,
        bool $gender = true
    ) {
        $this->firstname = $firsntame;
        $this->lastname = $lastname;
        $this->dateOfBirthday = $dateOfBirthday;
        $this->gender = $gender;
    }

    /**
     * Get the value of lastname
     */
    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    /**
     * Set the value of lastname
     *
     * @return  self
     */
    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get the value of firstname
     */
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * Set the value of firstname
     *
     * @return  self
     */
    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

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
     * Get fullname from firstname and lastname
     *
     * @return string
     */
    public function getFullname(): string
    {
        return $this->getFirstname()  . ' ' . $this->getLastname();
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
