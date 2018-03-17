<?php
namespace softr\MakoQueue\migrations;

use mako\database\migrations\Migration;

class Migration_20170616122930 extends Migration
{
    /**
     * Description.
     *
     * @var string
     */
    protected $description = 'Create queue table';

    /**
     * Makes changes to the database structure.
     *
     * @access  public
     */
    public function up()
    {
        $this->database->connection()->query('CREATE TABLE `mako_queues` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `queue` varchar(255) NOT NULL,
            `data` text NOT NULL,
            `class` text NOT NULL,
            `created_at` int(11) unsigned NOT NULL,
            `failed_at` int(11) unsigned NOT NULL,
            `log` text NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
    }

    /**
     * Reverts the database changes.
     *
     * @access  public
     */
    public function down()
    {
        $this->database->connection()->query('DROP TABLE IF EXISTS `mako_queues`');
    }
}