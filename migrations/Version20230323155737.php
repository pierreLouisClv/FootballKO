<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230323155737 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_club DROP FOREIGN KEY FK_8ACBF737294869C');
        $this->addSql('ALTER TABLE article_club DROP FOREIGN KEY FK_8ACBF7361190A32');
        $this->addSql('DROP TABLE article_club');
        $this->addSql('ALTER TABLE injury_article ADD author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE injury_article ADD CONSTRAINT FK_F7D9B5C112469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE injury_article ADD CONSTRAINT FK_F7D9B5C1F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_F7D9B5C112469DE2 ON injury_article (category_id)');
        $this->addSql('CREATE INDEX IDX_F7D9B5C1F675F31B ON injury_article (author_id)');
        $this->addSql('ALTER TABLE user ADD email VARCHAR(180) NOT NULL, ADD roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', ADD password VARCHAR(255) NOT NULL, DROP pseudo');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article_club (article_id INT NOT NULL, club_id INT NOT NULL, INDEX IDX_8ACBF737294869C (article_id), INDEX IDX_8ACBF7361190A32 (club_id), PRIMARY KEY(article_id, club_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE article_club ADD CONSTRAINT FK_8ACBF737294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_club ADD CONSTRAINT FK_8ACBF7361190A32 FOREIGN KEY (club_id) REFERENCES club (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE injury_article DROP FOREIGN KEY FK_F7D9B5C112469DE2');
        $this->addSql('ALTER TABLE injury_article DROP FOREIGN KEY FK_F7D9B5C1F675F31B');
        $this->addSql('DROP INDEX IDX_F7D9B5C112469DE2 ON injury_article');
        $this->addSql('DROP INDEX IDX_F7D9B5C1F675F31B ON injury_article');
        $this->addSql('ALTER TABLE injury_article DROP author_id');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74 ON user');
        $this->addSql('ALTER TABLE user ADD pseudo VARCHAR(25) NOT NULL, DROP email, DROP roles, DROP password');
    }
}
