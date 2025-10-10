<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251010092148 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE move (id SERIAL NOT NULL, accplus_id INT NOT NULL, accminus_id INT NOT NULL, value INT NOT NULL, dat DATE NOT NULL, comment VARCHAR(100) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_move_accplus ON move (accplus_id)');
        $this->addSql('CREATE INDEX IDX_move_accminus ON move (accminus_id)');
        $this->addSql('ALTER TABLE move ADD CONSTRAINT FK_move_accplus FOREIGN KEY (accplus_id) REFERENCES account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE move ADD CONSTRAINT FK_move_accminus FOREIGN KEY (accminus_id) REFERENCES account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE move DROP CONSTRAINT FK_move_accplus');
        $this->addSql('ALTER TABLE move DROP CONSTRAINT FK_move_accminus');
        $this->addSql('DROP TABLE move');
    }
}
