<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231025212740 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE author CHANGE author username VARCHAR(200) NOT NULL, CHANGE nbrbooks nb_books INT NOT NULL');
        $this->addSql('ALTER TABLE book ADD category VARCHAR(255) NOT NULL, CHANGE author_id author_id INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE author CHANGE username author VARCHAR(200) NOT NULL, CHANGE nb_books nbrbooks INT NOT NULL');
        $this->addSql('ALTER TABLE book DROP category, CHANGE author_id author_id INT NOT NULL');
    }
}
