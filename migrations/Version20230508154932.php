<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230508154932 extends AbstractMigration
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
        $this->addSql('ALTER TABLE article ADD mentioned_champ_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E6649D41133 FOREIGN KEY (mentioned_champ_id) REFERENCES championship (id)');
        $this->addSql('CREATE INDEX IDX_23A0E6649D41133 ON article (mentioned_champ_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article_club (article_id INT NOT NULL, club_id INT NOT NULL, INDEX IDX_8ACBF7361190A32 (club_id), INDEX IDX_8ACBF737294869C (article_id), PRIMARY KEY(article_id, club_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE article_club ADD CONSTRAINT FK_8ACBF7361190A32 FOREIGN KEY (club_id) REFERENCES club (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_club ADD CONSTRAINT FK_8ACBF737294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E6649D41133');
        $this->addSql('DROP INDEX IDX_23A0E6649D41133 ON article');
        $this->addSql('ALTER TABLE article DROP mentioned_champ_id');
    }
}
