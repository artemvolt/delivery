<?php

declare(strict_types=1);

namespace app\common\Infrastructure\Adapters\Postgres\Models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "courier_statuses".
 *
 * @property int $id
 * @property string|null $name
 *
 * @property CourierModel[] $couriers
 */
class CourierStatusModel extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'courier_statuses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
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
        ];
    }

    /**
     * Gets query for [[Couriers]].
     *
     * @return ActiveQuery
     */
    public function getCouriers()
    {
        return $this->hasMany(CourierModel::class, ['status_id' => 'id']);
    }
}
