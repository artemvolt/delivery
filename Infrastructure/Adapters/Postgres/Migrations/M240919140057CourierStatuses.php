<?php

declare(strict_types=1);

namespace Infrastructure\Adapters\Postgres\Migrations;

use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;
use Yiisoft\Db\Migration\TransactionalMigrationInterface;

/**
 * Class M240919140057CourierStatuses
 */
final class M240919140057CourierStatuses implements TransactionalMigrationInterface, RevertibleMigrationInterface
{
    public function up(MigrationBuilder $b): void
    {
        $b->createTable('courier_statuses', [
            'id' => $b->primaryKey(),
            'name' => $b->string(),
        ]);

        $b->batchInsert(
            'courier_statuses',
            ['id', 'name'],
            [
                [1, 'free'],
                [2, 'busy']
            ]
        );

        $b->addForeignKey(
            'couriers',
            'fk_couriers__status_id',
            'status_id',
            'courier_statuses',
            'id',
            'RESTRICT',
            'CASCADE'
        );
    }

    public function down(MigrationBuilder $b): void
    {
        $b->dropForeignKey(
            'couriers',
            'fk_couriers__status_id',
        );
        $b->dropTable('courier_statuses');
    }
}
