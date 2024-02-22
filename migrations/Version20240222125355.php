<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240222125355 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE image_annonce (id INT AUTO_INCREMENT NOT NULL, annonce_id INT DEFAULT NULL, filename VARCHAR(255) NOT NULL, INDEX IDX_6345C4398805AB2F (annonce_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE image_annonce ADD CONSTRAINT FK_6345C4398805AB2F FOREIGN KEY (annonce_id) REFERENCES annonce (id)');
        $this->addSql('ALTER TABLE annonce DROP image_filename');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image_annonce DROP FOREIGN KEY FK_6345C4398805AB2F');
        $this->addSql('DROP TABLE image_annonce');
        $this->addSql('ALTER TABLE annonce ADD image_filename VARCHAR(255) DEFAULT NULL');
    }
}
