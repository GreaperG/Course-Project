<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260102154252 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE custom_field ADD name VARCHAR(255) NOT NULL, ADD type VARCHAR(255) NOT NULL, ADD required TINYINT NOT NULL, ADD inventory_id INT NOT NULL');
        $this->addSql('ALTER TABLE custom_field ADD CONSTRAINT FK_98F8BD319EEA759 FOREIGN KEY (inventory_id) REFERENCES inventory (id)');
        $this->addSql('CREATE INDEX IDX_98F8BD319EEA759 ON custom_field (inventory_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE custom_field DROP FOREIGN KEY FK_98F8BD319EEA759');
        $this->addSql('DROP INDEX IDX_98F8BD319EEA759 ON custom_field');
        $this->addSql('ALTER TABLE custom_field DROP name, DROP type, DROP required, DROP inventory_id');
    }
}
