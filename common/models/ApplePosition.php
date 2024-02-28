<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "apple_position".
 *
 * @property int $id
 * @property int|null $apple_id ID яблока
 * @property int $product_position_id Позиция продукта
 * @property string $created_at Время
 *
 * @property Apple $apple
 * @property ProductPosition $productPosition
 */
class ApplePosition extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'apple_position';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['apple_id', 'product_position_id'], 'integer'],
            [['product_position_id'], 'required'],
            [['created_at'], 'safe'],
            [['apple_id'], 'exist', 'skipOnError' => true, 'targetClass' => Apple::class, 'targetAttribute' => ['apple_id' => 'id']],
            [['product_position_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductPosition::class, 'targetAttribute' => ['product_position_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'apple_id' => 'Apple ID',
            'product_position_id' => 'Product Position ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Apple]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApple()
    {
        return $this->hasOne(Apple::class, ['id' => 'apple_id']);
    }

    /**
     * Gets query for [[ProductPosition]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductPosition()
    {
        return $this->hasOne(ProductPosition::class, ['id' => 'product_position_id']);
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function fields()
    {
        $fields = [
            'id',
            'name' => static function ($model) {
                return $model->productPosition->name;
            },
        ];

        return $fields;
    }
}
