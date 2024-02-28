<?php

use yii\db\Migration;

/**
 * Class m231114_210857_create_apple_position
 */
class m231114_210857_create_apple_position extends Migration
{
    /**
     * {@inheritdoc}
     */
    public const TABLE_NAME = '{{%apple_position}}';

    public function up()
    {

        $tableOptions = null;

        if ('mysql' === $this->db->driverName) {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'apple_id' => $this->integer()->null()->comment('ID яблока'),
            'product_position_id' => $this->integer()->notNull()->comment('Позиция продукта'),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->comment('Время'),
        ], $tableOptions);

        $this->createIndex('apple_id-apple_position', self::TABLE_NAME, ['apple_id']);
        $this->createIndex('product_position_id-apple_position', self::TABLE_NAME, ['product_position_id']);

        $this->addForeignKey(
            'apple_position-apple_id-apple-id',
            self::TABLE_NAME,
            'apple_id',
            '{{%apple}}',
            'id',
            'RESTRICT',
            'NO ACTION'
        );

        $this->addForeignKey(
            'apple_position-product_position_id-product_position-id',
            self::TABLE_NAME,
            'product_position_id',
            '{{%product_position}}',
            'id',
            'RESTRICT',
            'NO ACTION'
        );
    }

    public function down()
    {
        $this->dropForeignKey('apple_position-apple_id-apple-id', self::TABLE_NAME);
        $this->dropForeignKey('apple_position-product_position_id-product_position-id', self::TABLE_NAME);
        $this->dropTable(self::TABLE_NAME);
    }
}
