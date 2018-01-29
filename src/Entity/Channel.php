<?php

namespace App\Entity;

use App\Annotation\UserAware;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as AppAssert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ChannelRepository")
 * @UserAware()
 */
class Channel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Groups({"default"})
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     *
     * @Groups({"default"})
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     *
     * @Groups({"default"})
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     *
     * @Groups({"default"})
     */
    private $isActive;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Line", inversedBy="channels")
     * @ORM\JoinTable(name="channels_lines")
     *
     * @Groups({"default"})
     */
    private $lines;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $user;

    public function __construct()
    {
        $this->lines = new ArrayCollection();
        $this->isActive = false;
        $this->description = '';
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription($description): void
    {
        $this->description = $description;
    }

    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive($isActive): void
    {
        $this->isActive = $isActive;
    }

    public function getLines(): Collection
    {
        return $this->lines;
    }

    public function setLines($lines): void
    {
        $this->lines = $lines;
    }

    public function addLine(Line $line): void
    {
        if ($this->lines->contains($line)) {
            return;
        }

        $this->lines->add($line);

        $line->addChannel($this);
    }

    public function removeLine(Line $line): void
    {
        if (!$this->lines->contains($line)) {
            return;
        }

        $this->lines->removeElement($line);

        $line->removeChannel($this);
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }
}
