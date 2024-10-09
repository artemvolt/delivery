<?php

declare(strict_types=1);

namespace app\commands;

use app\common\Core\Application\UseCases\Commands\MoveCouriers\MoveCouriersCommandDto;
use app\common\Core\Application\UseCases\Commands\MoveCouriers\MoveCouriersCommandHandlerInterface;
use yii\console\Controller;

final class MoveCouriersController extends Controller
{
    public function __construct(
        $id,
        $module,
        private readonly MoveCouriersCommandHandlerInterface $moveCouriersCommandHandler,
        $config = [])
    {
        parent::__construct($id, $module, $config);
    }

    public function actionMove()
    {
        $this->moveCouriersCommandHandler->handle(new MoveCouriersCommandDto());
    }
}