<?php

use yii\db\Migration;

/**
 * Handles the creation of table `profile`.
 */
class m181121_141537_create_profile_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('profile', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'first_name'=>$this->string(),
            'last_name'=>$this->string(),
            'age'=>$this->date(),
            'photo'=>$this->string(),
            'photo_50'=>$this->string(),
            'photo_200'=>$this->string(),
            'about'=> $this->text(),
            'sex'=>$this->string(),
            'education'=>$this->string(),
        ]);

        $this->createIndex('idx-profile-user_id', '{{%profile}}', 'user_id');
        $this->addForeignKey('fk-profile-user_id', '{{%profile}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('profile');
    }
}
