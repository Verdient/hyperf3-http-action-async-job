<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Async\Job;

use Override;
use Verdient\Hyperf3\HttpAction\Async\AsyncActionInterface;
use Verdient\Job\JobInterface;

/**
 * 异步动作
 *
 * @author Verdient。
 */
class AsyncAction implements AsyncActionInterface
{
    /**
     * Job
     *
     * @author Verdient。
     */
    protected ?JobInterface $job = null;

    /**
     * @param string $class 类名
     * @param ?array $params 参数
     * @param ?array $properties 属性
     * @param ?string $queue 队列
     *
     * @author Verdient。
     */
    public function __construct(
        protected string $class,
        protected ?array $params,
        protected ?array $properties,
        protected ?string $queue
    ) {}

    /**
     * @author Verdient。
     */
    #[Override]
    public static function create(string $class, ?array $params, ?array $properties, ?string $queue = 'default'): static
    {
        return new static($class, $params, $properties, $queue);
    }

    /**
     * @author Verdient。
     */
    #[Override]
    public function class(): string
    {
        return $this->class;
    }

    /**
     * @author Verdient。
     */
    #[Override]
    public function params(): ?array
    {
        return $this->params;
    }

    /**
     * @author Verdient。
     */
    #[Override]
    public function properties(): ?array
    {
        return $this->properties;
    }

    /**
     * @author Verdient。
     */
    #[Override]
    public function queue(): string
    {
        return $this->queue;
    }

    /**
     * 设置Job
     *
     * @param JobInterface $job 任务
     *
     * @author Verdient。
     */
    public function setJob(JobInterface $job): static
    {
        $this->job = $job;
        return $this;
    }

    /**
     * 获取Job
     *
     * @author Verdient。
     */
    public function job(): ?JobInterface
    {
        return $this->job;
    }
}
