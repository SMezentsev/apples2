<?php

namespace common\models;

use common\models\query\AppleQuery;
use Yii;

/**
 * This is the model class for table "apple".
 *
 * @property int $id
 * @property int $color Цвет
 * @property string $created_at Время создания
 *
 * @property AppleCondition[] $appleConditions
 * @property ApplePosition[] $applePositions
 * @property TreeApple[] $treeApples
 */
class Apple extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'apple';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['color'], 'required'],
            [['id', 'amount'], 'integer'],
            [['color'], 'string'],
            [['created_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'color' => 'Color',
            'amount' => 'Amount',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return AppleQuery
     */
    public static function find(): AppleQuery
    {
        return new AppleQuery(static::class);
    }

    /**
     * Gets query for [[AppleConditions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCondition()
    {
        return $this->hasOne(AppleCondition::class, ['apple_id' => 'id'])->orderBy(['id' => SORT_DESC]);
    }

    /**
     * Gets query for [[ApplePositions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPosition()
    {
        return $this->hasOne(ApplePosition::class, ['apple_id' => 'id'])->orderBy(['id' => SORT_DESC]);
    }

    /**
     * Gets query for [[TreeApples]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTreeApples()
    {
        return $this->hasMany(TreeApple::class, ['apple_id' => 'id']);
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
            'color',
            'amount',
            'created_at',
            'position' => static function ($model) {
                return $model->position;
            },
            'condition' => static function ($model) {
                return $model->condition;
            },
        ];

        return $fields;
    }
}
