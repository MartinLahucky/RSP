<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231119205358 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE clanek (id INT AUTO_INCREMENT NOT NULL, id_recenzni_rizeni_id INT NOT NULL, stav_redakce INT NOT NULL, stav_autor INT NOT NULL, INDEX IDX_60591765530ADD1B (id_recenzni_rizeni_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recenzni_rizeni (id INT AUTO_INCREMENT NOT NULL, tisk_id_id INT NOT NULL, od DATE NOT NULL, do DATE NOT NULL, UNIQUE INDEX UNIQ_48A89B91338276F (tisk_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tisk (id INT AUTO_INCREMENT NOT NULL, datum DATE NOT NULL, kapacita INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE clanek ADD CONSTRAINT FK_60591765530ADD1B FOREIGN KEY (id_recenzni_rizeni_id) REFERENCES recenzni_rizeni (id)');
        $this->addSql('ALTER TABLE recenzni_rizeni ADD CONSTRAINT FK_48A89B91338276F FOREIGN KEY (tisk_id_id) REFERENCES tisk (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE clanek DROP FOREIGN KEY FK_60591765530ADD1B');
        $this->addSql('ALTER TABLE recenzni_rizeni DROP FOREIGN KEY FK_48A89B91338276F');
        $this->addSql('DROP TABLE clanek');
        $this->addSql('DROP TABLE recenzni_rizeni');
        $this->addSql('DROP TABLE tisk');
    }
}
