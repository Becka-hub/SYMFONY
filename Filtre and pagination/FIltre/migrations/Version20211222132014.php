<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211222132014 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cart (id INT AUTO_INCREMENT NOT NULL, model VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, color VARCHAR(255) NOT NULL, carburant VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cart_city (cart_id INT NOT NULL, city_id INT NOT NULL, INDEX IDX_C2BE020D1AD5CDBF (cart_id), INDEX IDX_C2BE020D8BAC62AF (city_id), PRIMARY KEY(cart_id, city_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cart_city ADD CONSTRAINT FK_C2BE020D1AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cart_city ADD CONSTRAINT FK_C2BE020D8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart_city DROP FOREIGN KEY FK_C2BE020D1AD5CDBF');
        $this->addSql('ALTER TABLE cart_city DROP FOREIGN KEY FK_C2BE020D8BAC62AF');
        $this->addSql('DROP TABLE cart');
        $this->addSql('DROP TABLE cart_city');
        $this->addSql('DROP TABLE city');
    }
}
