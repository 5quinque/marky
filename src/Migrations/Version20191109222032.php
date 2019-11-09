<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191109222032 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE markov_key (id INT AUTO_INCREMENT NOT NULL, pair VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE markov_key_value (markov_key_id INT NOT NULL, value_id INT NOT NULL, INDEX IDX_D8408FCCFABB73DC (markov_key_id), INDEX IDX_D8408FCCF920BBA2 (value_id), PRIMARY KEY(markov_key_id, value_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE value (id INT AUTO_INCREMENT NOT NULL, word VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE markov_key_value ADD CONSTRAINT FK_D8408FCCFABB73DC FOREIGN KEY (markov_key_id) REFERENCES markov_key (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE markov_key_value ADD CONSTRAINT FK_D8408FCCF920BBA2 FOREIGN KEY (value_id) REFERENCES value (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE markov_key_value DROP FOREIGN KEY FK_D8408FCCFABB73DC');
        $this->addSql('ALTER TABLE markov_key_value DROP FOREIGN KEY FK_D8408FCCF920BBA2');
        $this->addSql('DROP TABLE markov_key');
        $this->addSql('DROP TABLE markov_key_value');
        $this->addSql('DROP TABLE value');
    }
}
