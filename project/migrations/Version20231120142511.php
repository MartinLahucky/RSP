<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231120142511 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE clanek DROP FOREIGN KEY FK_60591765530ADD1B');
        $this->addSql('ALTER TABLE clanek DROP FOREIGN KEY FK_605917659D86650F');
        $this->addSql('DROP INDEX IDX_605917659D86650F ON clanek');
        $this->addSql('DROP INDEX IDX_60591765530ADD1B ON clanek');
        $this->addSql('ALTER TABLE clanek ADD recenzni_rizeni_id INT NOT NULL, ADD user_id INT NOT NULL, DROP id_recenzni_rizeni_id, DROP user_id_id');
        $this->addSql('ALTER TABLE clanek ADD CONSTRAINT FK_60591765D0286FB4 FOREIGN KEY (recenzni_rizeni_id) REFERENCES recenzni_rizeni (id)');
        $this->addSql('ALTER TABLE clanek ADD CONSTRAINT FK_60591765A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_60591765D0286FB4 ON clanek (recenzni_rizeni_id)');
        $this->addSql('CREATE INDEX IDX_60591765A76ED395 ON clanek (user_id)');
        $this->addSql('ALTER TABLE komentar_clanek DROP FOREIGN KEY FK_3415CE539D86650F');
        $this->addSql('ALTER TABLE komentar_clanek DROP FOREIGN KEY FK_3415CE53E0BC71B4');
        $this->addSql('DROP INDEX IDX_3415CE53E0BC71B4 ON komentar_clanek');
        $this->addSql('DROP INDEX IDX_3415CE539D86650F ON komentar_clanek');
        $this->addSql('ALTER TABLE komentar_clanek ADD verze_clanku_id INT NOT NULL, ADD user_id INT NOT NULL, DROP verze_clanku_id_id, DROP user_id_id');
        $this->addSql('ALTER TABLE komentar_clanek ADD CONSTRAINT FK_3415CE5350D48026 FOREIGN KEY (verze_clanku_id) REFERENCES verze_clanku (id)');
        $this->addSql('ALTER TABLE komentar_clanek ADD CONSTRAINT FK_3415CE53A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_3415CE5350D48026 ON komentar_clanek (verze_clanku_id)');
        $this->addSql('CREATE INDEX IDX_3415CE53A76ED395 ON komentar_clanek (user_id)');
        $this->addSql('ALTER TABLE namitka DROP FOREIGN KEY FK_4D851C6281572CB8');
        $this->addSql('DROP INDEX UNIQ_4D851C6281572CB8 ON namitka');
        $this->addSql('ALTER TABLE namitka CHANGE clanek_id_id clanek_id INT NOT NULL');
        $this->addSql('ALTER TABLE namitka ADD CONSTRAINT FK_4D851C623F69A15D FOREIGN KEY (clanek_id) REFERENCES clanek (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4D851C623F69A15D ON namitka (clanek_id)');
        $this->addSql('ALTER TABLE posudek DROP FOREIGN KEY FK_E7013CA59D86650F');
        $this->addSql('ALTER TABLE posudek DROP FOREIGN KEY FK_E7013CA581572CB8');
        $this->addSql('DROP INDEX IDX_E7013CA581572CB8 ON posudek');
        $this->addSql('DROP INDEX IDX_E7013CA59D86650F ON posudek');
        $this->addSql('ALTER TABLE posudek ADD clanek_id INT NOT NULL, ADD user_id INT NOT NULL, DROP clanek_id_id, DROP user_id_id');
        $this->addSql('ALTER TABLE posudek ADD CONSTRAINT FK_E7013CA53F69A15D FOREIGN KEY (clanek_id) REFERENCES clanek (id)');
        $this->addSql('ALTER TABLE posudek ADD CONSTRAINT FK_E7013CA5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_E7013CA53F69A15D ON posudek (clanek_id)');
        $this->addSql('CREATE INDEX IDX_E7013CA5A76ED395 ON posudek (user_id)');
        $this->addSql('ALTER TABLE recenzni_rizeni DROP FOREIGN KEY FK_48A89B91338276F');
        $this->addSql('DROP INDEX UNIQ_48A89B91338276F ON recenzni_rizeni');
        $this->addSql('ALTER TABLE recenzni_rizeni CHANGE tisk_id_id tisk_id INT NOT NULL');
        $this->addSql('ALTER TABLE recenzni_rizeni ADD CONSTRAINT FK_48A89B9161E583EB FOREIGN KEY (tisk_id) REFERENCES tisk (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_48A89B9161E583EB ON recenzni_rizeni (tisk_id)');
        $this->addSql('ALTER TABLE verze_clanku DROP FOREIGN KEY FK_FE91554581572CB8');
        $this->addSql('DROP INDEX IDX_FE91554581572CB8 ON verze_clanku');
        $this->addSql('ALTER TABLE verze_clanku CHANGE clanek_id_id clanek_id INT NOT NULL');
        $this->addSql('ALTER TABLE verze_clanku ADD CONSTRAINT FK_FE9155453F69A15D FOREIGN KEY (clanek_id) REFERENCES clanek (id)');
        $this->addSql('CREATE INDEX IDX_FE9155453F69A15D ON verze_clanku (clanek_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE komentar_clanek DROP FOREIGN KEY FK_3415CE5350D48026');
        $this->addSql('ALTER TABLE komentar_clanek DROP FOREIGN KEY FK_3415CE53A76ED395');
        $this->addSql('DROP INDEX IDX_3415CE5350D48026 ON komentar_clanek');
        $this->addSql('DROP INDEX IDX_3415CE53A76ED395 ON komentar_clanek');
        $this->addSql('ALTER TABLE komentar_clanek ADD verze_clanku_id_id INT NOT NULL, ADD user_id_id INT NOT NULL, DROP verze_clanku_id, DROP user_id');
        $this->addSql('ALTER TABLE komentar_clanek ADD CONSTRAINT FK_3415CE539D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE komentar_clanek ADD CONSTRAINT FK_3415CE53E0BC71B4 FOREIGN KEY (verze_clanku_id_id) REFERENCES verze_clanku (id)');
        $this->addSql('CREATE INDEX IDX_3415CE53E0BC71B4 ON komentar_clanek (verze_clanku_id_id)');
        $this->addSql('CREATE INDEX IDX_3415CE539D86650F ON komentar_clanek (user_id_id)');
        $this->addSql('ALTER TABLE posudek DROP FOREIGN KEY FK_E7013CA53F69A15D');
        $this->addSql('ALTER TABLE posudek DROP FOREIGN KEY FK_E7013CA5A76ED395');
        $this->addSql('DROP INDEX IDX_E7013CA53F69A15D ON posudek');
        $this->addSql('DROP INDEX IDX_E7013CA5A76ED395 ON posudek');
        $this->addSql('ALTER TABLE posudek ADD clanek_id_id INT NOT NULL, ADD user_id_id INT NOT NULL, DROP clanek_id, DROP user_id');
        $this->addSql('ALTER TABLE posudek ADD CONSTRAINT FK_E7013CA59D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE posudek ADD CONSTRAINT FK_E7013CA581572CB8 FOREIGN KEY (clanek_id_id) REFERENCES clanek (id)');
        $this->addSql('CREATE INDEX IDX_E7013CA581572CB8 ON posudek (clanek_id_id)');
        $this->addSql('CREATE INDEX IDX_E7013CA59D86650F ON posudek (user_id_id)');
        $this->addSql('ALTER TABLE recenzni_rizeni DROP FOREIGN KEY FK_48A89B9161E583EB');
        $this->addSql('DROP INDEX UNIQ_48A89B9161E583EB ON recenzni_rizeni');
        $this->addSql('ALTER TABLE recenzni_rizeni CHANGE tisk_id tisk_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE recenzni_rizeni ADD CONSTRAINT FK_48A89B91338276F FOREIGN KEY (tisk_id_id) REFERENCES tisk (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_48A89B91338276F ON recenzni_rizeni (tisk_id_id)');
        $this->addSql('ALTER TABLE namitka DROP FOREIGN KEY FK_4D851C623F69A15D');
        $this->addSql('DROP INDEX UNIQ_4D851C623F69A15D ON namitka');
        $this->addSql('ALTER TABLE namitka CHANGE clanek_id clanek_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE namitka ADD CONSTRAINT FK_4D851C6281572CB8 FOREIGN KEY (clanek_id_id) REFERENCES clanek (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4D851C6281572CB8 ON namitka (clanek_id_id)');
        $this->addSql('ALTER TABLE clanek DROP FOREIGN KEY FK_60591765D0286FB4');
        $this->addSql('ALTER TABLE clanek DROP FOREIGN KEY FK_60591765A76ED395');
        $this->addSql('DROP INDEX IDX_60591765D0286FB4 ON clanek');
        $this->addSql('DROP INDEX IDX_60591765A76ED395 ON clanek');
        $this->addSql('ALTER TABLE clanek ADD id_recenzni_rizeni_id INT NOT NULL, ADD user_id_id INT NOT NULL, DROP recenzni_rizeni_id, DROP user_id');
        $this->addSql('ALTER TABLE clanek ADD CONSTRAINT FK_60591765530ADD1B FOREIGN KEY (id_recenzni_rizeni_id) REFERENCES recenzni_rizeni (id)');
        $this->addSql('ALTER TABLE clanek ADD CONSTRAINT FK_605917659D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_605917659D86650F ON clanek (user_id_id)');
        $this->addSql('CREATE INDEX IDX_60591765530ADD1B ON clanek (id_recenzni_rizeni_id)');
        $this->addSql('ALTER TABLE verze_clanku DROP FOREIGN KEY FK_FE9155453F69A15D');
        $this->addSql('DROP INDEX IDX_FE9155453F69A15D ON verze_clanku');
        $this->addSql('ALTER TABLE verze_clanku CHANGE clanek_id clanek_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE verze_clanku ADD CONSTRAINT FK_FE91554581572CB8 FOREIGN KEY (clanek_id_id) REFERENCES clanek (id)');
        $this->addSql('CREATE INDEX IDX_FE91554581572CB8 ON verze_clanku (clanek_id_id)');
    }
}
