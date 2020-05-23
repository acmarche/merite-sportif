<?php
/**
 * This file is part of meritesportif application
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 2/10/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Controller;

use App\Repository\VoteRepository;
use App\Service\SpreadsheetFactory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController
 * @package App\Controller
 */
class DefaultController extends AbstractController
{
    /**
     * @var VoteRepository
     */
    private $voteRepository;
    /**
     * @var SpreadsheetFactory
     */
    private $spreadsheetFactory;

    public function __construct(VoteRepository $voteRepository, SpreadsheetFactory $spreadsheetFactory)
    {
        $this->voteRepository = $voteRepository;
        $this->spreadsheetFactory = $spreadsheetFactory;
    }

    /**
     * @Route("/", name="merite_home", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render(
            'default/index.html.twig',
            [

            ]
        );
    }

    /**
     * @Route("/contact", name="merite_contact", methods={"GET","POST"})
     */
    public function contact(): Response
    {
        return $this->render(
            'default/contact.html.twig',
            [

            ]
        );
    }

    /**
     * @Route("/resultat", name="merite_resultat", methods={"GET","POST"})
     * @IsGranted("ROLE_MERITE_ADMIN")
     */
    public function resultat(): Response
    {
        $votes = $this->voteRepository->getAll();

        return $this->render(
            'default/resultat.html.twig',
            [
                'votes' => $votes,
            ]
        );
    }

    /**
     * @Route("/export", name="merite_vote_export", methods={"GET","POST"})
     * @IsGranted("ROLE_MERITE_ADMIN")
     */
    public function export(): Response
    {
        $votes = $this->voteRepository->getAll();
        $xls = $this->spreadsheetFactory->createXSL($votes);

        return $this->spreadsheetFactory->downloadXls($xls,'votes.xlsx');
    }

}
