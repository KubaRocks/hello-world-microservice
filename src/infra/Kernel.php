<?php

declare(strict_types=1);

namespace Infra;

use Infra\Kernel\Exception\EnvironmentVariableNotSet;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

final class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    /**
     * @inheritDoc
     */
    public function getProjectDir(): string
    {
        return dirname(__DIR__, 2);
    }

    /**
     * @inheritDoc
     */
    public function getLogDir(): string
    {
        return $this->getEnvVar('APP_LOG_DIR');
    }

    /**
     * @inheritDoc
     * @return non-empty-array<array-key, mixed>
     */
    protected function getKernelParameters(): array
    {
        $params =  parent::getKernelParameters();
        $params += [
            'kernel.api_dir' => sprintf('%s/%s', $this->getProjectDir(), 'src/api'),
            'kernel.app_dir' => sprintf('%s/%s', $this->getProjectDir(), 'src/app'),
            'kernel.infra_dir' => sprintf('%s/%s', $this->getProjectDir(), 'src/infra'),
            'kernel.domain_dir' => sprintf('%s/%s', $this->getProjectDir(), 'src/domain'),
        ];

        return $params;
    }

    /**
     * @param string $varName
     * @return string
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    private function getEnvVar(string $varName): string
    {
        if (!isset($_ENV[$varName]) || !$var = $_ENV[$varName]) {
            throw EnvironmentVariableNotSet::fromVarName($varName);
        }

        return (string) $var;
    }
}
