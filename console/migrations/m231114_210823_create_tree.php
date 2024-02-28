<?php

use yii\db\Migration;

/**
 * Class m231114_210823_create_tree
 */
class m231114_210823_create_tree extends Migration
{

    public const TABLE_NAME = '{{%tree}}';

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
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->comment('Время создания'),
        ], $tableOptions);
    }

    public function down()
    {

        $this->dropTable(self::TABLE_NAME);
    }
}
