<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Async\Job;

use Verdient\Hyperf3\HttpAction\Async\AdapterInterface;
use Verdient\Hyperf3\HttpAction\Async\AsyncActionInterface;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                AsyncActionInterface::class => AsyncAction::class,
                AdapterInterface::class => JobAdapter::class
            ]
        ];
    }
}
