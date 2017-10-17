<?php

namespace PHPMD\Tests;

use PHPMD\Fingerprint;

class FingerprintTest extends \PHPUnit\Framework\TestCase
{
    public function testComputesFingerprintForMatchingRules()
    {
        $path = "some/path.php";
        $rule = "CyclomaticComplexity";
        $name = "foo";

        $fingerprintObject = new Fingerprint($path, $rule, $name);
        $fingerprint = $fingerprintObject->compute();

        $this->assertEquals("2d8de996e0cf4d8b62b4b5fd6262c72d", $fingerprint);
    }

    public function testDoesNotComputeFingerprintForNonMatchingRules()
    {
        $path = "some/path.php";
        $rule = "nomatch";
        $name = "foo";

        $fingerprintObject = new Fingerprint($path, $rule, $name);
        $fingerprint = $fingerprintObject->compute();

        $this->assertEquals(null, $fingerprint);
    }
}
