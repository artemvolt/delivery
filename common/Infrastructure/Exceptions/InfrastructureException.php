<?php

declare(strict_types=1);

namespace app\common\Infrastructure\Exceptions;

use RuntimeException;

final class InfrastructureException extends RuntimeException implements InfrastructureExceptionInterface
{
}