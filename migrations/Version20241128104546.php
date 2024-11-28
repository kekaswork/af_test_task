<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241128104546 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE clients ALTER id TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE clients ALTER fico_score TYPE INTEGER');
        $this->addSql('ALTER TABLE loans ALTER id TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE loans ALTER client_id TYPE VARCHAR(255)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE loans ALTER id TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE loans ALTER client_id TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE clients ALTER id TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE clients ALTER fico_score TYPE INT');
    }
}
