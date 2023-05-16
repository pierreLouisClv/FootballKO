<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230516172152 extends AbstractMigration
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
        $this->addSql('ALTER TABLE external_article ADD championship_id INT NOT NULL, ADD club_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE external_article ADD CONSTRAINT FK_DB98D72594DDBCE9 FOREIGN KEY (championship_id) REFERENCES championship (id)');
        $this->addSql('ALTER TABLE external_article ADD CONSTRAINT FK_DB98D72561190A32 FOREIGN KEY (club_id) REFERENCES club (id)');
        $this->addSql('CREATE INDEX IDX_DB98D72594DDBCE9 ON external_article (championship_id)');
        $this->addSql('CREATE INDEX IDX_DB98D72561190A32 ON external_article (club_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article_club (article_id INT NOT NULL, club_id INT NOT NULL, INDEX IDX_8ACBF7361190A32 (club_id), INDEX IDX_8ACBF737294869C (article_id), PRIMARY KEY(article_id, club_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE article_club ADD CONSTRAINT FK_8ACBF7361190A32 FOREIGN KEY (club_id) REFERENCES club (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_club ADD CONSTRAINT FK_8ACBF737294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE external_article DROP FOREIGN KEY FK_DB98D72594DDBCE9');
        $this->addSql('ALTER TABLE external_article DROP FOREIGN KEY FK_DB98D72561190A32');
        $this->addSql('DROP INDEX IDX_DB98D72594DDBCE9 ON external_article');
        $this->addSql('DROP INDEX IDX_DB98D72561190A32 ON external_article');
        $this->addSql('ALTER TABLE external_article DROP championship_id, DROP club_id');
    }
}
