<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221103110930 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cheese_listing DROP CONSTRAINT fk_356577d4a8a3ebb7');
        $this->addSql('DROP INDEX idx_356577d4a8a3ebb7');
        $this->addSql('ALTER TABLE cheese_listing RENAME COLUMN onwer_id TO owner_id');
        $this->addSql('ALTER TABLE cheese_listing ADD CONSTRAINT FK_356577D47E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_356577D47E3C61F9 ON cheese_listing (owner_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE cheese_listing DROP CONSTRAINT FK_356577D47E3C61F9');
        $this->addSql('DROP INDEX IDX_356577D47E3C61F9');
        $this->addSql('ALTER TABLE cheese_listing RENAME COLUMN owner_id TO onwer_id');
        $this->addSql('ALTER TABLE cheese_listing ADD CONSTRAINT fk_356577d4a8a3ebb7 FOREIGN KEY (onwer_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_356577d4a8a3ebb7 ON cheese_listing (onwer_id)');
    }
}
