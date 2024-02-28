<?php

use yii\db\Migration;

/**
 * Class m231114_205651_insert_user
 */
class m231114_205651_insert_user extends Migration
{

    public const TABLE_NAME = '{{%user}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        // admin
        // admin
        $this->insert(
            self::TABLE_NAME,
            [
                'username' => 'admin',
                'auth_key' => 'oy2TPoNQ62KtO1MGtoAEhsG8B3oiZF2',
                'password_hash' => '$2y$13$Rq3I2qUtmZbKlSeOa8BE7.oDhIFxzoR7Cw1hMxdPlLGH74Mi7nHOu',
                'password_reset_token' => '$KgsjTYX6ksb3GH59dGRbqeEdpI6rweLg0z4bkoSAXGzGdWVFCWeuC',
                'email' => 'admin@mail.ru',
                'created_at' => '1392559490',
                'updated_at' => '1392559490'
            ]
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m231114_205651_insert_user cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231114_205651_insert_user cannot be reverted.\n";

        return false;
    }
    */
}
