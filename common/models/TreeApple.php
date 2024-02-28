<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tree_apple".
 *
 * @property int $id
 * @property int $tree_id Дерево
 * @property int|null $apple_id Яблоко
 * @property string $created_at
 *
 * @property Apple $apple
 * @property Tree $tree
 */
class TreeApple extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tree_apple';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tree_id'], 'required'],
            [['tree_id', 'apple_id'], 'integer'],
            [['created_at'], 'safe'],
            [['apple_id'], 'exist', 'skipOnError' => true, 'targetClass' => Apple::class, 'targetAttribute' => ['apple_id' => 'id']],
            [['tree_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tree::class, 'targetAttribute' => ['tree_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tree_id' => 'Tree ID',
            'apple_id' => 'Apple ID',
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
     * Gets query for [[Tree]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTree()
    {
        return $this->hasOne(Tree::class, ['id' => 'tree_id']);
    }
}
