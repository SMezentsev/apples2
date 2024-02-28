<?php

namespace common\models;

use Yii;
use \common\models\Apple;

/**
 * This is the model class for table "tree".
 *
 * @property int $id
 * @property int $color Цвет
 * @property string $created_at Время создания
 *
 * @property TreeApple[] $treeApples
 */
class Tree extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tree';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
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
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[TreeApples]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTreeApples()
    {

        return $this->hasMany(TreeApple::class, ['tree_id' => 'id']);
    }

    /**
     * Gets query for [[Apples]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApples()
    {

        return $this
            ->hasMany(Apple::class, ['id' => 'apple_id'])
            ->via('treeApples')
            ->andWhere('apple.deleted_at is NULL');
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function fields()
    {
        $fields = [
            'id' => 'id',
            'apples' => static function ($model) {
                return $model->apples;
            },
        ];

        return $fields;
    }

}
