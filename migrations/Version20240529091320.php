<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240529091320 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'New role';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_administrator_head_office (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', head_office_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_C305F6007FAF4F07 (head_office_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_administrator_site (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', site_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_4C8D83F2F6BD1646 (site_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_administrator_head_office ADD CONSTRAINT FK_C305F6007FAF4F07 FOREIGN KEY (head_office_id) REFERENCES head_office (id)');
        $this->addSql('ALTER TABLE user_administrator_head_office ADD CONSTRAINT FK_C305F600BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_administrator_site ADD CONSTRAINT FK_4C8D83F2F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)');
        $this->addSql('ALTER TABLE user_administrator_site ADD CONSTRAINT FK_4C8D83F2BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_administrator DROP FOREIGN KEY FK_B651AB01BF396750');
        $this->addSql('ALTER TABLE user_administrator DROP FOREIGN KEY FK_B651AB01F6BD1646');
        $this->addSql('DROP TABLE user_administrator');
        $this->addSql('ALTER TABLE user_super_administrator DROP FOREIGN KEY FK_A983BE4A7FAF4F07');
        $this->addSql('DROP INDEX IDX_A983BE4A7FAF4F07 ON user_super_administrator');
        $this->addSql('ALTER TABLE user_super_administrator DROP head_office_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_administrator (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', site_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_B651AB01F6BD1646 (site_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user_administrator ADD CONSTRAINT FK_B651AB01BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_administrator ADD CONSTRAINT FK_B651AB01F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)');
        $this->addSql('ALTER TABLE user_administrator_head_office DROP FOREIGN KEY FK_C305F6007FAF4F07');
        $this->addSql('ALTER TABLE user_administrator_head_office DROP FOREIGN KEY FK_C305F600BF396750');
        $this->addSql('ALTER TABLE user_administrator_site DROP FOREIGN KEY FK_4C8D83F2F6BD1646');
        $this->addSql('ALTER TABLE user_administrator_site DROP FOREIGN KEY FK_4C8D83F2BF396750');
        $this->addSql('DROP TABLE user_administrator_head_office');
        $this->addSql('DROP TABLE user_administrator_site');
        $this->addSql('ALTER TABLE user_super_administrator ADD head_office_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE user_super_administrator ADD CONSTRAINT FK_A983BE4A7FAF4F07 FOREIGN KEY (head_office_id) REFERENCES head_office (id)');
        $this->addSql('CREATE INDEX IDX_A983BE4A7FAF4F07 ON user_super_administrator (head_office_id)');
    }
}
