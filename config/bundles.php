<?php

declare(strict_types=1);

return [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
    FriendsOfBehat\SymfonyExtension\Bundle\FriendsOfBehatSymfonyExtensionBundle::class => [
        'dev' => true,
        'test' => true
    ],
    Cushon\HealthBundle\CushonHealthBundle::class => ['all' => true],
];
