<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241127154811 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Removes the "age" column, adds the "date_of_birth" column for the "clients" table';
    }

    public function up(Schema $schema): void
    {
        // Removing the 'age' column
        $this->addSql('ALTER TABLE clients DROP COLUMN age');

        // Adding the 'date_of_birth' column
        $this->addSql('ALTER TABLE clients ADD date_of_birth DATE NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // Adding the 'age' column back
        $this->addSql('ALTER TABLE clients ADD age INT NOT NULL');

        // Removing the 'date_of_birth' column
        $this->addSql('ALTER TABLE clients DROP COLUMN date_of_birth');
    }
}
