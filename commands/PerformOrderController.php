<?php

declare(strict_types=1);

namespace app\commands;

use app\common\Core\Application\UseCases\Commands\AssignedOrdersOnCouriers\AssignedOrdersOnCouriersCommandDto;
use app\common\Core\Application\UseCases\Commands\AssignedOrdersOnCouriers\AssignedOrdersOnCouriersCommandHandlerInterface;
use app\common\Core\Application\UseCases\Commands\MoveCouriers\MoveCouriersCommandDto;
use app\common\Core\Application\UseCases\Commands\MoveCouriers\MoveCouriersCommandHandlerInterface;
use DomainException;
use yii\console\Controller;

final class PerformOrderController extends Controller
{
    public function __construct(
        $id,
        $module,
        private readonly MoveCouriersCommandHandlerInterface $moveCouriersCommandHandler,
        private readonly AssignedOrdersOnCouriersCommandHandlerInterface $assignedOrdersOnCouriersCommandHandler,
        $config = [])
    {
        parent::__construct($id, $module, $config);
    }

    public function actionAssign()
    {
        while (true) {
            try {
                $this->assignedOrdersOnCouriersCommandHandler->handle(new AssignedOrdersOnCouriersCommandDto());
                echo time() . ' assign' . PHP_EOL;
            } catch (DomainException $e) {
                echo time() . ' domain exception: ' . $e->getMessage() . PHP_EOL;
            }
            sleep(5);
        }
    }

    public function actionMove()
    {
        while (true) {
            try {
                $this->moveCouriersCommandHandler->handle(new MoveCouriersCommandDto());
                echo time() . ' move.' . PHP_EOL;
            } catch (DomainException $e) {
                echo time() . ' domain exception: ' . $e->getMessage() . PHP_EOL;
            }
            sleep(5);
        }
    }
}