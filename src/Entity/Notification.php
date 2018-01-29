<?php

namespace App\Entity;

use App\Annotation\UserAware;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as AppAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NotificationRepository")
 * @UserAware()
 */
class Notification
{
    const STATUS_WAITING = "waiting";
    const STATUS_SENT = "sent";

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Groups({"default_read"})
     */
    private $id;

    /**
     * @var Channel
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Channel", inversedBy="notifications")
     *
     * @Assert\NotBlank()
     * @AppAssert\Owned()
     *
     * @Groups({"default_write", "default_read"})
     */
    private $channel;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Channel")
     *
     * @Assert\NotBlank()
     */
    private $user;

    /**
     * @var \DateTimeImmutable
     *
     * @ORM\Column(type="datetime_immutable")
     *
     * @Groups({"default_read"})
     */
    private $createdAt;

    /**
     * @var \DateTimeImmutable
     *
     * @ORM\Column(type="datetime_immutable", nullable=true)
     *
     * @Groups({"default_read"})
     */
    private $sentAt;

    /**
     * @ORM\Column(type="text")
     *
     * @Assert\NotBlank()
     *
     * @Groups({"default_write", "default_read"})
     */
    private $content;

    /**
     * @var boolean
     *
     * @ORM\Column(type="string", length=64)
     *
     * @Assert\NotBlank()
     * @Assert\Choice(choices={Notification::STATUS_WAITING, Notification::STATUS_SENT})
     *
     * @Groups({"default_write", "default_read"})
     */
    private $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChannel(): ?Channel
    {
        return $this->channel;
    }

    public function setChannel(?Channel $channel): void
    {
        $channel ? $channel->addNotification($this) : $this->channel->removeNotification($this);

        $this->channel = $channel;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getSentAt(): ?\DateTimeImmutable
    {
        return $this->sentAt;
    }

    public function setSentAt(\DateTimeImmutable $sentAt): void
    {
        $this->sentAt = $sentAt;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): void
    {
        $this->content = $content;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }
}
