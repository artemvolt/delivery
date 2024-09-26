<?php

namespace app\common\Infrastructure\Adapters\Postgres\Models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "transports".
 *
 * @property int $id
 * @property string|null $name
 * @property float|null $speed
 *
 * @property CourierModel[] $couriers
 */
class TransportModel extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transports';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['speed'], 'number'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'speed' => 'Speed',
        ];
    }

    /**
     * Gets query for [[Couriers]].
     *
     * @return ActiveQuery
     */
    public function getCouriers()
    {
        return $this->hasMany(CourierModel::class, ['transport_id' => 'id']);
    }
}
