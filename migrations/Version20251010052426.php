<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251010052426 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE minus (id SERIAL NOT NULL, type_id INT NOT NULL, transaction_id INT NOT NULL, account_id INT NOT NULL, value INT NOT NULL, dat DATE NOT NULL, comment VARCHAR(100) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_minus_type ON minus (type_id)');
        $this->addSql('CREATE INDEX IDX_minus_transaction ON minus (transaction_id)');
        $this->addSql('CREATE INDEX IDX_minus_account ON minus (account_id)');
        $this->addSql('ALTER TABLE minus ADD CONSTRAINT FK_minus_type FOREIGN KEY (type_id) REFERENCES type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE minus ADD CONSTRAINT FK_minus_transaction FOREIGN KEY (transaction_id) REFERENCES transaction (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE minus ADD CONSTRAINT FK_minus_account FOREIGN KEY (account_id) REFERENCES account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE minus DROP CONSTRAINT FK_minus_type');
        $this->addSql('ALTER TABLE minus DROP CONSTRAINT FK_minus_transaction');
        $this->addSql('ALTER TABLE minus DROP CONSTRAINT FK_minus_account');
        $this->addSql('DROP TABLE minus');
    }
}
