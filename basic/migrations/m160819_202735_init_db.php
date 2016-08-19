<?php

use yii\db\Migration;

class m160819_202735_init_db extends Migration
{
    public function up()
    {
        $this->execute('SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO"');
        $this->execute('SET time_zone = "+00:00";');

        $this->execute('CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `xindx` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=30');

        $this->execute('CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `balance` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8');


        $this->execute('ALTER TABLE `comments` ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE');


        // set data
        $this->execute('INSERT INTO `comments` (`id`, `user_id`, `text`) VALUES
(1, 1, \'comment1\'),
(2, 1, \'comment2\'),
(3, 1, \'comment3\'),
(4, 1, \'comment4\'),
(5, 1, \'comment5\'),
(6, 1, \'comment6\'),
(7, 1, \'comment7\'),
(8, 1, \'comment8\'),
(9, 2, \'comment1\'),
(10, 2, \'comment2\'),
(11, 2, \'comment3\'),
(12, 2, \'comment4\'),
(13, 2, \'comment5\'),
(14, 2, \'comment6\'),
(15, 3, \'comment1\'),
(16, 3, \'comment2\'),
(17, 3, \'comment3\'),
(18, 4, \'comment1\'),
(19, 4, \'comment2\'),
(20, 4, \'comment3\'),
(21, 5, \'comment1\'),
(22, 5, \'comment2\'),
(23, 5, \'comment3\'),
(24, 6, \'comment1\'),
(25, 6, \'comment2\'),
(26, 6, \'comment3\'),
(27, 7, \'comment1\'),
(28, 7, \'comment2\'),
(29, 7, \'comment4\');');

        $this->execute('INSERT INTO `users` (`id`, `name`, `balance`) VALUES
(1, \'admin\', 7.45),
(2, \'test1\', 3.04),
(3, \'test2\', 0.01),
(4, \'test3\', 0.00),
(5, \'test4\', 0.00),
(6, \'test5\', 0.00),
(7, \'test6\', 0.00)');
    }

    public function down()
    {
        echo "m160819_202735_init_db cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
