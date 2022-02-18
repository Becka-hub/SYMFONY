<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220103173557 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, fk_login_id INT DEFAULT NULL, fk_category_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, content VARCHAR(255) DEFAULT NULL, active TINYINT(1) DEFAULT NULL, date_save DATETIME DEFAULT NULL, date_updated DATETIME DEFAULT NULL, date_deleted DATETIME DEFAULT NULL, INDEX IDX_23A0E666E9AA383 (fk_login_id), INDEX IDX_23A0E667BB031D6 (fk_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, active TINYINT(1) DEFAULT NULL, date_save DATETIME DEFAULT NULL, date_updated DATETIME DEFAULT NULL, date_deleted DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE login (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, email VARCHAR(1000) DEFAULT NULL, description LONGTEXT DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, active TINYINT(1) DEFAULT NULL, date_save DATETIME DEFAULT NULL, date_updated DATETIME DEFAULT NULL, date_deleted DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E666E9AA383 FOREIGN KEY (fk_login_id) REFERENCES login (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E667BB031D6 FOREIGN KEY (fk_category_id) REFERENCES category (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E667BB031D6');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E666E9AA383');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE login');
    }
}
