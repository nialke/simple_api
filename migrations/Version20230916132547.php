<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230916132547 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add schema public';
    }

    public function up(Schema $schema): void
    {
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
    }
}
