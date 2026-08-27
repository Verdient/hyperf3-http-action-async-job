<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Async\Job;

use Override;
use Throwable;
use Verdient\Hyperf3\HttpAction\Async\AdapterInterface;
use Verdient\Hyperf3\HttpAction\Async\AsyncActionInterface;
use Verdient\Hyperf3\HttpAction\Async\Job\AsyncAction;
use Verdient\Job\AdapterInterface as JobAdapterInterface;

/**
 * Job适配器
 *
 * @author Verdient。
 */
class JobAdapter implements AdapterInterface
{
    /**
     * @param JobAdapterInterface $adapter Job适配器
     *
     * @author Verdient。
     */
    public function __construct(protected JobAdapterInterface $adapter) {}

    /**
     * @author Verdient。
     */
    #[Override]
    public function idleGap(): int|float
    {
        return $this->adapter->idleGap();
    }

    /**
     * @author Verdient。
     */
    #[Override]
    public function push(AsyncActionInterface $asyncAction): int|string|float|false
    {
        $job = new Job($asyncAction->class(), $asyncAction->params(), $asyncAction->properties());

        $job->setQueue($asyncAction->queue());

        return $this->adapter->push($job);
    }

    /**
     * 弹出任务
     *
     * @param string $queue 队列名称
     *
     * @author Verdient。
     */
    public function pop(string $queue): ?AsyncActionInterface
    {
        if ($job = $this->adapter->pop($queue)) {
            if ($job instanceof Job) {
                $asyncAction = new AsyncAction($job->class, $job->params, $job->properties, $job->queue());
                $asyncAction->setJob($job);
                return $asyncAction;
            }
        }
        return null;
    }

    /**
     * @author Verdient。
     */
    #[Override]
    public function commit(AsyncActionInterface $asyncAction, ?array $data): void
    {
        if ($asyncAction instanceof AsyncAction) {
            $this->adapter->commit($asyncAction->job(), $data);
        }
    }

    /**
     * @author Verdient。
     */
    #[Override]
    public function fault(AsyncActionInterface $asyncAction, Throwable $throwable): void
    {
        if ($asyncAction instanceof AsyncAction) {
            $this->adapter->fault($asyncAction->job(), $throwable);
        }
    }

    /**
     * @author Verdient。
     */
    #[Override]
    public function retry(AsyncActionInterface $asyncAction): void
    {
        if ($asyncAction instanceof AsyncAction) {
            $this->adapter->retry($asyncAction->job());
        }
    }
}
