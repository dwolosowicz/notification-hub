<?php

namespace App\Validator\Constraints;

use App\Entity\User;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class OwnedValidator extends ConstraintValidator
{
    protected $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function validate($value, Constraint $constraint)
    {
        if ($value instanceof Collection) {
            $value = $value->toArray();
        }

        if (!is_array($value)) {
            $value = [$value];
        }

        $currentUser = $this->getUser();

        $userAwareObjects = array_filter($value, function ($object) {
            return method_exists($object, 'getUser');
        });

        $invalidObjects = [];

        foreach ($userAwareObjects as $userAwareObject) {
            if (!$userAwareObject->getUser()
                || $userAwareObject->getUser()->getId() != $currentUser->getId()) {
                $invalidObjects[] = $userAwareObject;
            }
        }

        if (empty($invalidObjects)) {
            return;
        }

        /* @var $constraint Owned */
        $this->context->buildViolation($constraint->message)->addViolation();
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
