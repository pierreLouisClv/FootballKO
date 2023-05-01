<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230321113622 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B8EE3872989D9B62 ON club (slug)');
        $this->addSql('ALTER TABLE injury_article ADD media_id INT NOT NULL, ADD slug VARCHAR(128) NOT NULL');
        $this->addSql('ALTER TABLE injury_article ADD CONSTRAINT FK_F7D9B5C1EA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F7D9B5C1989D9B62 ON injury_article (slug)');
        $this->addSql('CREATE INDEX IDX_F7D9B5C1EA9FDD75 ON injury_article (media_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_B8EE3872989D9B62 ON club');
        $this->addSql('ALTER TABLE injury_article DROP FOREIGN KEY FK_F7D9B5C1EA9FDD75');
        $this->addSql('DROP INDEX UNIQ_F7D9B5C1989D9B62 ON injury_article');
        $this->addSql('DROP INDEX IDX_F7D9B5C1EA9FDD75 ON injury_article');
        $this->addSql('ALTER TABLE injury_article DROP media_id, DROP slug');
    }
}
