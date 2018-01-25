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
use Swagger\Annotations as SWG;

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
     *
     * @SWG\Parameter(
     *     name="account",
     *     description="The new account credentials",
     *     in="body",
     *     @SWG\Schema(properties={
     *         @SWG\Property(title="username", type="string", property="username"),
     *         @SWG\Property(title="email", type="string", property="email"),
     *         @SWG\Property(title="plainPassword", type="string", property="plainPassword")
     *     })
     * )
     *
     * @SWG\Response(
     *     response=204,
     *     description="Account has been created"
     * )
     *
     * @SWG\Tag(name="Account")
     */
    public function __invoke(Request $request): Response
    {
        $jsonRequest = new JsonRequest($request);

        $user = new User();
        $user->setEmail($jsonRequest->json->get('email'));
        $user->setUsername($jsonRequest->json->get('username'));
        $user->setPlainPassword($jsonRequest->json->get('plainPassword'));

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