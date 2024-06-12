<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240604111615 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add fields to car entity';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE car ADD fuel VARCHAR(255) NOT NULL, ADD year_of_production DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', ADD kilometers INT NOT NULL, ADD circulation_date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', ADD fiscal_horse_power SMALLINT NOT NULL, ADD horse_power SMALLINT NOT NULL, ADD gearbox VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE car DROP fuel, DROP year_of_production, DROP kilometers, DROP circulation_date, DROP fiscal_horse_power, DROP horse_power, DROP gearbox');
    }
}
