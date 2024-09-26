<?php

declare(strict_types=1);

namespace app\common\Infrastructure\Adapters\Postgres\Models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "orders".
 *
 * @property int $id
 * @property int|null $location_x
 * @property int|null $location_y
 * @property int|null $status_id
 * @property string|null $courier_id
 *
 * @property CourierModel $courier
 */
class OrderModel extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['location_x', 'location_y', 'status_id'], 'default', 'value' => null],
            [['location_x', 'location_y', 'status_id'], 'integer'],
            [['courier_id'], 'string'],
            [['courier_id'], 'exist', 'skipOnError' => true, 'targetClass' => CourierModel::class, 'targetAttribute' => ['courier_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'location_x' => 'Location X',
            'location_y' => 'Location Y',
            'status_id' => 'Status ID',
            'courier_id' => 'Courier ID',
        ];
    }

    /**
     * Gets query for [[Courier]].
     *
     * @return ActiveQuery
     */
    public function getCourier()
    {
        return $this->hasOne(CourierModel::class, ['id' => 'courier_id']);
    }
}
