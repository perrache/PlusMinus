<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260212223616 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE saldo (id SERIAL NOT NULL, account_id INT NOT NULL, value INT NOT NULL, dat DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_saldo_account ON saldo (account_id)');
        $this->addSql('ALTER TABLE saldo ADD CONSTRAINT FK_saldo_account FOREIGN KEY (account_id) REFERENCES account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE saldo DROP CONSTRAINT FK_saldo_account');
        $this->addSql('DROP TABLE saldo');
    }
}
