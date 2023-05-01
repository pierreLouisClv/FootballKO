<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230430095555 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_club DROP FOREIGN KEY FK_8ACBF7361190A32');
        $this->addSql('ALTER TABLE article_club DROP FOREIGN KEY FK_8ACBF737294869C');
        $this->addSql('DROP TABLE article_club');
        $this->addSql('ALTER TABLE category ADD short_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10C7A50F576');
        $this->addSql('DROP INDEX IDX_6A2CA10C7A50F576 ON media');
        $this->addSql('ALTER TABLE media ADD associated_club VARCHAR(255) DEFAULT NULL, CHANGE associated_club_id associated_championship_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10C945BC7F9 FOREIGN KEY (associated_championship_id) REFERENCES championship (id)');
        $this->addSql('CREATE INDEX IDX_6A2CA10C945BC7F9 ON media (associated_championship_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article_club (article_id INT NOT NULL, club_id INT NOT NULL, INDEX IDX_8ACBF737294869C (article_id), INDEX IDX_8ACBF7361190A32 (club_id), PRIMARY KEY(article_id, club_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE article_club ADD CONSTRAINT FK_8ACBF7361190A32 FOREIGN KEY (club_id) REFERENCES club (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_club ADD CONSTRAINT FK_8ACBF737294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category DROP short_name');
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10C945BC7F9');
        $this->addSql('DROP INDEX IDX_6A2CA10C945BC7F9 ON media');
        $this->addSql('ALTER TABLE media DROP associated_club, CHANGE associated_championship_id associated_club_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10C7A50F576 FOREIGN KEY (associated_club_id) REFERENCES club (id)');
        $this->addSql('CREATE INDEX IDX_6A2CA10C7A50F576 ON media (associated_club_id)');
    }
}
