<?php

use yii\db\Migration;

/**
 * Class m231114_210918_insert_product_condition
 */
class m231114_210918_insert_product_condition extends Migration
{

    public const TABLE_NAME = '{{%product_condition}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->insert(
            self::TABLE_NAME,
            [
                'id' => 1,
                'name' => 'Свежее',
            ]
        );
        $this->insert(
            self::TABLE_NAME,
            [
                'id' => 2,
                'name' => 'Гнилое',
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
