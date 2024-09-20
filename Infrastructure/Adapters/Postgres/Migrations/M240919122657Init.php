<?php

declare(strict_types=1);

namespace Infrastructure\Adapters\Postgres\Migrations;

use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;
use Yiisoft\Db\Migration\TransactionalMigrationInterface;

/**
 * Class M240919122657Init
 */
final class M240919122657Init implements TransactionalMigrationInterface, RevertibleMigrationInterface
{
    private const TABLE_NAME = 'couriers';

    public function up(MigrationBuilder $b): void
    {
        $b->createTable(self::TABLE_NAME, [
            'id' => $b->uuidPrimaryKey(),
            'name' => $b->string(),
            'transport_id' => $b->integer(),
            'location_x' => $b->integer(),
            'location_y' => $b->integer(),
            'status_id' => $b->integer(),
         ]);

        $b->batchInsert(self::TABLE_NAME, [
            'id', 'name', 'transport_id', 'location_x', 'location_y', 'status_id'
        ], [
            ['bf79a004-56d7-4e5f-a21c-0a9e5e08d10d', 'Пеший 1', 1, 1, 3, 2],
            ['a9f7e4aa-becc-40ff-b691-f063c5d04015', 'Пеший 2', 1, 3,2, 2],
            ['db18375d-59a7-49d1-bd96-a1738adcee93', 'Вело 1', 2, 4,5, 2],
            ['e7c84de4-3261-476a-9481-fb6be211de75', 'Вело 2', 2, 1,8, 2],
            ['407f68be-5adf-4e72-81bc-b1d8e9574cf8', 'Авто 1', 3, 7,9, 2],
            ['006e6c66-087e-4a27-aa59-3c0a2bc945c5', 'Авто 1', 3, 5,5, 2],
        ]);
    }

    public function down(MigrationBuilder $b): void
    {
        $b->dropTable(self::TABLE_NAME);
    }
}
