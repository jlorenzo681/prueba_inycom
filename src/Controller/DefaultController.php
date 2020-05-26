<?php

namespace App\Controller;

use App\Service\ChargePointService;
use App\Service\OrganizationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @param OrganizationService $organizationService
     * @param ChargePointService $chargePointService
     * @return Response
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function index(OrganizationService $organizationService, ChargePointService $chargePointService): Response
    {

        $organizations = $organizationService->getOrganizations();
        $chargePoints = $chargePointService->getChargePoints();

        return $this->render('inicio.html.twig', [
            'ciudades' => $organizations,
            'chargePoints' => $chargePoints
        ]);
    }

}
