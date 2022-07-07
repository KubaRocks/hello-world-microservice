<?php

declare(strict_types=1);

namespace Tests\Utils;

final class Constants
{
    public static function getRootDir(): string
    {
        return dirname(__DIR__, 3);
    }

    /**
     * @return string
     */
    public static function fixturesDir(): string
    {
        return dirname(__DIR__, 2) . '/fixtures';
    }
}
