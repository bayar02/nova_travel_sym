<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250427044231 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE planning_event DROP FOREIGN KEY FK_AA02B03884425363
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE planning_event DROP FOREIGN KEY FK_AA02B038D52B4B97
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE planning_event
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD reset_token VARCHAR(255) DEFAULT NULL, CHANGE cin cin VARCHAR(255) NOT NULL, CHANGE tel tel INT NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE planning_event (id_planning INT NOT NULL, id_event INT NOT NULL, INDEX IDX_AA02B03884425363 (id_planning), INDEX IDX_AA02B038D52B4B97 (id_event), PRIMARY KEY(id_planning, id_event)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE planning_event ADD CONSTRAINT FK_AA02B03884425363 FOREIGN KEY (id_planning) REFERENCES planning (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE planning_event ADD CONSTRAINT FK_AA02B038D52B4B97 FOREIGN KEY (id_event) REFERENCES event (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user DROP reset_token, CHANGE cin cin VARCHAR(255) DEFAULT NULL, CHANGE tel tel INT DEFAULT NULL
        SQL);
    }
}
