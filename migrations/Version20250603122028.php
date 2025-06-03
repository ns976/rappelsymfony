<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250603122028 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product ADD user_create_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADEEFE5067 FOREIGN KEY (user_create_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADEEFE5067 ON product (user_create_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADEEFE5067');
        $this->addSql('DROP INDEX IDX_D34A04ADEEFE5067 ON product');
        $this->addSql('ALTER TABLE product DROP user_create_id');
    }
}
