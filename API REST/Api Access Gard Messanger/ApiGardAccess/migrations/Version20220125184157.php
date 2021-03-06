<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220125184157 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649DDEAB1A3');
        $this->addSql('ALTER TABLE user CHANGE etudiant_id etudiant_id INT NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649DDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiants (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vage DROP FOREIGN KEY FK_3161E9FCDDEAB1A3');
        $this->addSql('ALTER TABLE vage CHANGE etudiant_id etudiant_id INT NOT NULL');
        $this->addSql('ALTER TABLE vage ADD CONSTRAINT FK_3161E9FCDDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiants (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649DDEAB1A3');
        $this->addSql('ALTER TABLE user CHANGE etudiant_id etudiant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649DDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiants (id)');
        $this->addSql('ALTER TABLE vage DROP FOREIGN KEY FK_3161E9FCDDEAB1A3');
        $this->addSql('ALTER TABLE vage CHANGE etudiant_id etudiant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE vage ADD CONSTRAINT FK_3161E9FCDDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiants (id)');
    }
}
