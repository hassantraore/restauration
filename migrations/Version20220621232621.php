<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220621232621 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE plat_drink');
        $this->addSql('DROP TABLE plat_extras');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE whishlist');
        $this->addSql('DROP TABLE `year_month`');
        $this->addSql('ALTER TABLE reservation ADD date_debut DATETIME DEFAULT NULL, DROP date, DROP heure_debut');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE plat_drink (plat_id INT NOT NULL, drink_id INT NOT NULL, INDEX IDX_9DB6C6AD36AA4BB4 (drink_id), INDEX IDX_9DB6C6ADD73DB560 (plat_id), PRIMARY KEY(plat_id, drink_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE plat_extras (plat_id INT NOT NULL, extras_id INT NOT NULL, INDEX IDX_7FB85040955D4F3F (extras_id), INDEX IDX_7FB85040D73DB560 (plat_id), PRIMARY KEY(plat_id, extras_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, size LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\', price DOUBLE PRECISION NOT NULL, promotion TINYINT(1) DEFAULT NULL, image_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, extras TINYINT(1) DEFAULT NULL, boissons TINYINT(1) DEFAULT NULL, sauce TINYINT(1) DEFAULT NULL, INDEX IDX_D34A04AD12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE whishlist (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, item LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\', UNIQUE INDEX UNIQ_2E936C6DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE `year_month` (year_id INT NOT NULL, month_id INT NOT NULL, INDEX IDX_2DD83ED140C1FEA7 (year_id), INDEX IDX_2DD83ED1A0CBDE4 (month_id), PRIMARY KEY(year_id, month_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE plat_drink ADD CONSTRAINT FK_9DB6C6AD36AA4BB4 FOREIGN KEY (drink_id) REFERENCES drink (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE plat_drink ADD CONSTRAINT FK_9DB6C6ADD73DB560 FOREIGN KEY (plat_id) REFERENCES plat (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE plat_extras ADD CONSTRAINT FK_7FB85040955D4F3F FOREIGN KEY (extras_id) REFERENCES extras (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE plat_extras ADD CONSTRAINT FK_7FB85040D73DB560 FOREIGN KEY (plat_id) REFERENCES plat (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE whishlist ADD CONSTRAINT FK_2E936C6DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE `year_month` ADD CONSTRAINT FK_2DD83ED140C1FEA7 FOREIGN KEY (year_id) REFERENCES year (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `year_month` ADD CONSTRAINT FK_2DD83ED1A0CBDE4 FOREIGN KEY (month_id) REFERENCES month (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation ADD date DATE NOT NULL, ADD heure_debut TIME NOT NULL, DROP date_debut');
    }
}
