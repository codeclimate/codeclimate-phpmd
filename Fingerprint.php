<?php

namespace PHPMD;

class Fingerprint
{
    const OVERRIDE_RULES = ["match"];
    private $path;
    private $rule;

    function __construct($path, $rule) {
        $this->path = $path;
        $this->rule = $rule;
    }

    public function compute()
    {
        $fingerprint = NULL;

        if (in_array($this->rule, self::OVERRIDE_RULES)) {
          $fingerprint = md5($this->path . $this->rule);
        }

        return $fingerprint;
    }
}
