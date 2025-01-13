<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250113043500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE plus (id SERIAL NOT NULL, source_id INT NOT NULL, account_id INT NOT NULL, value INT NOT NULL, dat DATE NOT NULL, comment VARCHAR(100) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_90F2E0F1953C1C61 ON plus (source_id)');
        $this->addSql('CREATE INDEX IDX_90F2E0F19B6B5FBA ON plus (account_id)');
        $this->addSql('ALTER TABLE plus ADD CONSTRAINT FK_90F2E0F1953C1C61 FOREIGN KEY (source_id) REFERENCES source (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE plus ADD CONSTRAINT FK_90F2E0F19B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE plus DROP CONSTRAINT FK_90F2E0F1953C1C61');
        $this->addSql('ALTER TABLE plus DROP CONSTRAINT FK_90F2E0F19B6B5FBA');
        $this->addSql('DROP TABLE plus');
    }
}
