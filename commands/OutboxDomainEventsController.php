<?php
declare(strict_types=1);

namespace app\commands;

use app\common\Infrastructure\Adapters\Postgres\BackgroundJobs\DomainEventsPublish\DomainEventsPublisher;
use app\common\Infrastructure\Exceptions\InfrastructureException;
use app\common\Infrastructure\Exceptions\InfrastructureExceptionInterface;
use yii\console\Controller;

final class OutboxDomainEventsController extends Controller
{
    public function __construct(
        $id,
        $module,
        private readonly DomainEventsPublisher $domainEventsPublisher,
        $config = [])
    {
        parent::__construct($id, $module, $config);
    }

    public function actionIndex()
    {
        while (true) {
            try {
                $this->domainEventsPublisher->publish();
                echo time() . '. publish' . PHP_EOL;
            } catch (InfrastructureExceptionInterface $e) {
                echo 'infrastructure exception: ' . $e->getMessage() . PHP_EOL;
            }
            sleep(5);
        }
    }
}