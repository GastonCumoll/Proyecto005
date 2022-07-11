<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220711114420 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE digesto_viejo');
        $this->addSql('DROP TABLE norma_prueba1');
        $this->addSql('DROP TABLE norma_usuario');
        $this->addSql('DROP TABLE spip_auteurs');
        $this->addSql('DROP TABLE spip_auteurs_liens');
        $this->addSql('ALTER TABLE auditoria DROP FOREIGN KEY FK_AF4BB49D5D340050');
        $this->addSql('ALTER TABLE auditoria DROP FOREIGN KEY FK_AF4BB49D2DE240FD');
        $this->addSql('DROP INDEX IDX_AF4BB49D5D340050 ON auditoria');
        $this->addSql('DROP INDEX IDX_AF4BB49D2DE240FD ON auditoria');
        $this->addSql('ALTER TABLE auditoria ADD usuario_id INT DEFAULT NULL, ADD accion VARCHAR(255) DEFAULT NULL, DROP usuario_cargador_id, DROP usuario_modificador_id');
        $this->addSql('ALTER TABLE auditoria ADD CONSTRAINT FK_AF4BB49DDB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('CREATE INDEX IDX_AF4BB49DDB38439E ON auditoria (usuario_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE digesto_viejo (link_id INT UNSIGNED NOT NULL, url VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, route VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, title VARCHAR(400) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, description LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, indexdate DATETIME DEFAULT NULL, md5sum VARCHAR(32) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, published TINYINT(1) DEFAULT 1 NOT NULL, state INT DEFAULT 1, access INT DEFAULT 0, language VARCHAR(8) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, publish_start_date DATETIME DEFAULT NULL, publish_end_date DATETIME DEFAULT NULL, start_date DATETIME DEFAULT NULL, end_date DATETIME DEFAULT NULL, list_price DOUBLE PRECISION UNSIGNED DEFAULT \'0\' NOT NULL, sale_price DOUBLE PRECISION UNSIGNED DEFAULT \'0\' NOT NULL, type_id INT NOT NULL, object MEDIUMBLOB NOT NULL) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE norma_prueba1 (id INT AUTO_INCREMENT NOT NULL, tipo_norma_id INT NOT NULL, decreto_promulgacion_id INT DEFAULT NULL, fecha_sancion DATE DEFAULT NULL, fecha_publicacion DATE DEFAULT NULL, titulo VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, texto LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, resumen LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, fecha_publicacion_boletin DATE DEFAULT NULL, estado VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, numero VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, fecha_promulgacion DATE DEFAULT NULL, rela TINYINT(1) NOT NULL, INDEX IDX_3EF6217E36AA9857 (tipo_norma_id), INDEX IDX_3EF6217E3B526322 (decreto_promulgacion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE norma_usuario (norma_id INT NOT NULL, usuario_id INT NOT NULL, fecha DATETIME DEFAULT NULL, INDEX IDX_5BE71C49DB38439E (usuario_id), INDEX IDX_5BE71C49E06FC29E (norma_id), PRIMARY KEY(norma_id, usuario_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE spip_auteurs (id_auteur BIGINT NOT NULL, nom TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, bio TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, email TINYTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, nom_site TINYTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, url_site TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, login VARCHAR(255) CHARACTER SET latin1 NOT NULL COLLATE `latin1_bin`, pass TINYTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, low_sec TINYTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, statut VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'0\' NOT NULL COLLATE `utf8mb4_general_ci`, maj DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, pgp TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, htpass TINYTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, en_ligne DATETIME DEFAULT NULL, imessage VARCHAR(3) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, messagerie VARCHAR(3) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, alea_actuel TINYTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, alea_futur TINYTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, prefs TINYTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, cookie_oubli TINYTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, source VARCHAR(10) CHARACTER SET utf8mb4 DEFAULT \'spip\' NOT NULL COLLATE `utf8mb4_general_ci`, lang VARCHAR(10) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_general_ci`, extra LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, webmestre VARCHAR(3) CHARACTER SET utf8mb4 DEFAULT \'non\' NOT NULL COLLATE `utf8mb4_general_ci`) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE spip_auteurs_liens (id_auteur BIGINT DEFAULT 0 NOT NULL, id_objet BIGINT DEFAULT 0 NOT NULL, objet VARCHAR(25) CHARACTER SET utf8 DEFAULT \'\' NOT NULL COLLATE `utf8_general_ci`, vu VARCHAR(6) CHARACTER SET utf8 DEFAULT \'non\' NOT NULL COLLATE `utf8_general_ci`, INDEX id_auteur (id_auteur), INDEX id_objet (id_objet), INDEX objet (objet), PRIMARY KEY(id_auteur, id_objet, objet)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('ALTER TABLE norma_usuario ADD CONSTRAINT FK_5BE71C49E06FC29E FOREIGN KEY (norma_id) REFERENCES norma (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE norma_usuario ADD CONSTRAINT FK_5BE71C49DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE archivo CHANGE ruta ruta VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE nombre nombre VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE tipo tipo VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE area CHANGE nombre nombre VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE auditoria DROP FOREIGN KEY FK_AF4BB49DDB38439E');
        $this->addSql('DROP INDEX IDX_AF4BB49DDB38439E ON auditoria');
        $this->addSql('ALTER TABLE auditoria ADD usuario_modificador_id INT DEFAULT NULL, DROP accion, CHANGE estado_anterior estado_anterior VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE estado_actual estado_actual VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE usuario_id usuario_cargador_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE auditoria ADD CONSTRAINT FK_AF4BB49D5D340050 FOREIGN KEY (usuario_cargador_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE auditoria ADD CONSTRAINT FK_AF4BB49D2DE240FD FOREIGN KEY (usuario_modificador_id) REFERENCES usuario (id)');
        $this->addSql('CREATE INDEX IDX_AF4BB49D5D340050 ON auditoria (usuario_cargador_id)');
        $this->addSql('CREATE INDEX IDX_AF4BB49D2DE240FD ON auditoria (usuario_modificador_id)');
        $this->addSql('ALTER TABLE consulta CHANGE nombre nombre VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE numero_tel numero_tel VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE texto texto LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE etiqueta CHANGE nombre nombre VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE item CHANGE nombre nombre VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE url url VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE contenido contenido LONGTEXT DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE norma CHANGE titulo titulo VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE texto texto LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE resumen resumen LONGTEXT DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE estado estado VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE numero numero VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE prueba CHANGE titulo titulo VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE texto texto LONGTEXT DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE texto_largo texto_largo VARCHAR(10000) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE prueba_norma CHANGE titulo titulo VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE texto texto LONGTEXT DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE relacion CHANGE descripcion descripcion VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE resumen resumen LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE usuario usuario VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE tipo_consulta CHANGE nombre nombre VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE tipo_norma CHANGE nombre nombre VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE tipo_relacion CHANGE nombre nombre VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE usuario CHANGE nombre nombre VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE rol rol VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
