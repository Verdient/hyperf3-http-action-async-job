<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Async\Job;

use Override;
use Verdient\Job\Exception\IgnoreException;

/**
 * 授权等待异常
 *
 * @author Verdient。
 */
class ApprovalPendingException extends IgnoreException
{
    /**
     * @param int $delaySeconds 延迟的秒数
     *
     * @author Verdient。
     */
    public function __construct(public readonly int $delaySeconds)
    {
        $message = 'The approval for the async action has not yet been completed. It will be retried in ' . $delaySeconds . ' seconds.';

        parent::__construct($message);
    }

    /**
     * @author Verdient。
     */
    #[Override]
    public function __toString(): string
    {
        return $this->getMessage();
    }
}
