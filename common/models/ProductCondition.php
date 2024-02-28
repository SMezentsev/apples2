<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product_condition".
 *
 * @property int $id
 * @property int|null $name Наименование
 *
 * @property AppleCondition[] $appleConditions
 */
class ProductCondition extends \yii\db\ActiveRecord
{

    public const PRODUCT_FRESH = 1;
    public const PRODUCT_ROTTEN = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_condition';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string'],
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
     * Gets query for [[AppleConditions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAppleConditions()
    {
        return $this->hasMany(AppleCondition::class, ['product_condition_id' => 'id']);
    }
}
