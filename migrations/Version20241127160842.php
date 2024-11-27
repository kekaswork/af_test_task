<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241127160842 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Generating loans table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE loans (id VARCHAR(255) NOT NULL, client_id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, term INT NOT NULL, interest DOUBLE PRECISION NOT NULL, sum DOUBLE PRECISION NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_82C24DBC19EB6921 ON loans (client_id)');
        $this->addSql('COMMENT ON COLUMN loans.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN loans.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE loans ADD CONSTRAINT FK_82C24DBC19EB6921 FOREIGN KEY (client_id) REFERENCES clients (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE clients ALTER id TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE clients ALTER fico_score TYPE INTEGER');
        $this->addSql('ALTER TABLE clients ALTER date_of_birth TYPE DATE');
        $this->addSql('COMMENT ON COLUMN clients.date_of_birth IS \'(DC2Type:date_immutable)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C82E74E7927C74 ON clients (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE loans DROP CONSTRAINT FK_82C24DBC19EB6921');
        $this->addSql('DROP TABLE loans');
        $this->addSql('DROP INDEX UNIQ_C82E74E7927C74');
        $this->addSql('ALTER TABLE clients ALTER id TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE clients ALTER date_of_birth TYPE DATE');
        $this->addSql('ALTER TABLE clients ALTER fico_score TYPE INT');
        $this->addSql('COMMENT ON COLUMN clients.date_of_birth IS NULL');
    }
}
