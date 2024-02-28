<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "apple_condition".
 *
 * @property int $id
 * @property int|null $apple_id ID яблока
 * @property int $product_condition_id Состояние продукта
 * @property string $created_at Время создания
 *
 * @property Apple $apple
 * @property ProductCondition $productCondition
 */
class AppleCondition extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'apple_condition';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['apple_id', 'product_condition_id'], 'integer'],
            [['product_condition_id'], 'required'],
            [['created_at'], 'safe'],
            [['apple_id'], 'exist', 'skipOnError' => true, 'targetClass' => Apple::class, 'targetAttribute' => ['apple_id' => 'id']],
            [['product_condition_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductCondition::class, 'targetAttribute' => ['product_condition_id' => 'id']],
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
            'product_condition_id' => 'Product Condition ID',
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
     * Gets query for [[ProductCondition]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductCondition()
    {
        return $this->hasOne(ProductCondition::class, ['id' => 'product_condition_id']);
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
                return $model->productCondition->name;
            },
        ];

        return $fields;
    }
}
