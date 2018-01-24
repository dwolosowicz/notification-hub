<?php

namespace App\Component\Http\Request;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

class JsonRequest
{
    /** @var ParameterBag */
    public $json;

    /** @var Request */
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->json = $this->createJsonBag($request);
    }

    protected function createJsonBag(Request $request): ParameterBag
    {
        $json = json_decode($request->getContent(), true);

        return new ParameterBag($json ?: []);
    }
}