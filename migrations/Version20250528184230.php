<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250528184230 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE import1 (id SERIAL NOT NULL, postingdate DATE NOT NULL, valuedate DATE NOT NULL, contractor VARCHAR(100) DEFAULT NULL, billsource VARCHAR(50) DEFAULT NULL, billdestination VARCHAR(50) DEFAULT NULL, title VARCHAR(100) DEFAULT NULL, value INT NOT NULL, transaction VARCHAR(50) NOT NULL, type VARCHAR(50) NOT NULL, category VARCHAR(50) NOT NULL, PRIMARY KEY(id), UNIQUE (transaction))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
//        $this->addSql('DROP TABLE import1');
    }
}
