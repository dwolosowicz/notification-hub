<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ChannelRepository")
 */
class Channel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Line", inversedBy="channels")
     * @ORM\JoinTable(name="channels_lines")
     */
    private $lines;

    public function __construct()
    {
        $this->lines = new ArrayCollection();
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

    public function getLines(): ArrayCollection
    {
        return $this->lines;
    }

    public function setLines($lines): void
    {
        $this->lines = $lines;
    }

    public function addLine(Line $line): void
    {
        $this->lines->add($line);

        $line->addChannel($this);
    }

    public function removeLine(Line $line): void
    {
        $this->lines->removeElement($line);

        $line->removeChannel($this);
    }
}
