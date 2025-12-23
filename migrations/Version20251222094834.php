<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251222094834 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inventory ADD title VARCHAR(100) NOT NULL, ADD description VARCHAR(255) DEFAULT NULL, ADD category VARCHAR(100) NOT NULL, ADD is_public TINYINT NOT NULL, ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, ADD owner_id INT NOT NULL');
        $this->addSql('ALTER TABLE inventory ADD CONSTRAINT FK_B12D4A367E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B12D4A367E3C61F9 ON inventory (owner_id)');
        $this->addSql('ALTER TABLE item ADD custom_id VARCHAR(255) NOT NULL, ADD inventory_id INT NOT NULL, ADD created_by_id INT NOT NULL');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E9EEA759 FOREIGN KEY (inventory_id) REFERENCES inventory (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251EB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_1F1B251E9EEA759 ON item (inventory_id)');
        $this->addSql('CREATE INDEX IDX_1F1B251EB03A8386 ON item (created_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inventory DROP FOREIGN KEY FK_B12D4A367E3C61F9');
        $this->addSql('DROP INDEX IDX_B12D4A367E3C61F9 ON inventory');
        $this->addSql('ALTER TABLE inventory DROP title, DROP description, DROP category, DROP is_public, DROP created_at, DROP updated_at, DROP owner_id');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E9EEA759');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251EB03A8386');
        $this->addSql('DROP INDEX IDX_1F1B251E9EEA759 ON item');
        $this->addSql('DROP INDEX IDX_1F1B251EB03A8386 ON item');
        $this->addSql('ALTER TABLE item DROP custom_id, DROP inventory_id, DROP created_by_id');
    }
}
