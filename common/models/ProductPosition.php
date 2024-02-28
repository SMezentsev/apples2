<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product_position".
 *
 * @property int $id
 * @property string|null $name Наименование
 *
 * @property ApplePosition[] $applePositions
 */
class ProductPosition extends \yii\db\ActiveRecord
{

    public const ON_TREE = 1;
    public const ON_GROUND = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_position';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 50],
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
     * Gets query for [[ApplePositions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplePosition()
    {
        return $this->hasMany(ApplePosition::class, ['product_position_id' => 'id']);
    }

}
