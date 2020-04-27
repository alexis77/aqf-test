<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200427101742 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('INSERT INTO `user` (`id`, `username`, `roles`, `password`) VALUES
	(\'438a98d4-d8b3-472f-9746-5dee053f57f3\', \'client2@aqf.com\', \'["ROLE_CLIENT"]\', \'$argon2id$v=19$m=65536,t=4,p=1$oweEaGfijl/S0YOi078PwQ$GyGk2fYAwUl5pvRn0SCFDb8Aml7UYyXK123QWH98XW4\'),
	(\'9e953436-d261-4b0d-9223-0654e951b9aa\', \'client1@aqf.com\', \'["ROLE_CLIENT"]\', \'$argon2id$v=19$m=65536,t=4,p=1$y1fDPNA9QPHIq2pt2mwPSA$6SaQUVFCX7DFr05uI5HfQZjV1C+4h5xRUzdJQeuZ6gc\'),
	(\'90ae9204-a8f0-402e-8aad-19381fc53b37\', \'admin@aqf.com\', \'["ROLE_ADMIN"]\', \'$argon2id$v=19$m=65536,t=4,p=1$ojFo1eCOMAEDl1Sev3QIAg$jTlnyVoXUL/SuZ+qsq4ZgHSUVTeFUjEJI9Si2WH7cfo\');
');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE user');
    }
}
