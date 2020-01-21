<?php
/**
 * This file is part of meritesportif application
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 8/10/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Service;


use App\Entity\Candidat;
use App\Entity\Club;
use App\Repository\ClubRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\RouterInterface;

class Mailer
{
    /**
     * @var ClubRepository
     */
    private $clubRepository;
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var \Symfony\Component\Mailer\Mailer
     */
    private $mailer;
    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    public function __construct(
        MailerInterface $mailer,
        ClubRepository $clubRepository,
        RouterInterface $router,
        FlashBagInterface $flashBag
    ) {
        $this->clubRepository = $clubRepository;
        $this->router = $router;
        $this->mailer = $mailer;
        $this->flashBag = $flashBag;
    }

    public function handle(array $data)
    {
        foreach ($this->clubRepository->findAll() as $club) {
            $user = $club->getUser();
            if (!$user) {
                $this->flashBag->add('error', $club->getNom() . ' a pas de compte user');
                continue;
            }

            $token = $user->getToken();
            if (!$token) {
                $this->flashBag->add('error', $club->getNom() . ' a pas de token');
                continue;
            }

            $value = $token->getValue();

            $message = $this->createMessage($data, $club, $value);
            $this->send($message);
        }
    }

    protected function send(Email $email)
    {
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            $this->flashBag->add('danger', $e->getMessage());
        }
    }

    protected function createMessage(array $data, Club $club, string $value): TemplatedEmail
    {
        $email = (new TemplatedEmail())
            ->from($data['from'])
            //->to($club->getEmail())
            ->to('johnny.kets@ac.marche.be')
            //->bcc('csl@marche.be')
            ->subject($data['sujet'])
            ->text($data['texte'])
            ->htmlTemplate('message/_content.html.twig')
            ->context(
                [
                    'club' => $club,
                    'texte' => $data['texte'],
                    'value' => $value,
                ]
            );

        return $email;
    }

    public function newPropositionMessage(Candidat $candidat, Club $club): TemplatedEmail
    {
        $email = (new TemplatedEmail())
            ->from($club->getEmail())
            //->to($club->getEmail())
            ->to('johnny.kets@ac.marche.be')
            //->bcc('csl@marche.be')
            ->subject('Une nouvelle proposition pour le mérite')
            ->htmlTemplate('message/_proposition.html.twig')
            ->context(
                [
                    'club' => $club,
                    'candidat' => $candidat
                ]
            );

        return $email;
    }
}