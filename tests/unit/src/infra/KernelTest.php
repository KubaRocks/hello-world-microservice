<?php

declare(strict_types=1);

namespace Tests\Unit\Infra;

use Infra\Kernel;
use Infra\Kernel\Exception\EnvironmentVariableNotSet;
use PHPUnit\Framework\TestCase;
use Tests\Utils\Constants;

/**
 * @backupGlobals enabled
 */
final class KernelTest extends TestCase
{
    public function testItReturnsTheProjectDir(): void
    {
        $kernel = new Kernel($_SERVER['APP_ENV'], true);
        $this->assertSame(Constants::getRootDir(), $kernel->getProjectDir());
    }

    public function testItThrowsAnExceptionIfTheEnvironmentVariableIsNotSet(): void
    {
        $varName = 'APP_LOG_DIR';
        unset($_ENV[$varName]);
        $kernel = new Kernel($_SERVER['APP_ENV'], true);
        $this->expectExceptionObject(
            EnvironmentVariableNotSet::fromVarName($varName)
        );

        $kernel->getLogDir();
    }

    public function testItReturnsTheLogDir(): void
    {
        $appLogDir = 'wibble';
        $_ENV['APP_LOG_DIR'] = $appLogDir;
        $kernel = new Kernel($_SERVER['APP_ENV'], true);

        $this->assertSame($appLogDir, $kernel->getLogDir());
    }
}
