<?php

namespace Tests;

use \PHPUnit\Framework\TestCase;

class BaseTestCase extends TestCase
{
    public function assertQuery(array $expected, array $actual)
    {
        $jsonExpected = json_encode($expected);
        $jsonActual = json_encode($actual);

        if ($jsonExpected === false || $jsonActual === false) {
            return false;
        }

        $this->assertJsonStringEqualsJsonString(
            $jsonExpected,
            $jsonActual
        );
    }
}
