<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240604101241 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Change date to datetime in borrow table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE borrow CHANGE start_date start_date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE end_date end_date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE borrow CHANGE start_date start_date DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', CHANGE end_date end_date DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\'');
    }
}
