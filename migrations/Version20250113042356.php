<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250113042356 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE minus (id SERIAL NOT NULL, type_id INT NOT NULL, transaction_id INT NOT NULL, account_id INT NOT NULL, value INT NOT NULL, dat DATE NOT NULL, comment VARCHAR(100) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_38A3B777C54C8C93 ON minus (type_id)');
        $this->addSql('CREATE INDEX IDX_38A3B7772FC0CB0F ON minus (transaction_id)');
        $this->addSql('CREATE INDEX IDX_38A3B7779B6B5FBA ON minus (account_id)');
        $this->addSql('ALTER TABLE minus ADD CONSTRAINT FK_38A3B777C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE minus ADD CONSTRAINT FK_38A3B7772FC0CB0F FOREIGN KEY (transaction_id) REFERENCES transaction (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE minus ADD CONSTRAINT FK_38A3B7779B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE minus DROP CONSTRAINT FK_38A3B777C54C8C93');
        $this->addSql('ALTER TABLE minus DROP CONSTRAINT FK_38A3B7772FC0CB0F');
        $this->addSql('ALTER TABLE minus DROP CONSTRAINT FK_38A3B7779B6B5FBA');
        $this->addSql('DROP TABLE minus');
    }
}
