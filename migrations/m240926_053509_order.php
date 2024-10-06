<?php

use yii\db\Migration;

/**
 * Class m240926_053509_order
 */
class m240926_053509_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('orders', [
            'location_x' => $this->integer(),
            'location_y' => $this->integer(),
            'status_id' => $this->integer(),
        ]);

        $this->addColumn('orders', 'courier_id', 'UUID');
        $this->addColumn('orders', 'id', 'UUID');
        $this->addPrimaryKey('pk_orders__id', 'orders', 'id');

        $this->addForeignKey(
            'fk_orders__courier_id',
            'orders',
            'courier_id',
            'couriers',
            'id',
            'RESTRICT',
            'CASCADE',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('orders');
    }
}
