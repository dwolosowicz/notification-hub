<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Annotation\UserAware;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Validator\Constraints as AppAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LineRepository")
 * @UserAware()
 * @ApiResource(attributes={
 *     "normalization_context"={"groups"={"default"}},
 *     "denormalization_context"={"groups"={"default"}}
 * })
 */
class Line
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
     * @ORM\Column(type="string", length=64)
     *
     * @AppAssert\LineType();
     *
     * @Groups({"default"})
     */
    private $type;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Channel", mappedBy="lines")
     */
    private $channels;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $user;

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

    public function getChannels(): Collection
    {
        return $this->channels;
    }

    public function setChannels($channels): void
    {
        $this->channels = $channels;
    }

    public function addChannel(Channel $channel): void
    {
        if ($this->channels->contains($channel)) {
            return;
        }

        $this->channels->add($channel);

        $channel->addLine($this);
    }

    public function removeChannel(Channel $channel): void
    {
        if (!$this->channels->contains($channel)) {
            return;
        }

        $this->channels->removeElement($channel);

        $channel->removeLine($this);
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
