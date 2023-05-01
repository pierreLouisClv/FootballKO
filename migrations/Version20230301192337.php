<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230301192337 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article ADD slug VARCHAR(128) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_23A0E66989D9B62 ON article (slug)');
        $this->addSql('ALTER TABLE category ADD slug VARCHAR(128) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_64C19C1989D9B62 ON category (slug)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EBADDE6A989D9B62 ON championship (slug)');
        $this->addSql('ALTER TABLE club ADD slug VARCHAR(128) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B8EE3872989D9B62 ON club (slug)');
        $this->addSql('ALTER TABLE injury_article ADD slug VARCHAR(128) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F7D9B5C1989D9B62 ON injury_article (slug)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_23A0E66989D9B62 ON article');
        $this->addSql('ALTER TABLE article DROP slug');
        $this->addSql('DROP INDEX UNIQ_64C19C1989D9B62 ON category');
        $this->addSql('ALTER TABLE category DROP slug');
        $this->addSql('DROP INDEX UNIQ_EBADDE6A989D9B62 ON championship');
        $this->addSql('DROP INDEX UNIQ_B8EE3872989D9B62 ON club');
        $this->addSql('ALTER TABLE club DROP slug');
        $this->addSql('DROP INDEX UNIQ_F7D9B5C1989D9B62 ON injury_article');
        $this->addSql('ALTER TABLE injury_article DROP slug');
    }
}
