<?php

use yii\db\Migration;

/**
 * Class m231114_210846_create_apple_condition
 */
class m231114_210846_create_apple_condition extends Migration
{
    /**
     * {@inheritdoc}
     */
    public const TABLE_NAME = '{{%apple_condition}}';

    public function up()
    {

        $tableOptions = null;

        if ('mysql' === $this->db->driverName) {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'apple_id' => $this->integer()->null()->comment('ID яблока'),
            'product_condition_id' => $this->integer()->notNull()->comment('Состояние продукта'),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->comment('Время создания'),
        ], $tableOptions);

        $this->createIndex('apple_id-apple_condition', self::TABLE_NAME, ['apple_id']);
        $this->createIndex('product_condition_id-apple_condition', self::TABLE_NAME, ['product_condition_id']);

        $this->addForeignKey(
            'apple_condition-apple_id-apple-id',
            self::TABLE_NAME,
            'apple_id',
            '{{%apple}}',
            'id',
            'RESTRICT',
            'NO ACTION'
        );

        $this->addForeignKey(
            'apple_condition-product_condition_id-product_condition-id',
            self::TABLE_NAME,
            'product_condition_id',
            '{{%product_condition}}',
            'id',
            'RESTRICT',
            'NO ACTION'
        );
    }

    public function down()
    {
        $this->dropForeignKey('apple_condition-apple_id-apple-id', self::TABLE_NAME);
        $this->dropForeignKey('apple_condition-product_condition_id-product_condition-id', self::TABLE_NAME);
        $this->dropTable(self::TABLE_NAME);
    }
}
