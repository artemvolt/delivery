<?php

use yii\db\Migration;

/**
 * Class m240925_092029_courier_statuses
 */
class m240925_092029_courier_statuses extends Migration
{
    public function safeUp()
    {
        $this->createTable('courier_statuses', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
        ]);

        $this->batchInsert(
            'courier_statuses',
            ['id', 'name'],
            [
                [1, 'free'],
                [2, 'busy']
            ]
        );

        $this->addForeignKey(
            'fk_couriers__status_id',
            'couriers',
            'status_id',
            'courier_statuses',
            'id',
            'RESTRICT',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey(
            'fk_couriers__status_id',
            'couriers',
        );
        $this->dropTable('courier_statuses');
    }
}
