<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Async\Job;

use Hyperf\Contract\Arrayable;
use Override;
use Throwable;
use Verdient\Hyperf3\HttpAction\Async\ApprovalInterface;
use Verdient\Hyperf3\HttpAction\Async\Job\ApprovalPendingException;
use Verdient\Hyperf3\HttpAction\Async\Job\ApprovalRejectException;;
use Verdient\Hyperf3\HttpAction\Utils;
use Verdient\Hyperf3\Job\AbstractJob;

/**
 * 异步动作任务
 *
 * @author Verdient。
 */
class Job extends AbstractJob
{
    /**
     * 队列
     *
     * @author Verdient。
     */
    protected string $queue = 'default';

    /**
     * @param string $class 类名
     * @param ?array $params 参数
     * @param ?array $properties 属性
     *
     * @author Verdient。
     */
    public function __construct(
        public readonly string $class,
        public readonly ?array $params = null,
        public readonly ?array $properties = null
    ) {}

    /**
     * @author Verdient。
     */
    #[Override]
    public function queue(): string
    {
        return $this->queue;
    }

    /**
     * 设置队列名称
     *
     * @param string $queue 队列名称
     *
     * @author Verdient。
     */
    public function setQueue(string $queue): static
    {
        $this->queue = $queue;
        return $this;
    }

    /**
     * @author Verdient。
     */
    #[Override]
    public function handle(): ?array
    {
        $action = Utils::createActionFromAsyncAction(new AsyncAction($this->class, $this->params, $this->properties, $this->queue));

        if ($action instanceof ApprovalInterface) {
            $approval = $action->approval();

            if ($approval === false) {
                throw new ApprovalRejectException();
            } else if (is_int($approval)) {
                throw $approval >= 0 ? new ApprovalPendingException($approval) : new ApprovalRejectException();
            }
        }

        $result = $action->handle();

        if ($result instanceof Arrayable) {
            $result = $result->toArray();
        } else {
            $result = null;
        }

        return $result;
    }

    /**
     * @author Verdient。
     */
    #[Override]
    public function retriable(Throwable $throwable): bool|int
    {
        if ($throwable instanceof ApprovalPendingException) {
            return $throwable->delaySeconds;
        }

        return parent::retriable($throwable);
    }
}
