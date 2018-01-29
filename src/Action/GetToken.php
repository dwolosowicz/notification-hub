<?php

namespace App\Action;

use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;

class GetToken
{
    /**
     * @Route(path="/api/jwt/token", name="api_jwt_token", methods={"POST"})
     *
     * @SWG\Parameter(
     *     name="credentials",
     *     description="The account's credentials",
     *     in="body",
     *     @SWG\Schema(properties={
     *         @SWG\Property(title="username", type="string", property="username"),
     *         @SWG\Property(title="password", type="string", property="password")
     *     })
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Authenticated token",
     *     @SWG\Schema(properties={
     *         @SWG\Property(title="token", type="string", property="token"),
     *     })
     * )
     *
     * @SWG\Response(
     *     response=401,
     *     description="Bad credentials",
     *     @SWG\Schema(properties={
     *         @SWG\Property(title="code", type="number", property="code", example="401"),
     *         @SWG\Property(title="message", type="string", property="message", example="Bad credentials"),
     *     })
     * )
     *
     * @SWG\Tag(name="Authentication")
     */
    public function __invoke()
    {

    }
}