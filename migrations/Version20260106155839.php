<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260106155839 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE inventory_access (id INT AUTO_INCREMENT NOT NULL, permission VARCHAR(20) NOT NULL, granted_at DATETIME DEFAULT NULL, inventory_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_6B5B7FF9EEA759 (inventory_id), INDEX IDX_6B5B7FFA76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE inventory_access ADD CONSTRAINT FK_6B5B7FF9EEA759 FOREIGN KEY (inventory_id) REFERENCES inventory (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE inventory_access ADD CONSTRAINT FK_6B5B7FFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inventory_access DROP FOREIGN KEY FK_6B5B7FF9EEA759');
        $this->addSql('ALTER TABLE inventory_access DROP FOREIGN KEY FK_6B5B7FFA76ED395');
        $this->addSql('DROP TABLE inventory_access');
    }
}
