<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260103113621 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE item_attribute_value (id INT AUTO_INCREMENT NOT NULL, value LONGTEXT DEFAULT NULL, item_id INT NOT NULL, attribute_id INT NOT NULL, INDEX IDX_448E6020126F525E (item_id), INDEX IDX_448E6020B6E62EFA (attribute_id), UNIQUE INDEX unique_item_attribute (item_id, attribute_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE item_attribute_value ADD CONSTRAINT FK_448E6020126F525E FOREIGN KEY (item_id) REFERENCES item (id)');
        $this->addSql('ALTER TABLE item_attribute_value ADD CONSTRAINT FK_448E6020B6E62EFA FOREIGN KEY (attribute_id) REFERENCES inventory_attribute (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item_attribute_value DROP FOREIGN KEY FK_448E6020126F525E');
        $this->addSql('ALTER TABLE item_attribute_value DROP FOREIGN KEY FK_448E6020B6E62EFA');
        $this->addSql('DROP TABLE item_attribute_value');
    }
}
