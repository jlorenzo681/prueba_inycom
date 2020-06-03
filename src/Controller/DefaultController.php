<?php

namespace App\Controller;

use App\Service\ChargePointService;
use App\Service\OrganizationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

    private $chargePointService;
    private $organizationService;

    /**
     * DefaultController constructor.
     * @param ChargePointService $chargePointService
     * @param OrganizationService $organizationService
     */
    public function __construct(ChargePointService $chargePointService, OrganizationService $organizationService)
    {
        $this->chargePointService = $chargePointService;
        $this->organizationService = $organizationService;
    }

    /**
     * @Route("/", name="index")
     * @return Response
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function index(): Response
    {
        $chargePoints = $this->chargePointService->getChargePoints();
        $organizatons = $this->chargePointService->getChargePoints();

        return $this->render('inicio.html.twig', [
            "chargePoints" => $chargePoints,
            "organizations" => $organizatons
        ]);
    }

}
