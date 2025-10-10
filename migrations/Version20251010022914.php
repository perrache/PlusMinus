<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251010022914 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE account (id SERIAL NOT NULL, currency_id INT NOT NULL, organization_id INT NOT NULL, name VARCHAR(50) NOT NULL, bo INT NOT NULL, lt INT NOT NULL, import INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_account_currency ON account (currency_id)');
        $this->addSql('CREATE INDEX IDX_account_organization ON account (organization_id)');
        $this->addSql('ALTER TABLE account ADD CONSTRAINT FK_account_currency FOREIGN KEY (currency_id) REFERENCES currency (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE account ADD CONSTRAINT FK_account_organization FOREIGN KEY (organization_id) REFERENCES organization (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE account DROP CONSTRAINT FK_account_currency');
        $this->addSql('ALTER TABLE account DROP CONSTRAINT FK_account_organization');
        $this->addSql('DROP TABLE account');
    }
}
