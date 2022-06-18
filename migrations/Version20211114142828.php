<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211114142828 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE authme (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, realname VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, ip VARCHAR(40) DEFAULT NULL, lastlogin BIGINT DEFAULT NULL, x DOUBLE PRECISION NOT NULL, y DOUBLE PRECISION NOT NULL, z DOUBLE PRECISION NOT NULL, world VARCHAR(255) NOT NULL, regdate BIGINT NOT NULL, regip VARCHAR(40) DEFAULT NULL, yaw DOUBLE PRECISION DEFAULT NULL, pitch DOUBLE PRECISION DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, is_logged SMALLINT NOT NULL, has_session SMALLINT NOT NULL, totp VARCHAR(32) DEFAULT NULL, uuid VARCHAR(36) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE board_publication (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, server_id INT NOT NULL, category_id INT NOT NULL, title VARCHAR(255) NOT NULL, text LONGTEXT NOT NULL, status INT NOT NULL, date DATETIME NOT NULL, INDEX IDX_6D47960AA76ED395 (user_id), INDEX IDX_6D47960A1844E6B7 (server_id), INDEX IDX_6D47960A12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE board_publication_category (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) DEFAULT NULL, icon VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE moderator (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, mentor_id INT DEFAULT NULL, server_id INT NOT NULL, realname VARCHAR(255) NOT NULL, rating INT NOT NULL, creation_date DATE NOT NULL, UNIQUE INDEX UNIQ_6A30B268A76ED395 (user_id), INDEX IDX_6A30B268DB403044 (mentor_id), INDEX IDX_6A30B2681844E6B7 (server_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE news (id INT AUTO_INCREMENT NOT NULL, server_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, text LONGTEXT NOT NULL, publication_date DATE NOT NULL, image VARCHAR(255) DEFAULT NULL, INDEX IDX_1DD399501844E6B7 (server_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE server (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, icon VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, rules LONGTEXT NOT NULL, worldmap VARCHAR(255) NOT NULL, color VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, status SMALLINT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE board_publication ADD CONSTRAINT FK_6D47960AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE board_publication ADD CONSTRAINT FK_6D47960A1844E6B7 FOREIGN KEY (server_id) REFERENCES server (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE board_publication ADD CONSTRAINT FK_6D47960A12469DE2 FOREIGN KEY (category_id) REFERENCES board_publication_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE moderator ADD CONSTRAINT FK_6A30B268A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE moderator ADD CONSTRAINT FK_6A30B268DB403044 FOREIGN KEY (mentor_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE moderator ADD CONSTRAINT FK_6A30B2681844E6B7 FOREIGN KEY (server_id) REFERENCES server (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE news ADD CONSTRAINT FK_1DD399501844E6B7 FOREIGN KEY (server_id) REFERENCES server (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE board_publication DROP FOREIGN KEY FK_6D47960A12469DE2');
        $this->addSql('ALTER TABLE board_publication DROP FOREIGN KEY FK_6D47960A1844E6B7');
        $this->addSql('ALTER TABLE moderator DROP FOREIGN KEY FK_6A30B2681844E6B7');
        $this->addSql('ALTER TABLE news DROP FOREIGN KEY FK_1DD399501844E6B7');
        $this->addSql('ALTER TABLE board_publication DROP FOREIGN KEY FK_6D47960AA76ED395');
        $this->addSql('ALTER TABLE moderator DROP FOREIGN KEY FK_6A30B268A76ED395');
        $this->addSql('ALTER TABLE moderator DROP FOREIGN KEY FK_6A30B268DB403044');
        $this->addSql('DROP TABLE authme');
        $this->addSql('DROP TABLE board_publication');
        $this->addSql('DROP TABLE board_publication_category');
        $this->addSql('DROP TABLE moderator');
        $this->addSql('DROP TABLE news');
        $this->addSql('DROP TABLE server');
        $this->addSql('DROP TABLE user');
    }
}
