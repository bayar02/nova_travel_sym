<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250414201050 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE reclamation CHANGE id_user id_user INT DEFAULT NULL, CHANGE date_reclamation date_reclamation VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reclamation ADD CONSTRAINT FK_CE6064046B3CA4B FOREIGN KEY (id_user) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX id_user ON reclamation
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_CE6064046B3CA4B ON reclamation (id_user)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reponse DROP FOREIGN KEY reponse_ibfk_1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reponse DROP FOREIGN KEY reponse_ibfk_1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reponse CHANGE id_reclamation id_reclamation INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC7D672A9F3 FOREIGN KEY (id_reclamation) REFERENCES reclamation (id)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX id_reclamation ON reponse
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_5FB6DEC7D672A9F3 ON reponse (id_reclamation)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reponse ADD CONSTRAINT reponse_ibfk_1 FOREIGN KEY (id_reclamation) REFERENCES reclamation (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation_hebergement DROP FOREIGN KEY reservation_hebergement_ibfk_1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation_hebergement DROP FOREIGN KEY reservation_hebergement_ibfk_2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation_hebergement DROP FOREIGN KEY reservation_hebergement_ibfk_1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation_hebergement DROP FOREIGN KEY reservation_hebergement_ibfk_2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation_hebergement CHANGE id_user id_user INT DEFAULT NULL, CHANGE id_hebergement id_hebergement INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation_hebergement ADD CONSTRAINT FK_843E00C06B3CA4B FOREIGN KEY (id_user) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation_hebergement ADD CONSTRAINT FK_843E00C05040106B FOREIGN KEY (id_hebergement) REFERENCES hebergement (id)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX id_user ON reservation_hebergement
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_843E00C06B3CA4B ON reservation_hebergement (id_user)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX id_hebergement ON reservation_hebergement
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_843E00C05040106B ON reservation_hebergement (id_hebergement)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation_hebergement ADD CONSTRAINT reservation_hebergement_ibfk_1 FOREIGN KEY (id_user) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation_hebergement ADD CONSTRAINT reservation_hebergement_ibfk_2 FOREIGN KEY (id_hebergement) REFERENCES hebergement (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation_vol DROP FOREIGN KEY reservation_vol_ibfk_1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation_vol DROP FOREIGN KEY reservation_vol_ibfk_2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation_vol DROP FOREIGN KEY reservation_vol_ibfk_1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation_vol DROP FOREIGN KEY reservation_vol_ibfk_2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation_vol CHANGE id_user id_user INT DEFAULT NULL, CHANGE id_vol id_vol INT DEFAULT NULL, CHANGE classe classe VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation_vol ADD CONSTRAINT FK_5C5EBA346B3CA4B FOREIGN KEY (id_user) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation_vol ADD CONSTRAINT FK_5C5EBA3497F87FB1 FOREIGN KEY (id_vol) REFERENCES vol (id)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX id_user ON reservation_vol
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_5C5EBA346B3CA4B ON reservation_vol (id_user)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX id_vol ON reservation_vol
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_5C5EBA3497F87FB1 ON reservation_vol (id_vol)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation_vol ADD CONSTRAINT reservation_vol_ibfk_1 FOREIGN KEY (id_user) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation_vol ADD CONSTRAINT reservation_vol_ibfk_2 FOREIGN KEY (id_vol) REFERENCES vol (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX cin ON user
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD roles JSON NOT NULL COMMENT '(DC2Type:json)', ADD is_verified TINYINT(1) NOT NULL, DROP role, CHANGE nom nom VARCHAR(255) NOT NULL, CHANGE prenom prenom VARCHAR(255) NOT NULL, CHANGE cin cin VARCHAR(255) NOT NULL, CHANGE mail mail VARCHAR(180) NOT NULL, CHANGE tel tel INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_8D93D6495126AC48 ON user (mail)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vol CHANGE compagnie compagnie VARCHAR(255) NOT NULL, CHANGE aeroport_depart aeroport_depart VARCHAR(255) NOT NULL, CHANGE aeroport_arrivee aeroport_arrivee VARCHAR(255) NOT NULL, CHANGE destination destination VARCHAR(255) NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE reclamation DROP FOREIGN KEY FK_CE6064046B3CA4B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reclamation DROP FOREIGN KEY FK_CE6064046B3CA4B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reclamation CHANGE id_user id_user INT NOT NULL, CHANGE date_reclamation date_reclamation DATE NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_ce6064046b3ca4b ON reclamation
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX id_user ON reclamation (id_user)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reclamation ADD CONSTRAINT FK_CE6064046B3CA4B FOREIGN KEY (id_user) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC7D672A9F3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC7D672A9F3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reponse CHANGE id_reclamation id_reclamation INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reponse ADD CONSTRAINT reponse_ibfk_1 FOREIGN KEY (id_reclamation) REFERENCES reclamation (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_5fb6dec7d672a9f3 ON reponse
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX id_reclamation ON reponse (id_reclamation)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC7D672A9F3 FOREIGN KEY (id_reclamation) REFERENCES reclamation (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation_hebergement DROP FOREIGN KEY FK_843E00C06B3CA4B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation_hebergement DROP FOREIGN KEY FK_843E00C05040106B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation_hebergement DROP FOREIGN KEY FK_843E00C06B3CA4B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation_hebergement DROP FOREIGN KEY FK_843E00C05040106B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation_hebergement CHANGE id_user id_user INT NOT NULL, CHANGE id_hebergement id_hebergement INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation_hebergement ADD CONSTRAINT reservation_hebergement_ibfk_1 FOREIGN KEY (id_user) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation_hebergement ADD CONSTRAINT reservation_hebergement_ibfk_2 FOREIGN KEY (id_hebergement) REFERENCES hebergement (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_843e00c06b3ca4b ON reservation_hebergement
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX id_user ON reservation_hebergement (id_user)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_843e00c05040106b ON reservation_hebergement
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX id_hebergement ON reservation_hebergement (id_hebergement)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation_hebergement ADD CONSTRAINT FK_843E00C06B3CA4B FOREIGN KEY (id_user) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation_hebergement ADD CONSTRAINT FK_843E00C05040106B FOREIGN KEY (id_hebergement) REFERENCES hebergement (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation_vol DROP FOREIGN KEY FK_5C5EBA346B3CA4B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation_vol DROP FOREIGN KEY FK_5C5EBA3497F87FB1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation_vol DROP FOREIGN KEY FK_5C5EBA346B3CA4B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation_vol DROP FOREIGN KEY FK_5C5EBA3497F87FB1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation_vol CHANGE id_user id_user INT NOT NULL, CHANGE id_vol id_vol INT NOT NULL, CHANGE classe classe VARCHAR(50) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation_vol ADD CONSTRAINT reservation_vol_ibfk_1 FOREIGN KEY (id_user) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation_vol ADD CONSTRAINT reservation_vol_ibfk_2 FOREIGN KEY (id_vol) REFERENCES vol (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_5c5eba346b3ca4b ON reservation_vol
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX id_user ON reservation_vol (id_user)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_5c5eba3497f87fb1 ON reservation_vol
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX id_vol ON reservation_vol (id_vol)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation_vol ADD CONSTRAINT FK_5C5EBA346B3CA4B FOREIGN KEY (id_user) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation_vol ADD CONSTRAINT FK_5C5EBA3497F87FB1 FOREIGN KEY (id_vol) REFERENCES vol (id)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_8D93D6495126AC48 ON user
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD role VARCHAR(50) NOT NULL, DROP roles, DROP is_verified, CHANGE mail mail VARCHAR(255) NOT NULL, CHANGE nom nom VARCHAR(50) NOT NULL, CHANGE prenom prenom VARCHAR(50) NOT NULL, CHANGE cin cin VARCHAR(20) DEFAULT NULL, CHANGE tel tel INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX cin ON user (cin)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vol CHANGE compagnie compagnie VARCHAR(100) NOT NULL, CHANGE aeroport_depart aeroport_depart VARCHAR(100) NOT NULL, CHANGE aeroport_arrivee aeroport_arrivee VARCHAR(100) NOT NULL, CHANGE destination destination VARCHAR(100) NOT NULL
        SQL);
    }
}
