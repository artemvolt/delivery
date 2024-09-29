<?php

declare(strict_types=1);

namespace app\common\Infrastructure\Adapters\Postgres\Models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "couriers".
 *
 * @property string|null $name
 * @property int|null $transport_id
 * @property int|null $location_x
 * @property int|null $location_y
 * @property int|null $status_id
 * @property string $id
 *
 * @property CourierStatusModel $status
 * @property TransportModel $transport
 */
class CourierModel extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'couriers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['transport_id', 'location_x', 'location_y', 'status_id'], 'default', 'value' => null],
            [['transport_id', 'location_x', 'location_y', 'status_id'], 'integer'],
            [['id'], 'required'],
            [['id'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['id'], 'unique'],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => CourierStatusModel::class, 'targetAttribute' => ['status_id' => 'id']],
            [['transport_id'], 'exist', 'skipOnError' => true, 'targetClass' => TransportModel::class, 'targetAttribute' => ['transport_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'transport_id' => 'Transport ID',
            'location_x' => 'Location X',
            'location_y' => 'Location Y',
            'status_id' => 'Status ID',
            'id' => 'ID',
        ];
    }

    /**
     * Gets query for [[Status]].
     *
     * @return ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(CourierStatusModel::class, ['id' => 'status_id']);
    }

    /**
     * Gets query for [[Transport]].
     *
     * @return ActiveQuery
     */
    public function getTransport()
    {
        return $this->hasOne(TransportModel::class, ['id' => 'transport_id']);
    }
}
