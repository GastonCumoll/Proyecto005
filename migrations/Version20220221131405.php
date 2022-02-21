<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220221131405 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE capitulo (id INT AUTO_INCREMENT NOT NULL, titulo_id INT NOT NULL, nombre VARCHAR(255) NOT NULL, INDEX IDX_2BA5E28F61AD3496 (titulo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE decreto (id INT AUTO_INCREMENT NOT NULL, fecha_sancion DATE DEFAULT NULL, fecha_publicacion DATE DEFAULT NULL, titulo VARCHAR(255) NOT NULL, texto LONGTEXT NOT NULL, resumen LONGTEXT DEFAULT NULL, fecha_publicacion_boletin DATE DEFAULT NULL, estado VARCHAR(255) NOT NULL, etiquetas LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', numero VARCHAR(255) NOT NULL, tipo VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ley (id INT AUTO_INCREMENT NOT NULL, fecha_sancion DATE DEFAULT NULL, fecha_publicacion DATE DEFAULT NULL, titulo VARCHAR(255) NOT NULL, texto LONGTEXT NOT NULL, resumen LONGTEXT DEFAULT NULL, fecha_publicacion_boletin DATE DEFAULT NULL, estado VARCHAR(255) NOT NULL, etiquetas LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', numero INT NOT NULL, fecha_promulgacion DATE DEFAULT NULL, tipo VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ordenanza (id INT AUTO_INCREMENT NOT NULL, modificado_por_ordenanza_id INT DEFAULT NULL, decreto_promulgacion_id INT NOT NULL, fecha_sancion DATE DEFAULT NULL, fecha_publicacion DATE DEFAULT NULL, titulo VARCHAR(255) NOT NULL, texto LONGTEXT NOT NULL, resumen LONGTEXT DEFAULT NULL, fecha_publicacion_boletin DATE DEFAULT NULL, estado VARCHAR(255) NOT NULL, etiquetas LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', numero INT NOT NULL, fecha_promulgacion DATE DEFAULT NULL, UNIQUE INDEX UNIQ_A4D245FB9235FC86 (modificado_por_ordenanza_id), UNIQUE INDEX UNIQ_A4D245FB3B526322 (decreto_promulgacion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE resolucion (id INT AUTO_INCREMENT NOT NULL, fecha_sancion DATE DEFAULT NULL, fecha_publicacion DATE DEFAULT NULL, titulo VARCHAR(255) NOT NULL, texto LONGTEXT NOT NULL, resumen LONGTEXT DEFAULT NULL, fecha_publicacion_boletin DATE DEFAULT NULL, estado VARCHAR(255) NOT NULL, etiquetas LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', numero VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tema (id INT AUTO_INCREMENT NOT NULL, capitulo_id INT NOT NULL, nombre VARCHAR(255) NOT NULL, INDEX IDX_61E3A53846DC5FAF (capitulo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tema_norma (tema_id INT NOT NULL, norma_id INT NOT NULL, INDEX IDX_69FF1705A64A8A17 (tema_id), INDEX IDX_69FF1705E06FC29E (norma_id), PRIMARY KEY(tema_id, norma_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE titulo (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE capitulo ADD CONSTRAINT FK_2BA5E28F61AD3496 FOREIGN KEY (titulo_id) REFERENCES titulo (id)');
        $this->addSql('ALTER TABLE ordenanza ADD CONSTRAINT FK_A4D245FB9235FC86 FOREIGN KEY (modificado_por_ordenanza_id) REFERENCES ordenanza (id)');
        $this->addSql('ALTER TABLE ordenanza ADD CONSTRAINT FK_A4D245FB3B526322 FOREIGN KEY (decreto_promulgacion_id) REFERENCES decreto (id)');
        $this->addSql('ALTER TABLE tema ADD CONSTRAINT FK_61E3A53846DC5FAF FOREIGN KEY (capitulo_id) REFERENCES capitulo (id)');
        $this->addSql('ALTER TABLE tema_norma ADD CONSTRAINT FK_69FF1705A64A8A17 FOREIGN KEY (tema_id) REFERENCES tema (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tema_norma ADD CONSTRAINT FK_69FF1705E06FC29E FOREIGN KEY (norma_id) REFERENCES norma (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tema DROP FOREIGN KEY FK_61E3A53846DC5FAF');
        $this->addSql('ALTER TABLE ordenanza DROP FOREIGN KEY FK_A4D245FB3B526322');
        $this->addSql('ALTER TABLE ordenanza DROP FOREIGN KEY FK_A4D245FB9235FC86');
        $this->addSql('ALTER TABLE tema_norma DROP FOREIGN KEY FK_69FF1705A64A8A17');
        $this->addSql('ALTER TABLE capitulo DROP FOREIGN KEY FK_2BA5E28F61AD3496');
        $this->addSql('DROP TABLE capitulo');
        $this->addSql('DROP TABLE decreto');
        $this->addSql('DROP TABLE ley');
        $this->addSql('DROP TABLE ordenanza');
        $this->addSql('DROP TABLE resolucion');
        $this->addSql('DROP TABLE tema');
        $this->addSql('DROP TABLE tema_norma');
        $this->addSql('DROP TABLE titulo');
        $this->addSql('ALTER TABLE norma CHANGE titulo titulo VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE texto texto LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE resumen resumen LONGTEXT DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE estado estado VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE etiquetas etiquetas LONGTEXT DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE prueba CHANGE nombre nombre VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
