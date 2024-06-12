<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240609202404 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'This migration creates the picture table.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE picture (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', car_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, INDEX IDX_16DB4F89C3C6F69F (car_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT FK_16DB4F89C3C6F69F FOREIGN KEY (car_id) REFERENCES car (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE picture DROP FOREIGN KEY FK_16DB4F89C3C6F69F');
        $this->addSql('DROP TABLE picture');
    }
}
