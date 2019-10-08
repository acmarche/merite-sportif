<?php
/**
 * This file is part of meritesportif application
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 3/10/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Service;


use App\Entity\Club;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(
        UserRepository $userRepository,
        UserPasswordEncoderInterface $userPasswordEncoder,
        EntityManagerInterface $entityManager
    ) {
        $this->userRepository = $userRepository;
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->entityManager = $entityManager;
    }

    public function createUser(Club $club) : User {

        $password = rand(9999,999999);
        $email = $club->getEmail();
        $user = new User();
        $user->setUsername($email);
        $user->setNom($club->getNom());
        $user->setPassword($this->userPasswordEncoder->encodePassword($user, $password));
        $user->addRole('ROLE_MERITE');
        $user->addRole('ROLE_MERITE_CLUB');

        $this->entityManager->persist($user);

        $club->setUser($user);

        return $user;
    }
}