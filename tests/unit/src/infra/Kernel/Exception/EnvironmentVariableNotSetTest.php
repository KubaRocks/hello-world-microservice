<?php

declare(strict_types=1);

namespace Tests\Unit\Infra\Kernel\Exception;

use Infra\Kernel\Exception\EnvironmentVariableNotSet;
use PHPUnit\Framework\TestCase;

final class EnvironmentVariableNotSetTest extends TestCase
{
    public function testItIncludesTheEnvironmentVarName(): void
    {
        $envVarKey = 'SOME_ENV_KEY';
        $exc = EnvironmentVariableNotSet::fromVarName($envVarKey);

        $this->assertStringContainsString($envVarKey, $exc->getMessage());
    }
}
