<?php

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{

    public function __construct(
        ManagerRegistry $registry,
        private EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Message::class);

        $this->entityManager = $this->getEntityManager();
    }

    public function save(Message $message): void
    {
        $this->entityManager->persist($message);
        $this->entityManager->flush();
    }

    /**
     * @return array<Message>
     */
    public function findAllSorted(string $sortBy, string $sortOrder): array {
        return $this->findBy([], [$sortBy => $sortOrder]);
    }
}