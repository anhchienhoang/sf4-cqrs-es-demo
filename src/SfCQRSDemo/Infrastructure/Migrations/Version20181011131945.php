<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181011131945 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql(
            'CREATE TABLE `events` (
            `id` bigint(20) NOT NULL AUTO_INCREMENT,
            `aggregate_id` char(36) COLLATE utf8_bin NOT NULL,
            `event_name` varchar(100) COLLATE utf8_bin NOT NULL,
            `payload` json NOT NULL,
            `created_at` datetime(6) NOT NULL,
            PRIMARY KEY (`id`),
            KEY `aggregate_idx` (`aggregate_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
        );

        $this->addSql(
            'CREATE TABLE `products` (
            `id` char(36) NOT NULL,
            `name` varchar(150) NOT NULL,
            `price` decimal(10,2) NOT NULL,
            `description` text NOT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
        );
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP TABLE IF EXISTS `events`');
        $this->addSql('DROP TABLE IF EXISTS `products`');
    }
}
