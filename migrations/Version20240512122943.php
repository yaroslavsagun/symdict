<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240512122943 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE translations DROP FOREIGN KEY FK_C6B7DA8719EFF0F5');
        $this->addSql('ALTER TABLE translations DROP FOREIGN KEY FK_C6B7DA8775FE5ADE');
        $this->addSql('ALTER TABLE translations ADD CONSTRAINT FK_C6B7DA8719EFF0F5 FOREIGN KEY (translation_language_id) REFERENCES languages (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE translations ADD CONSTRAINT FK_C6B7DA8775FE5ADE FOREIGN KEY (original_language_id) REFERENCES languages (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE words DROP FOREIGN KEY FK_717D1E8C82F1BAF4');
        $this->addSql('ALTER TABLE words ADD CONSTRAINT FK_717D1E8C82F1BAF4 FOREIGN KEY (language_id) REFERENCES languages (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE translations DROP FOREIGN KEY FK_C6B7DA8775FE5ADE');
        $this->addSql('ALTER TABLE translations DROP FOREIGN KEY FK_C6B7DA8719EFF0F5');
        $this->addSql('ALTER TABLE translations ADD CONSTRAINT FK_C6B7DA8775FE5ADE FOREIGN KEY (original_language_id) REFERENCES languages (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE translations ADD CONSTRAINT FK_C6B7DA8719EFF0F5 FOREIGN KEY (translation_language_id) REFERENCES languages (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE words DROP FOREIGN KEY FK_717D1E8C82F1BAF4');
        $this->addSql('ALTER TABLE words ADD CONSTRAINT FK_717D1E8C82F1BAF4 FOREIGN KEY (language_id) REFERENCES languages (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
