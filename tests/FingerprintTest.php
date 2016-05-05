<?php

require_once __DIR__ . "/../Fingerprint.php";

use PHPMD\Fingerprint;

class FingerprintTest extends PHPUnit_Framework_TestCase
{
    public function testComputesFingerprintForMatchingRules()
    {
        $path = "some/path.php";
        $rule = "match";

        $fingerprintObject = new Fingerprint($path, $rule);
        $fingerprint = $fingerprintObject->compute();

        $this->assertEquals("25156d6a6beb3a3ee352aeead6b906ac", $fingerprint);
    }

    public function testDoesNotComputeFingerprintForNonMatchingRules()
    {
        $path = "some/path.php";
        $rule = "nomatch";

        $fingerprintObject = new Fingerprint($path, $rule);
        $fingerprint = $fingerprintObject->compute();

        $this->assertEquals(NULL, $fingerprint);
    }
}
