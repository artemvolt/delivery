<?php

use yii\db\Migration;

class m241011_201341_outbox extends Migration
{
    public function safeUp()
    {
        $this->createTable('outbox_domain_events', [
            'id' => $this->primaryKey(),
            'event_class' => $this->string(),
            'event_body' => $this->text(),
            'status' => $this->integer(),
            'ins_ts' => $this->dateTime(),
            'upd_ts' => $this->dateTime(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('outbox_domain_events');
    }
}
