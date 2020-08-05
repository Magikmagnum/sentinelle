<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200805164951 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE absences (id INT AUTO_INCREMENT NOT NULL, cours_id INT DEFAULT NULL, eleve_id INT DEFAULT NULL, date_at DATETIME NOT NULL, heure INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_F9C0EFFF7ECF78B0 (cours_id), INDEX IDX_F9C0EFFFA6CC7B2 (eleve_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE classes (id INT AUTO_INCREMENT NOT NULL, session_id INT DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, classe INT NOT NULL, examen TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_2ED7EC5613FECDF (session_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cours (id INT AUTO_INCREMENT NOT NULL, professeur_id INT DEFAULT NULL, classe_id INT DEFAULT NULL, jour INT NOT NULL, periode INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_FDCA8C9CBAB22EE9 (professeur_id), UNIQUE INDEX UNIQ_FDCA8C9C8F5EA509 (classe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ecoles (id INT AUTO_INCREMENT NOT NULL, manager_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, devise VARCHAR(255) DEFAULT NULL, INDEX IDX_C46758A2783E3463 (manager_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE eleves (id INT AUTO_INCREMENT NOT NULL, classe_id INT DEFAULT NULL, tuteur_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) DEFAULT NULL, sexe TINYINT(1) NOT NULL, data_de_naissance_at DATETIME NOT NULL, lieu VARCHAR(255) NOT NULL, nationalite VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, telephone VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_383B09B18F5EA509 (classe_id), INDEX IDX_383B09B186EC68D8 (tuteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evaluations (id INT AUTO_INCREMENT NOT NULL, professeur_id INT DEFAULT NULL, classe_id INT DEFAULT NULL, type INT NOT NULL, date_at DATETIME DEFAULT NULL, duree DOUBLE PRECISION DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, statut TINYINT(1) DEFAULT NULL, INDEX IDX_3B72691DBAB22EE9 (professeur_id), INDEX IDX_3B72691D8F5EA509 (classe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE matieres (id INT AUTO_INCREMENT NOT NULL, matiere VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, abreger VARCHAR(8) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notes (id INT AUTO_INCREMENT NOT NULL, professeur_id INT DEFAULT NULL, eleve_id INT DEFAULT NULL, evaluation_id INT DEFAULT NULL, note DOUBLE PRECISION DEFAULT NULL, dispense TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_11BA68CBAB22EE9 (professeur_id), INDEX IDX_11BA68CA6CC7B2 (eleve_id), INDEX IDX_11BA68C456C5646 (evaluation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE professeurs (id INT AUTO_INCREMENT NOT NULL, matiere_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, telephone VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_92CA41B9F46CD258 (matiere_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE professeurs_classes (professeurs_id INT NOT NULL, classes_id INT NOT NULL, INDEX IDX_46D5655B3E1D55D7 (professeurs_id), INDEX IDX_46D5655B9E225B24 (classes_id), PRIMARY KEY(professeurs_id, classes_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sessions (id INT AUTO_INCREMENT NOT NULL, ecole_id INT DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, debut_at DATE NOT NULL, fin_at DATE DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_9A609D1377EF1B1E (ecole_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tuteurs (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) DEFAULT NULL, profession VARCHAR(255) DEFAULT NULL, sexe TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE absences ADD CONSTRAINT FK_F9C0EFFF7ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id)');
        $this->addSql('ALTER TABLE absences ADD CONSTRAINT FK_F9C0EFFFA6CC7B2 FOREIGN KEY (eleve_id) REFERENCES eleves (id)');
        $this->addSql('ALTER TABLE classes ADD CONSTRAINT FK_2ED7EC5613FECDF FOREIGN KEY (session_id) REFERENCES sessions (id)');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9CBAB22EE9 FOREIGN KEY (professeur_id) REFERENCES professeurs (id)');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9C8F5EA509 FOREIGN KEY (classe_id) REFERENCES classes (id)');
        $this->addSql('ALTER TABLE ecoles ADD CONSTRAINT FK_C46758A2783E3463 FOREIGN KEY (manager_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE eleves ADD CONSTRAINT FK_383B09B18F5EA509 FOREIGN KEY (classe_id) REFERENCES classes (id)');
        $this->addSql('ALTER TABLE eleves ADD CONSTRAINT FK_383B09B186EC68D8 FOREIGN KEY (tuteur_id) REFERENCES tuteurs (id)');
        $this->addSql('ALTER TABLE evaluations ADD CONSTRAINT FK_3B72691DBAB22EE9 FOREIGN KEY (professeur_id) REFERENCES professeurs (id)');
        $this->addSql('ALTER TABLE evaluations ADD CONSTRAINT FK_3B72691D8F5EA509 FOREIGN KEY (classe_id) REFERENCES classes (id)');
        $this->addSql('ALTER TABLE notes ADD CONSTRAINT FK_11BA68CBAB22EE9 FOREIGN KEY (professeur_id) REFERENCES professeurs (id)');
        $this->addSql('ALTER TABLE notes ADD CONSTRAINT FK_11BA68CA6CC7B2 FOREIGN KEY (eleve_id) REFERENCES eleves (id)');
        $this->addSql('ALTER TABLE notes ADD CONSTRAINT FK_11BA68C456C5646 FOREIGN KEY (evaluation_id) REFERENCES evaluations (id)');
        $this->addSql('ALTER TABLE professeurs ADD CONSTRAINT FK_92CA41B9F46CD258 FOREIGN KEY (matiere_id) REFERENCES matieres (id)');
        $this->addSql('ALTER TABLE professeurs_classes ADD CONSTRAINT FK_46D5655B3E1D55D7 FOREIGN KEY (professeurs_id) REFERENCES professeurs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE professeurs_classes ADD CONSTRAINT FK_46D5655B9E225B24 FOREIGN KEY (classes_id) REFERENCES classes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sessions ADD CONSTRAINT FK_9A609D1377EF1B1E FOREIGN KEY (ecole_id) REFERENCES ecoles (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cours DROP FOREIGN KEY FK_FDCA8C9C8F5EA509');
        $this->addSql('ALTER TABLE eleves DROP FOREIGN KEY FK_383B09B18F5EA509');
        $this->addSql('ALTER TABLE evaluations DROP FOREIGN KEY FK_3B72691D8F5EA509');
        $this->addSql('ALTER TABLE professeurs_classes DROP FOREIGN KEY FK_46D5655B9E225B24');
        $this->addSql('ALTER TABLE absences DROP FOREIGN KEY FK_F9C0EFFF7ECF78B0');
        $this->addSql('ALTER TABLE sessions DROP FOREIGN KEY FK_9A609D1377EF1B1E');
        $this->addSql('ALTER TABLE absences DROP FOREIGN KEY FK_F9C0EFFFA6CC7B2');
        $this->addSql('ALTER TABLE notes DROP FOREIGN KEY FK_11BA68CA6CC7B2');
        $this->addSql('ALTER TABLE notes DROP FOREIGN KEY FK_11BA68C456C5646');
        $this->addSql('ALTER TABLE professeurs DROP FOREIGN KEY FK_92CA41B9F46CD258');
        $this->addSql('ALTER TABLE cours DROP FOREIGN KEY FK_FDCA8C9CBAB22EE9');
        $this->addSql('ALTER TABLE evaluations DROP FOREIGN KEY FK_3B72691DBAB22EE9');
        $this->addSql('ALTER TABLE notes DROP FOREIGN KEY FK_11BA68CBAB22EE9');
        $this->addSql('ALTER TABLE professeurs_classes DROP FOREIGN KEY FK_46D5655B3E1D55D7');
        $this->addSql('ALTER TABLE classes DROP FOREIGN KEY FK_2ED7EC5613FECDF');
        $this->addSql('ALTER TABLE eleves DROP FOREIGN KEY FK_383B09B186EC68D8');
        $this->addSql('DROP TABLE absences');
        $this->addSql('DROP TABLE classes');
        $this->addSql('DROP TABLE cours');
        $this->addSql('DROP TABLE ecoles');
        $this->addSql('DROP TABLE eleves');
        $this->addSql('DROP TABLE evaluations');
        $this->addSql('DROP TABLE matieres');
        $this->addSql('DROP TABLE notes');
        $this->addSql('DROP TABLE professeurs');
        $this->addSql('DROP TABLE professeurs_classes');
        $this->addSql('DROP TABLE sessions');
        $this->addSql('DROP TABLE tuteurs');
    }
}
