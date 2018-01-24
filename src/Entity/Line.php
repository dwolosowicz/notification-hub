<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LineRepository")
 */
class Line
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $type;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Channel", mappedBy="lines")
     */
    private $channels;

    public function __construct()
    {
        $this->channels = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType($type): void
    {
        $this->type = $type;
    }

    public function getChannels(): ArrayCollection
    {
        return $this->channels;
    }

    public function setChannels($channels): void
    {
        $this->channels = $channels;
    }

    public function addChannel(Channel $channel): void
    {
        $this->channels->add($channel);

        $channel->addChannel($this);
    }

    public function removeChannel(Channel $channel): void
    {
        $this->channels->removeElement($channel);

        $channel->removeChannel($this);
    }
}
