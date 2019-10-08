<?php
/**
 * This file is part of meritesportif application
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 8/10/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Controller;

use App\Form\MessageType;
use App\Service\Mailer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MessageController
 * @package App\Controller
 * @Route("/message")
 * @IsGranted("ROLE_MERITE_ADMIN")
 */
class MessageController extends AbstractController
{
    /**
     * @var Mailer
     */
    private $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @Route("/", name="merite_message_index", methods={"GET","POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        $form = $this->createForm(MessageType::class, ['from' => 'csl@marche.be']);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->mailer->handle($form->getData());

            $this->addFlash('success', 'Message envoyé');

            return $this->redirectToRoute('merite_message_index');
        }

       return $this->render(
            'message/index.html.twig',
            [
                'form' => $form->createView(),
            ]
        );

    }

}