<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240216092453 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Key entity creation with car relation.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE `key` (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', car_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_8A90ABA9C3C6F69F (car_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `key` ADD CONSTRAINT FK_8A90ABA9C3C6F69F FOREIGN KEY (car_id) REFERENCES car (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `key` DROP FOREIGN KEY FK_8A90ABA9C3C6F69F');
        $this->addSql('DROP TABLE `key`');
    }
}
