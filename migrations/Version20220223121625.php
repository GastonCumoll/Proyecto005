<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220223121625 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE norma_norma');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE norma_norma (norma_source INT NOT NULL, norma_target INT NOT NULL, INDEX IDX_F4BF2606187B9FF5 (norma_target), INDEX IDX_F4BF260619ECF7A (norma_source), PRIMARY KEY(norma_source, norma_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE norma_norma ADD CONSTRAINT FK_F4BF2606187B9FF5 FOREIGN KEY (norma_target) REFERENCES norma (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE norma_norma ADD CONSTRAINT FK_F4BF260619ECF7A FOREIGN KEY (norma_source) REFERENCES norma (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE capitulo CHANGE nombre nombre VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE norma CHANGE titulo titulo VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE texto texto LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE resumen resumen LONGTEXT DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE estado estado VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE etiquetas etiquetas LONGTEXT DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\', CHANGE numero numero VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE tema CHANGE nombre nombre VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE tipo_norma CHANGE nombre nombre VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE titulo CHANGE nombre nombre VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
