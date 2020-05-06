<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "flight".
 *
 * @property int $id
 * @property string|null $name
 * @property string $flight_datetime
 * @property int|null $amount_of_places
 * @property int|null $status
 * @property string|null $description
 *
 * @property Ticket[] $tickets
 */
class Flight extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'flight';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['flight_datetime'], 'safe'],
            [['amount_of_places', 'status'], 'integer'],
            [['name', 'description'], 'string', 'max' => 255],
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
            'flight_datetime' => 'Flight Datetime',
            'amount_of_places' => 'Amount Of Places',
            'status' => 'Status',
            'description' => 'Description',
        ];
    }

    /**
     * Gets query for [[Tickets]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTickets()
    {
        return $this->hasMany(Ticket::className(), ['flight_id' => 'id']);
    }

    public static function checkFlight($id)
    {
        $flight = Flight::findOne($id);
        if(!isset($flight)) return -1;
        else return $flight->status;
    }

    public static function changeFlightStatus($id, $status)
    {
        $flight = Flight::findOne($id);
        $flight->status = $status;
        $flight->save();
    }

    public static function flightStatus($flight_status)
    {
        if($flight_status == 1)
            return json_encode(['error' => 'свободных мест нет']);
        elseif ($flight_status == 2)
            return json_encode(['error' => 'продажа билетов на рейс завершена']);
        elseif ($flight_status == 3)
            return json_encode(['error' => 'рейс отменен']);
        else return json_encode(['error' => 'рейс не существует']);
    }
}
