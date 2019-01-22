<?php

use yii\db\Migration;

/**
 * Handles the creation of table `usertoken`.
 */
class m181023_182005_create_usertoken_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('usertoken', [
          'id' => $this->primaryKey(),
         'user_id' => $this->integer()->notNull(),
         'token' => $this->string()->notNull()->unique(),
         'expired_at' => $this->integer()->notNull(),
        ]);

       $this->createIndex('idx-token-user_id', '{{%usertoken}}', 'user_id');
       $this->addForeignKey('fk-token-user_id', '{{%usertoken}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('usertoken');
    }
}
