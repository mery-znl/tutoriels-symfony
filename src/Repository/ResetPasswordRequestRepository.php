<?php

namespace App\Repository;

use App\Entity\ResetPasswordRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestInterface;
use SymfonyCasts\Bundle\ResetPassword\Persistence\ResetPasswordRequestRepositoryInterface;

class ResetPasswordRequestRepository extends ServiceEntityRepository implements ResetPasswordRequestRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResetPasswordRequest::class);
    }

    public function createResetPasswordRequest(object $user, \DateTimeInterface $expiresAt, string $selector, string $hashedToken): ResetPasswordRequestInterface
    {
        $resetPasswordRequest = new ResetPasswordRequest($user, $expiresAt, $selector, $hashedToken);
        $this->_em->persist($resetPasswordRequest);
        $this->_em->flush();

        return $resetPasswordRequest;
    }

    public function persistResetPasswordRequest(ResetPasswordRequestInterface $resetPasswordRequest): void
    {
        $this->_em->persist($resetPasswordRequest);
        $this->_em->flush();
    }

    public function findResetPasswordRequest(string $selector): ?ResetPasswordRequestInterface
    {
        return $this->findOneBy(['selector' => $selector]);
    }

    public function removeResetPasswordRequest(object $resetPasswordRequest): void
    {
        $this->_em->remove($resetPasswordRequest);
        $this->_em->flush();
    }

    public function getUserIdentifier(object $user): string
    {
        if (!method_exists($user, 'getUserIdentifier')) {
            throw new \InvalidArgumentException('Expected a UserInterface object.');
        }

        return $user->getUserIdentifier();
    }

    public function getMostRecentNonExpiredRequestDate(object $user): ?\DateTimeInterface
    {
        return $this->createQueryBuilder('r')
            ->select('r.expiresAt')
            ->where('r.user = :user')
            ->andWhere('r.expiresAt > :now')
            ->setParameter('user', $user)
            ->setParameter('now', new \DateTime())
            ->orderBy('r.expiresAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()['expiresAt'] ?? null;
    }

    public function removeExpiredResetPasswordRequests(): int
    {
        return $this->createQueryBuilder('r')
            ->delete()
            ->where('r.expiresAt <= :now')
            ->setParameter('now', new \DateTime())
            ->getQuery()
            ->execute();
    }
}
