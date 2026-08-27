<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Async\Job;

use Verdient\Job\Exception\IgnoreException;

/**
 * 授权拒绝异常
 *
 * @author Verdient。
 */
class ApprovalRejectException extends IgnoreException {}
