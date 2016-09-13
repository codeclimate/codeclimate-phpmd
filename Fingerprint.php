<?php

namespace PHPMD;

class Fingerprint
{
    const OVERRIDE_RULES = [
      "CyclomaticComplexity",
      "Design/LongClass",
      "Design/LongMethod",
      "Design/LongParameterList",
      "Design/NpathComplexity",
      "Design/NumberOfChildren",
      "Design/TooManyFields",
      "Design/TooManyMethods",
      "Design/TooManyPublicMethods",
      "Design/WeightedMethodCount",
      "ExcessivePublicCount",
    ];
    private $name;
    private $path;
    private $rule;

    public function __construct($path, $rule, $name)
    {
        $this->path = $path;
        $this->rule = $rule;
        $this->name = $name;
    }

    public function compute()
    {
        $fingerprint = null;

        if (in_array($this->rule, self::OVERRIDE_RULES)) {
            $fingerprint = md5($this->path . $this->rule . $this->name);
        }

        return $fingerprint;
    }
}
