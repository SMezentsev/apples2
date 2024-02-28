<?php

use yii\db\Migration;

/**
 * Class m231114_210736_create_product_condition
 */
class m231114_210736_create_product_condition extends Migration
{

    public const TABLE_NAME = '{{%product_condition}}';

    /**
     * {@inheritdoc}
     */
    public function up()
    {

        $tableOptions = null;

        if ('mysql' === $this->db->driverName) {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->null()->comment('Наименование'),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
