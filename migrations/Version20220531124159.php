<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220531124159 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE spip_articles');
        $this->addSql('DROP TABLE spip_mots_liens');
        $this->addSql('DROP TABLE spip_rubriques');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE spip_articles (id_article BIGINT NOT NULL, surtitre TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, titre TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, soustitre TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, id_rubrique BIGINT DEFAULT 0 NOT NULL, descriptif TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, chapo MEDIUMTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, texte LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, ps MEDIUMTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, date DATETIME DEFAULT NULL, statut VARCHAR(10) CHARACTER SET utf8mb4 DEFAULT \'0\' NOT NULL COLLATE `utf8mb4_general_ci`, id_secteur BIGINT DEFAULT 0 NOT NULL, maj DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, export VARCHAR(10) CHARACTER SET utf8mb4 DEFAULT \'oui\' COLLATE `utf8mb4_general_ci`, date_redac DATETIME DEFAULT NULL, visites INT DEFAULT 0 NOT NULL, referers INT DEFAULT 0 NOT NULL, popularite DOUBLE PRECISION DEFAULT \'0\' NOT NULL, accepter_forum CHAR(3) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_general_ci`, date_modif DATETIME DEFAULT NULL, lang VARCHAR(10) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_general_ci`, langue_choisie VARCHAR(3) CHARACTER SET utf8mb4 DEFAULT \'non\' COLLATE `utf8mb4_general_ci`, id_trad BIGINT DEFAULT 0 NOT NULL, extra LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, id_version INT UNSIGNED DEFAULT 0 NOT NULL, nom_site TINYTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, url_site TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, virtuel TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE spip_mots_liens (id_mot BIGINT DEFAULT 0 NOT NULL, id_objet BIGINT DEFAULT 0 NOT NULL, objet VARCHAR(25) CHARACTER SET utf8 DEFAULT \'\' NOT NULL COLLATE `utf8_general_ci`, INDEX id_objet (id_objet), INDEX objet (objet), INDEX id_mot (id_mot), PRIMARY KEY(id_mot, id_objet, objet)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE spip_rubriques (id_rubrique BIGINT NOT NULL, id_parent BIGINT DEFAULT 0 NOT NULL, titre TEXT CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, descriptif TEXT CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, texte LONGTEXT CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, id_secteur BIGINT DEFAULT 0 NOT NULL, maj DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, statut VARCHAR(10) CHARACTER SET latin1 DEFAULT \'0\' NOT NULL COLLATE `latin1_swedish_ci`, date DATETIME NOT NULL, lang VARCHAR(10) CHARACTER SET latin1 DEFAULT \'\' NOT NULL COLLATE `latin1_swedish_ci`, langue_choisie VARCHAR(3) CHARACTER SET latin1 DEFAULT \'non\' COLLATE `latin1_swedish_ci`, extra LONGTEXT CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, statut_tmp VARCHAR(10) CHARACTER SET latin1 DEFAULT \'0\' NOT NULL COLLATE `latin1_swedish_ci`, date_tmp DATETIME NOT NULL, profondeur SMALLINT DEFAULT 0 NOT NULL) DEFAULT CHARACTER SET latin1 COLLATE `latin1_unicode_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('ALTER TABLE archivo CHANGE ruta ruta VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE nombre nombre VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE tipo tipo VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE area CHANGE nombre nombre VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE etiqueta CHANGE nombre nombre VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE item CHANGE nombre nombre VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE url url VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE contenido contenido LONGTEXT DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE norma CHANGE titulo titulo VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE texto texto LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE resumen resumen LONGTEXT DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE estado estado VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE numero numero VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE relacion CHANGE descripcion descripcion VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE resumen resumen LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE usuario usuario VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE tipo_norma CHANGE nombre nombre VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE tipo_relacion CHANGE nombre nombre VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
