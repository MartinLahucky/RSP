<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231120105555 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE komentar_clanek (id INT AUTO_INCREMENT NOT NULL, verze_clanku_id_id INT NOT NULL, user_id_id INT NOT NULL, datum DATE NOT NULL, text VARCHAR(10000) NOT NULL, INDEX IDX_3415CE53E0BC71B4 (verze_clanku_id_id), INDEX IDX_3415CE539D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE posudek (id INT AUTO_INCREMENT NOT NULL, clanek_id_id INT NOT NULL, user_id_id INT NOT NULL, posudek_soubor VARCHAR(1000) NOT NULL, zpristupnen_autorovi TINYINT(1) NOT NULL, aktualnost INT NOT NULL, zajimavost INT NOT NULL, originalita INT NOT NULL, odborna_uroven INT NOT NULL, jazykova_uroven INT NOT NULL, INDEX IDX_E7013CA581572CB8 (clanek_id_id), INDEX IDX_E7013CA59D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE verze_clanku (id INT AUTO_INCREMENT NOT NULL, clanek_id_id INT NOT NULL, datum_nahrani DATE NOT NULL, soubor_clanek VARCHAR(1000) NOT NULL, zpristupnen_recenzentum TINYINT(1) NOT NULL, INDEX IDX_FE91554581572CB8 (clanek_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE komentar_clanek ADD CONSTRAINT FK_3415CE53E0BC71B4 FOREIGN KEY (verze_clanku_id_id) REFERENCES verze_clanku (id)');
        $this->addSql('ALTER TABLE komentar_clanek ADD CONSTRAINT FK_3415CE539D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE posudek ADD CONSTRAINT FK_E7013CA581572CB8 FOREIGN KEY (clanek_id_id) REFERENCES clanek (id)');
        $this->addSql('ALTER TABLE posudek ADD CONSTRAINT FK_E7013CA59D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE verze_clanku ADD CONSTRAINT FK_FE91554581572CB8 FOREIGN KEY (clanek_id_id) REFERENCES clanek (id)');
        $this->addSql('ALTER TABLE clanek ADD user_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE clanek ADD CONSTRAINT FK_605917659D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_605917659D86650F ON clanek (user_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE komentar_clanek DROP FOREIGN KEY FK_3415CE53E0BC71B4');
        $this->addSql('ALTER TABLE komentar_clanek DROP FOREIGN KEY FK_3415CE539D86650F');
        $this->addSql('ALTER TABLE posudek DROP FOREIGN KEY FK_E7013CA581572CB8');
        $this->addSql('ALTER TABLE posudek DROP FOREIGN KEY FK_E7013CA59D86650F');
        $this->addSql('ALTER TABLE verze_clanku DROP FOREIGN KEY FK_FE91554581572CB8');
        $this->addSql('DROP TABLE komentar_clanek');
        $this->addSql('DROP TABLE posudek');
        $this->addSql('DROP TABLE verze_clanku');
        $this->addSql('ALTER TABLE clanek DROP FOREIGN KEY FK_605917659D86650F');
        $this->addSql('DROP INDEX IDX_605917659D86650F ON clanek');
        $this->addSql('ALTER TABLE clanek DROP user_id_id');
    }
}
