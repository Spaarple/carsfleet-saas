<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240215201351 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'change is_driver to driver_id in borrow table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE borrow ADD driver_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', DROP is_driver');
        $this->addSql('ALTER TABLE borrow ADD CONSTRAINT FK_55DBA8B0C3423909 FOREIGN KEY (driver_id) REFERENCES user_employed (id)');
        $this->addSql('CREATE INDEX IDX_55DBA8B0C3423909 ON borrow (driver_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE borrow DROP FOREIGN KEY FK_55DBA8B0C3423909');
        $this->addSql('DROP INDEX IDX_55DBA8B0C3423909 ON borrow');
        $this->addSql('ALTER TABLE borrow ADD is_driver TINYINT(1) NOT NULL, DROP driver_id');
    }
}
