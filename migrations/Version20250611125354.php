<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250611125354 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE purchase (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, fullname VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, code_postal VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, total INT NOT NULL, statut VARCHAR(255) NOT NULL, INDEX IDX_6117D13BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE purchase ADD CONSTRAINT FK_6117D13BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE purchase DROP FOREIGN KEY FK_6117D13BA76ED395');
        $this->addSql('DROP TABLE purchase');
    }
}
