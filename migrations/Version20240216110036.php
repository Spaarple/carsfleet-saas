<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240216110036 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add status column to key table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `key` ADD status VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `key` DROP status');
    }
}
