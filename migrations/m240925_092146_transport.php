<?php

use yii\db\Migration;

/**
 * Class m240925_092146_transport
 */
class m240925_092146_transport extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('transports', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'speed' => $this->float(),
        ]);

        $this->batchInsert('transports', [
            'id', 'name', 'speed'
        ], [
            [1, 'pedestrian', 1],
            [2, 'bicycle', 2],
            [3, 'car', 3],
        ]);

        $this->addForeignKey(
            'fk_couriers__transport_id',
            'couriers',
            'transport_id',
            'transports',
            'id',
            'RESTRICT',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk_couriers__transport_id',
            'couriers',
        );
        $this->dropTable('transports');
    }
}
