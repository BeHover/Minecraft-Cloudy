<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230720184504 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE moderator (id INT AUTO_INCREMENT NOT NULL, user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', realname VARCHAR(255) NOT NULL, location VARCHAR(255) NOT NULL, birthday DATETIME NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_6A30B268A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE otp (id INT AUTO_INCREMENT NOT NULL, otp INT NOT NULL, username VARCHAR(180) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE report (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', created_by CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', type_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', closed_by CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', is_active TINYINT(1) NOT NULL, text LONGTEXT NOT NULL, created_at DATETIME NOT NULL, closed_at DATETIME DEFAULT NULL, INDEX IDX_C42F7784DE12AB56 (created_by), INDEX IDX_C42F7784C54C8C93 (type_id), INDEX IDX_C42F778488F6E01 (closed_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE report_chat_messages (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', report_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', text LONGTEXT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_66FD88D64BD2A4C0 (report_id), INDEX IDX_66FD88D6A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE report_type (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_FFF2BAD25E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', username VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, skin VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, image_url VARCHAR(255) DEFAULT NULL, discord VARCHAR(255) DEFAULT NULL, youtube VARCHAR(255) DEFAULT NULL, twitch VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE moderator ADD CONSTRAINT FK_6A30B268A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784DE12AB56 FOREIGN KEY (created_by) REFERENCES user (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784C54C8C93 FOREIGN KEY (type_id) REFERENCES report_type (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F778488F6E01 FOREIGN KEY (closed_by) REFERENCES user (id)');
        $this->addSql('ALTER TABLE report_chat_messages ADD CONSTRAINT FK_66FD88D64BD2A4C0 FOREIGN KEY (report_id) REFERENCES report (id)');
        $this->addSql('ALTER TABLE report_chat_messages ADD CONSTRAINT FK_66FD88D6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE moderator DROP FOREIGN KEY FK_6A30B268A76ED395');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F7784DE12AB56');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F7784C54C8C93');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F778488F6E01');
        $this->addSql('ALTER TABLE report_chat_messages DROP FOREIGN KEY FK_66FD88D64BD2A4C0');
        $this->addSql('ALTER TABLE report_chat_messages DROP FOREIGN KEY FK_66FD88D6A76ED395');
        $this->addSql('DROP TABLE moderator');
        $this->addSql('DROP TABLE otp');
        $this->addSql('DROP TABLE report');
        $this->addSql('DROP TABLE report_chat_messages');
        $this->addSql('DROP TABLE report_type');
        $this->addSql('DROP TABLE user');
    }
}
