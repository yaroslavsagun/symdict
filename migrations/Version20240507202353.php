<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240507202353 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE languages (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE translations (id INT AUTO_INCREMENT NOT NULL, original_language_id INT NOT NULL, translation_language_id INT NOT NULL, original_word_id INT NOT NULL, translation_word_id INT NOT NULL, INDEX IDX_C6B7DA8775FE5ADE (original_language_id), INDEX IDX_C6B7DA8719EFF0F5 (translation_language_id), INDEX IDX_C6B7DA8765D7ED7A (original_word_id), INDEX IDX_C6B7DA87425BBA7F (translation_word_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE words (id INT AUTO_INCREMENT NOT NULL, language_id INT NOT NULL, word VARCHAR(255) NOT NULL, INDEX IDX_717D1E8C82F1BAF4 (language_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE translations ADD CONSTRAINT FK_C6B7DA8775FE5ADE FOREIGN KEY (original_language_id) REFERENCES languages (id)');
        $this->addSql('ALTER TABLE translations ADD CONSTRAINT FK_C6B7DA8719EFF0F5 FOREIGN KEY (translation_language_id) REFERENCES languages (id)');
        $this->addSql('ALTER TABLE translations ADD CONSTRAINT FK_C6B7DA8765D7ED7A FOREIGN KEY (original_word_id) REFERENCES words (id)');
        $this->addSql('ALTER TABLE translations ADD CONSTRAINT FK_C6B7DA87425BBA7F FOREIGN KEY (translation_word_id) REFERENCES words (id)');
        $this->addSql('ALTER TABLE words ADD CONSTRAINT FK_717D1E8C82F1BAF4 FOREIGN KEY (language_id) REFERENCES languages (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE translations DROP FOREIGN KEY FK_C6B7DA8775FE5ADE');
        $this->addSql('ALTER TABLE translations DROP FOREIGN KEY FK_C6B7DA8719EFF0F5');
        $this->addSql('ALTER TABLE translations DROP FOREIGN KEY FK_C6B7DA8765D7ED7A');
        $this->addSql('ALTER TABLE translations DROP FOREIGN KEY FK_C6B7DA87425BBA7F');
        $this->addSql('ALTER TABLE words DROP FOREIGN KEY FK_717D1E8C82F1BAF4');
        $this->addSql('DROP TABLE languages');
        $this->addSql('DROP TABLE translations');
        $this->addSql('DROP TABLE words');
    }
}
