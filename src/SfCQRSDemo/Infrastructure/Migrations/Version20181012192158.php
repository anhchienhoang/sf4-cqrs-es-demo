<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181012192158 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE `products` ADD COLUMN `images` json NULL DEFAULT NULL');

        $this->addSql(
            'CREATE TABLE `product_images` (
            `id` char(36) NOT NULL,
            `product_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
            `image` varchar(150) NOT NULL,
            PRIMARY KEY (`id`),
            KEY `product_idx` (`product_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
        );

    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE `products` DROP COLUMN `images`');
        $this->addSql('DROP TABLE IF EXISTS `product_images`');
    }
}
