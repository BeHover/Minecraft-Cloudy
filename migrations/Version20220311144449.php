<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220311144449 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE news');
        $this->addSql('ALTER TABLE moderator DROP FOREIGN KEY FK_6A30B268DB403044');
        $this->addSql('DROP INDEX IDX_6A30B268DB403044 ON moderator');
        $this->addSql('ALTER TABLE moderator DROP mentor_id, DROP rating');
        $this->addSql('ALTER TABLE server DROP icon, DROP color, DROP type');
        $this->addSql('ALTER TABLE user ADD icon VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE news (id INT AUTO_INCREMENT NOT NULL, server_id INT DEFAULT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, text LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, publication_date DATE NOT NULL, image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_1DD399501844E6B7 (server_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE news ADD CONSTRAINT FK_1DD399501844E6B7 FOREIGN KEY (server_id) REFERENCES server (id)');
        $this->addSql('ALTER TABLE moderator ADD mentor_id INT DEFAULT NULL, ADD rating INT NOT NULL');
        $this->addSql('ALTER TABLE moderator ADD CONSTRAINT FK_6A30B268DB403044 FOREIGN KEY (mentor_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_6A30B268DB403044 ON moderator (mentor_id)');
        $this->addSql('ALTER TABLE server ADD icon VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD color VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD type VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user DROP icon');
    }
}
