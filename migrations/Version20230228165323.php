<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230228165323 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE injury_article (id INT AUTO_INCREMENT NOT NULL, championship_id INT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT DEFAULT NULL, introduction LONGTEXT DEFAULT NULL, day SMALLINT NOT NULL, INDEX IDX_F7D9B5C194DDBCE9 (championship_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE injury_tab (id INT AUTO_INCREMENT NOT NULL, club_id INT NOT NULL, article_id INT DEFAULT NULL, update_at DATETIME NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', status VARCHAR(255) NOT NULL, day SMALLINT NOT NULL, INDEX IDX_846F59B61190A32 (club_id), INDEX IDX_846F59B7294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE injury_tab_player (injury_tab_id INT NOT NULL, player_id INT NOT NULL, INDEX IDX_DBBDB28C61736374 (injury_tab_id), INDEX IDX_DBBDB28C99E6F5DF (player_id), PRIMARY KEY(injury_tab_id, player_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE injury_article ADD CONSTRAINT FK_F7D9B5C194DDBCE9 FOREIGN KEY (championship_id) REFERENCES championship (id)');
        $this->addSql('ALTER TABLE injury_tab ADD CONSTRAINT FK_846F59B61190A32 FOREIGN KEY (club_id) REFERENCES club (id)');
        $this->addSql('ALTER TABLE injury_tab ADD CONSTRAINT FK_846F59B7294869C FOREIGN KEY (article_id) REFERENCES injury_article (id)');
        $this->addSql('ALTER TABLE injury_tab_player ADD CONSTRAINT FK_DBBDB28C61736374 FOREIGN KEY (injury_tab_id) REFERENCES injury_tab (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE injury_tab_player ADD CONSTRAINT FK_DBBDB28C99E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE championship ADD current_day SMALLINT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE injury_article DROP FOREIGN KEY FK_F7D9B5C194DDBCE9');
        $this->addSql('ALTER TABLE injury_tab DROP FOREIGN KEY FK_846F59B61190A32');
        $this->addSql('ALTER TABLE injury_tab DROP FOREIGN KEY FK_846F59B7294869C');
        $this->addSql('ALTER TABLE injury_tab_player DROP FOREIGN KEY FK_DBBDB28C61736374');
        $this->addSql('ALTER TABLE injury_tab_player DROP FOREIGN KEY FK_DBBDB28C99E6F5DF');
        $this->addSql('DROP TABLE injury_article');
        $this->addSql('DROP TABLE injury_tab');
        $this->addSql('DROP TABLE injury_tab_player');
        $this->addSql('ALTER TABLE championship DROP current_day');
    }
}
