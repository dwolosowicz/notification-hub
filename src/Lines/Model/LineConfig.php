<?php

namespace App\Lines\Model;

class LineConfig {
    /** @var string */
    protected $code;

    /** @var bool */
    protected $isEnabled;

    public function __construct(string $code, bool $isEnabled)
    {
        $this->code = $code;
        $this->isEnabled = $isEnabled;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }
}