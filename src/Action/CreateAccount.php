<?php

namespace App\Action;

use ApiPlatform\Core\Bridge\Symfony\Validator\Exception\ValidationException;
use App\Component\Canonicalizer\Canonicalizer;
use App\Component\Http\Request\JsonRequest;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateAccount
{
    /** @var ValidatorInterface */
    protected $validator;

    /** @var UserPasswordEncoderInterface */
    protected $passwordEncoder;

    /** @var EntityManagerInterface */
    protected $entityManager;

    public function __construct(ValidatorInterface $validator, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager)
    {
        $this->validator = $validator;
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
        $this->canonicalizer = new Canonicalizer();
    }

    /**
     * @Route(
     *     name="api_account",
     *     methods={"POST"},
     *     path="/api/account"
     * )
     */
    public function __invoke(Request $request): Response
    {
        $request = new JsonRequest($request);

        $user = new User();
        $user->setEmail($request->json->get('email'));
        $user->setUsername($request->json->get('username'));
        $user->setPlainPassword($request->json->get('plain_password'));

        $violations = $this->validator->validate($user, null);

        if (0 !== \count($violations)) {
            throw new ValidationException($violations);
        }

        $user->setUsernameCanonical($this->canonicalizer->canonicalize($user->getUsername()));
        $user->setEmailCanonical($this->canonicalizer->canonicalize($user->getEmail()));

        $user->setPassword(
            $this->passwordEncoder->encodePassword($user, $user->getPlainPassword())
        );

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse(null, 204);
    }
}