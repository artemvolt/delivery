<?php

use yii\db\Migration;

/**
 * Class m240925_091804_init
 */
class m240925_091804_init extends Migration
{
    private const TABLE_NAME = 'couriers';

    public function safeUp()
    {
        $this->createTable(self::TABLE_NAME, [
            'name' => $this->string(),
            'transport_id' => $this->integer(),
            'location_x' => $this->integer(),
            'location_y' => $this->integer(),
            'status_id' => $this->integer(),
        ]);

        $this->addColumn(self::TABLE_NAME, 'id', 'UUID not null');

        $this->addPrimaryKey(
            'pk_couriers__uuid',
            self::TABLE_NAME,
            'id'
        );

        $this->batchInsert(self::TABLE_NAME, [
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

    public function safeDown()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
