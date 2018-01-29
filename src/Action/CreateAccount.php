<?php

namespace App\Action;

use ApiPlatform\Core\Bridge\Symfony\Validator\Exception\ValidationException;
use App\Component\Canonicalizer\Canonicalizer;
use App\Component\Http\Request\JsonRequest;
use App\Entity\User;
use App\Model\Request\Account;
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
     *         @SWG\Property(title="password", type="string", property="password")
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

        $account = new Account(
            $jsonRequest->json->get('username', ''),
            $jsonRequest->json->get('email', ''),
            $jsonRequest->json->get('password', '')
        );

        $violations = $this->validator->validate($account, null);

        if (0 !== \count($violations)) {
            throw new ValidationException($violations);
        }

        $user = new User();
        $user->setUsername($account->getUsername());
        $user->setUsernameCanonical($this->canonicalizer->canonicalize($user->getUsername()));
        $user->setEmail($account->getEmail());
        $user->setEmailCanonical($this->canonicalizer->canonicalize($user->getEmail()));
        $user->setPassword(
            $this->passwordEncoder->encodePassword($user, $account->getPassword())
        );

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse(null, 204);
    }
}