<?php

declare(strict_types=1);

namespace Infrastructure\Adapters\Postgres\Migrations;

use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;
use Yiisoft\Db\Migration\TransactionalMigrationInterface;

/**
 * Class M240919140329Transport
 */
final class M240919140329Transport implements TransactionalMigrationInterface, RevertibleMigrationInterface
{
    public function up(MigrationBuilder $b): void
    {
        $b->createTable('transports', [
            'id' => $b->primaryKey(),
            'name' => $b->string(),
            'speed' => $b->float(),
        ]);

        $b->batchInsert('transports', [
            'id', 'name', 'speed'
        ], [
            [1, 'pedestrian', 1],
            [2, 'bicycle', 2],
            [3, 'car', 3],
        ]);

        $b->addForeignKey(
            'couriers',
            'fk_couriers__transport_id',
            'transport_id',
            'transports',
            'id',
            'RESTRICT',
            'CASCADE'
        );
    }

    public function down(MigrationBuilder $b): void
    {
        $b->dropForeignKey(
            'couriers',
            'fk_couriers__transport_id',
        );
        $b->dropTable('transports');
    }
}
