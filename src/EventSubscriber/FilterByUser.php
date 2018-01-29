<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Filter\UserFilter;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class FilterByUser implements EventSubscriberInterface
{
    /** @var EntityManagerInterface */
    protected $em;

    /** @var TokenStorageInterface */
    protected $tokenStorage;

    /** @var Reader */
    protected $reader;

    public function __construct(EntityManagerInterface $em, TokenStorageInterface $tokenStorage, Reader $reader)
    {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
        $this->reader = $reader;
    }

    public static function getSubscribedEvents()
    {
        return [KernelEvents::REQUEST => ['configureFilter']];
    }

    public function configureFilter(): void
    {
        if (!$user = $this->getUser()) {
            return;
        }

        /** @var UserFilter $filter */
        $filter = $this->em->getFilters()->enable('user_filter');
        $filter->setParameter('id', $user->getId());
        $filter->setAnnotationReader($this->reader);
    }

    protected function getUser(): ?User
    {
        $token = $this->tokenStorage->getToken();

        if (!$token) {
            return null;
        }

        $user = $token->getUser();

        if (!$user instanceof User) {
            return null;
        }

        return $user;
    }
}