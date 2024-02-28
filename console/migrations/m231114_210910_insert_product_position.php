<?php

use yii\db\Migration;

/**
 * Class m231114_210910_insert_product_position
 */
class m231114_210910_insert_product_position extends Migration
{

    public const TABLE_NAME = '{{%product_position}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->insert(
            self::TABLE_NAME,
            [
                'id' => 1,
                'name' => 'На дереве',
            ]
        );
        $this->insert(
            self::TABLE_NAME,
            [
                'id' => 2,
                'name' => 'На земле',
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
