<?php


namespace App\Service;

use App\Entity\Club;
use App\Repository\CandidatRepository;
use Symfony\Component\String\Slugger\SluggerInterface;
use Twig\Environment;
use Knp\Snappy\Pdf;

class PdfFactory
{
    /**
     * @var Pdf
     */
    private $pdf;
    /**
     * @var SluggerInterface
     */
    private $slugger;
    /**
     * @var Environment
     */
    private $environment;
    /**
     * @var CandidatRepository
     */
    private $candidatRepository;

    public function __construct(
        CandidatRepository $candidatRepository,
        Pdf $pdf,
        SluggerInterface $slugger,
        Environment $environment
    ) {
        $this->pdf = $pdf;
        $this->slugger = $slugger;
        $this->environment = $environment;
        $this->candidatRepository = $candidatRepository;
    }

    public function create(Club $club)
    {
        $html = $this->environment->render(
            'pdf/proposition_finish.html.twig',
            [
                'club' => $club,
                'candidats' => $this->candidatRepository->getByClub($club)
            ]
        );

        return $this->pdf->getOutputFromHtml($html);
    }
}