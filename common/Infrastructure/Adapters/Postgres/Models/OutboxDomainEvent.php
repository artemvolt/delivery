<?php

namespace app\common\Infrastructure\Adapters\Postgres\Models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "outbox_domain_events".
 *
 * @property int $id
 * @property string|null $event_class
 * @property string|null $event_body
 * @property int|null $status
 * @property string $ins_ts
 * @property string $upd_ts
 */
class OutboxDomainEvent extends ActiveRecord
{
    public const STATUS_NEW = 1;
    public const STATUS_PROCESSING = 2;
    public const STATUS_PERFORMED = 3;

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'ins_ts',
                'updatedAtAttribute' => 'upd_ts',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public static function tableName()
    {
        return 'outbox_domain_events';
    }

    public function rules()
    {
        return [
            [['status'], 'default', 'value' => null],
            [['status'], 'integer'],
            [['event_class'], 'string', 'max' => 255],
            [['event_body'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'event_class' => 'Event Class',
            'event_body' => 'Event Body',
            'status' => 'Status',
        ];
    }
}
