<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231120110234 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE namitka (id INT AUTO_INCREMENT NOT NULL, clanek_id_id INT NOT NULL, datum DATE NOT NULL, text_namitky VARCHAR(10000) NOT NULL, UNIQUE INDEX UNIQ_4D851C6281572CB8 (clanek_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE namitka ADD CONSTRAINT FK_4D851C6281572CB8 FOREIGN KEY (clanek_id_id) REFERENCES clanek (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE namitka DROP FOREIGN KEY FK_4D851C6281572CB8');
        $this->addSql('DROP TABLE namitka');
    }
}
