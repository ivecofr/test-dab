<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240124085359 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE billet (bil_id INT AUTO_INCREMENT NOT NULL, bil_montantunitaire INT NOT NULL, bil_quantite INT NOT NULL, PRIMARY KEY(bil_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dab (dab_id INT AUTO_INCREMENT NOT NULL, dab_name VARCHAR(20) NOT NULL, dab_adresse LONGTEXT DEFAULT NULL, dab_rechargement DATETIME DEFAULT NULL, PRIMARY KEY(dab_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dab_bil (id_dab INT NOT NULL, id_bil INT NOT NULL, INDEX IDX_EAC9B27DF1610826 (id_dab), UNIQUE INDEX UNIQ_EAC9B27DDA8DD39B (id_bil), PRIMARY KEY(id_dab, id_bil)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE retrait (ret_id INT AUTO_INCREMENT NOT NULL, ret_dab INT DEFAULT NULL, ret_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ret_numcarte VARCHAR(50) DEFAULT NULL, INDEX IDX_D9846A51B11AE000 (ret_dab), PRIMARY KEY(ret_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ret_bil (id_ret INT NOT NULL, id_bil INT NOT NULL, INDEX IDX_9AF63BBD7972A7B1 (id_ret), UNIQUE INDEX UNIQ_9AF63BBDDA8DD39B (id_bil), PRIMARY KEY(id_ret, id_bil)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dab_bil ADD CONSTRAINT FK_EAC9B27DF1610826 FOREIGN KEY (id_dab) REFERENCES dab (dab_id)');
        $this->addSql('ALTER TABLE dab_bil ADD CONSTRAINT FK_EAC9B27DDA8DD39B FOREIGN KEY (id_bil) REFERENCES billet (bil_id)');
        $this->addSql('ALTER TABLE retrait ADD CONSTRAINT FK_D9846A51B11AE000 FOREIGN KEY (ret_dab) REFERENCES dab (dab_id)');
        $this->addSql('ALTER TABLE ret_bil ADD CONSTRAINT FK_9AF63BBD7972A7B1 FOREIGN KEY (id_ret) REFERENCES retrait (ret_id)');
        $this->addSql('ALTER TABLE ret_bil ADD CONSTRAINT FK_9AF63BBDDA8DD39B FOREIGN KEY (id_bil) REFERENCES billet (bil_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dab_bil DROP FOREIGN KEY FK_EAC9B27DDA8DD39B');
        $this->addSql('ALTER TABLE ret_bil DROP FOREIGN KEY FK_9AF63BBDDA8DD39B');
        $this->addSql('ALTER TABLE dab_bil DROP FOREIGN KEY FK_EAC9B27DF1610826');
        $this->addSql('ALTER TABLE retrait DROP FOREIGN KEY FK_D9846A51B11AE000');
        $this->addSql('ALTER TABLE ret_bil DROP FOREIGN KEY FK_9AF63BBD7972A7B1');
        $this->addSql('DROP TABLE billet');
        $this->addSql('DROP TABLE dab');
        $this->addSql('DROP TABLE dab_bil');
        $this->addSql('DROP TABLE retrait');
        $this->addSql('DROP TABLE ret_bil');
    }
}
