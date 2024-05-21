<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240208180107 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Initial migration for the project.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE accident (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', car_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', user_employed_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', description LONGTEXT NOT NULL, date DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', INDEX IDX_8F31DB6FC3C6F69F (car_id), INDEX IDX_8F31DB6F9DA45FAC (user_employed_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE borrow (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', car_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', borrow_meet_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', start_date DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', end_date DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', is_driver TINYINT(1) NOT NULL, INDEX IDX_55DBA8B0C3C6F69F (car_id), INDEX IDX_55DBA8B05D4BB9E2 (borrow_meet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE borrow_user_employed (borrow_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', user_employed_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_20301744D4C006C8 (borrow_id), INDEX IDX_203017449DA45FAC (user_employed_id), PRIMARY KEY(borrow_id, user_employed_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE borrow_meet (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', site_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_6ADBE9EBF6BD1646 (site_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE car (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', site_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', serial_number VARCHAR(255) NOT NULL, brand VARCHAR(255) NOT NULL, model VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, passenger_quantity SMALLINT NOT NULL, status VARCHAR(255) NOT NULL, registration_number VARCHAR(255) NOT NULL, INDEX IDX_773DE69DF6BD1646 (site_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE head_office (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', address VARCHAR(255) NOT NULL, postal_code VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, region VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', user_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE site (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', head_office_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, postal_code VARCHAR(255) NOT NULL, region VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, INDEX IDX_694309E47FAF4F07 (head_office_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_administrator (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', site_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_B651AB01F6BD1646 (site_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_employed (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', site_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', matricule INT NOT NULL, service VARCHAR(255) NOT NULL, driving_license TINYINT(1) NOT NULL, INDEX IDX_CA15A137F6BD1646 (site_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_super_administrator (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', head_office_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_A983BE4A7FAF4F07 (head_office_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE accident ADD CONSTRAINT FK_8F31DB6FC3C6F69F FOREIGN KEY (car_id) REFERENCES car (id)');
        $this->addSql('ALTER TABLE accident ADD CONSTRAINT FK_8F31DB6F9DA45FAC FOREIGN KEY (user_employed_id) REFERENCES user_employed (id)');
        $this->addSql('ALTER TABLE borrow ADD CONSTRAINT FK_55DBA8B0C3C6F69F FOREIGN KEY (car_id) REFERENCES car (id)');
        $this->addSql('ALTER TABLE borrow ADD CONSTRAINT FK_55DBA8B05D4BB9E2 FOREIGN KEY (borrow_meet_id) REFERENCES borrow_meet (id)');
        $this->addSql('ALTER TABLE borrow_user_employed ADD CONSTRAINT FK_20301744D4C006C8 FOREIGN KEY (borrow_id) REFERENCES borrow (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE borrow_user_employed ADD CONSTRAINT FK_203017449DA45FAC FOREIGN KEY (user_employed_id) REFERENCES user_employed (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE borrow_meet ADD CONSTRAINT FK_6ADBE9EBF6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69DF6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE site ADD CONSTRAINT FK_694309E47FAF4F07 FOREIGN KEY (head_office_id) REFERENCES head_office (id)');
        $this->addSql('ALTER TABLE user_administrator ADD CONSTRAINT FK_B651AB01F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)');
        $this->addSql('ALTER TABLE user_administrator ADD CONSTRAINT FK_B651AB01BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_employed ADD CONSTRAINT FK_CA15A137F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)');
        $this->addSql('ALTER TABLE user_employed ADD CONSTRAINT FK_CA15A137BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_super_administrator ADD CONSTRAINT FK_A983BE4A7FAF4F07 FOREIGN KEY (head_office_id) REFERENCES head_office (id)');
        $this->addSql('ALTER TABLE user_super_administrator ADD CONSTRAINT FK_A983BE4ABF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE accident DROP FOREIGN KEY FK_8F31DB6FC3C6F69F');
        $this->addSql('ALTER TABLE accident DROP FOREIGN KEY FK_8F31DB6F9DA45FAC');
        $this->addSql('ALTER TABLE borrow DROP FOREIGN KEY FK_55DBA8B0C3C6F69F');
        $this->addSql('ALTER TABLE borrow DROP FOREIGN KEY FK_55DBA8B05D4BB9E2');
        $this->addSql('ALTER TABLE borrow_user_employed DROP FOREIGN KEY FK_20301744D4C006C8');
        $this->addSql('ALTER TABLE borrow_user_employed DROP FOREIGN KEY FK_203017449DA45FAC');
        $this->addSql('ALTER TABLE borrow_meet DROP FOREIGN KEY FK_6ADBE9EBF6BD1646');
        $this->addSql('ALTER TABLE car DROP FOREIGN KEY FK_773DE69DF6BD1646');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE site DROP FOREIGN KEY FK_694309E47FAF4F07');
        $this->addSql('ALTER TABLE user_administrator DROP FOREIGN KEY FK_B651AB01F6BD1646');
        $this->addSql('ALTER TABLE user_administrator DROP FOREIGN KEY FK_B651AB01BF396750');
        $this->addSql('ALTER TABLE user_employed DROP FOREIGN KEY FK_CA15A137F6BD1646');
        $this->addSql('ALTER TABLE user_employed DROP FOREIGN KEY FK_CA15A137BF396750');
        $this->addSql('ALTER TABLE user_super_administrator DROP FOREIGN KEY FK_A983BE4A7FAF4F07');
        $this->addSql('ALTER TABLE user_super_administrator DROP FOREIGN KEY FK_A983BE4ABF396750');
        $this->addSql('DROP TABLE accident');
        $this->addSql('DROP TABLE borrow');
        $this->addSql('DROP TABLE borrow_user_employed');
        $this->addSql('DROP TABLE borrow_meet');
        $this->addSql('DROP TABLE car');
        $this->addSql('DROP TABLE head_office');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE site');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_administrator');
        $this->addSql('DROP TABLE user_employed');
        $this->addSql('DROP TABLE user_super_administrator');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
