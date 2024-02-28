<?php

use yii\db\Migration;

/**
 * Class m231114_210834_create_tree_apple
 */
class m231114_210834_create_tree_apple extends Migration
{
    /**
     * {@inheritdoc}
     */
    public const TABLE_NAME = '{{%tree_apple}}';

    public function up()
    {

        $tableOptions = null;

        if ('mysql' === $this->db->driverName) {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'tree_id' => $this->integer()->notNull()->comment('Дерево'),
            'apple_id' => $this->integer()->null()->comment('Яблоко'),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ], $tableOptions);

        $this->createIndex('tree_id-tree_apple', self::TABLE_NAME, ['tree_id']);
        $this->createIndex('apple_id-tree_apple', self::TABLE_NAME, ['apple_id']);

        $this->addForeignKey(
            'tree_apple-tree_id-tree-id',
            self::TABLE_NAME,
            'tree_id',
            '{{%tree}}',
            'id',
            'RESTRICT',
            'NO ACTION'
        );

        $this->addForeignKey(
            'tree_apple-apple_id-apple-id',
            self::TABLE_NAME,
            'apple_id',
            '{{%apple}}',
            'id',
            'RESTRICT',
            'NO ACTION'
        );
    }

    public function down()
    {
        $this->dropForeignKey('tree_apple-tree_id-tree-id', self::TABLE_NAME);
        $this->dropForeignKey('tree_apple-apple_id-apple-id', self::TABLE_NAME);
        $this->dropTable(self::TABLE_NAME);
    }
}
