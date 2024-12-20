<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241220221819 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chapitre (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, contenu LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chapitre_tutoriel (chapitre_id INT NOT NULL, tutoriel_id INT NOT NULL, INDEX IDX_E70CE7A91FBEEF7B (chapitre_id), INDEX IDX_E70CE7A97CB6CDBB (tutoriel_id), PRIMARY KEY(chapitre_id, tutoriel_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, tutoriel_id INT NOT NULL, contenu LONGTEXT NOT NULL, auteur VARCHAR(255) NOT NULL, date_creation DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_67F068BC7CB6CDBB (tutoriel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE chapitre_tutoriel ADD CONSTRAINT FK_E70CE7A91FBEEF7B FOREIGN KEY (chapitre_id) REFERENCES chapitre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chapitre_tutoriel ADD CONSTRAINT FK_E70CE7A97CB6CDBB FOREIGN KEY (tutoriel_id) REFERENCES tutoriel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC7CB6CDBB FOREIGN KEY (tutoriel_id) REFERENCES tutoriel (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chapitre_tutoriel DROP FOREIGN KEY FK_E70CE7A91FBEEF7B');
        $this->addSql('ALTER TABLE chapitre_tutoriel DROP FOREIGN KEY FK_E70CE7A97CB6CDBB');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC7CB6CDBB');
        $this->addSql('DROP TABLE chapitre');
        $this->addSql('DROP TABLE chapitre_tutoriel');
        $this->addSql('DROP TABLE commentaire');
    }
}
