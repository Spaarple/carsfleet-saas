<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240208182712 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'BorrowMeet entity has been added to the Borrow entity. The Borrow';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE borrow_meet ADD trip_destination_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE borrow_meet ADD CONSTRAINT FK_6ADBE9EBC0E1C592 FOREIGN KEY (trip_destination_id) REFERENCES site (id)');
        $this->addSql('CREATE INDEX IDX_6ADBE9EBC0E1C592 ON borrow_meet (trip_destination_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE borrow_meet DROP FOREIGN KEY FK_6ADBE9EBC0E1C592');
        $this->addSql('DROP INDEX IDX_6ADBE9EBC0E1C592 ON borrow_meet');
        $this->addSql('ALTER TABLE borrow_meet DROP trip_destination_id');
    }
}
