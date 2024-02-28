<?php

use yii\db\Migration;

/**
 * Class m231114_210813_create_apple
 */
class m231114_210813_create_apple extends Migration
{

    public const TABLE_NAME = '{{%apple}}';

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
            'color' => $this->string(10)->notNull()->comment('Цвет'),
            'amount' => $this->integer(10)->null()->defaultValue(100)->comment('Количество яблока, %'),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->comment('Время создания'),
            'deleted_at' => $this->timestamp()->null()->defaultValue(null)->comment('Время создания'),
        ], $tableOptions);
    }

    public function down()
    {

        $this->dropTable(self::TABLE_NAME);
    }
}
