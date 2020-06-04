<?php

namespace App\Controller;

use App\Entity\Organization;
use App\Repository\ChargePointRepository;
use App\Repository\OrganizationRepository;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Exception\MissingInputException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;

class OrganizationController extends AbstractController
{
    private $organizationRepository;
    private $chargePointRepository;

    public function __construct(
        OrganizationRepository $cocheRepository,
        ChargePointRepository $chargePointRepository)
    {
        $this->organizationRepository = $cocheRepository;
        $this->chargePointRepository = $chargePointRepository;
    }

    /**
     * @Route("api/organization", name="save_organization", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request): JsonResponse
    {
        $name = $request->get('name');
        $legalEntity = $request->get('legalEntity');

        if (empty($name) || empty($legalEntity)) {
            throw new MissingInputException('Parameters are mandatory');
        }

        $this->organizationRepository->saveOrganization($name, $legalEntity);

        return new JsonResponse(['status' => 'Organization saved'], Response::HTTP_CREATED);
    }

    /**
     * @Route("api/organization/{id}", name="get_organization", methods={"GET"})
     * @param $id
     * @return JsonResponse
     * @throws NoResultException
     */
    public function get($id): Object
    {
        if ($id === null) {
            throw new MissingMandatoryParametersException('El parametro id es obligatorio');
        }

        $organization = $this->organizationRepository->find($id);

        if ($organization === null) {
            throw new NoResultException;
        }

        $data = [
            'id' => $organization->getId(),
            'name' => $organization->getName(),
            'legalEntity' => $organization->getLegalEntity()
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("api/organizations", name="get_organizations", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $organizations = $this->getDoctrine()->getRepository(Organization::class)->findAll();

        $data = [];

        /** @var Organization $organization */
        foreach ($organizations as $organization) {
            $data[] = [
                'id' => $organization->getId(),
                'name' => $organization->getName(),
                'legalEntity' => $organization->getLegalEntity()
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("api/organization/{id}", name="update_organization", methods={"PUT"})
     * @param $id
     * @param Request $request
     * @return JsonResponse
     * @throws NoResultException
     */
    public function update($id, Request $request): JsonResponse
    {
        $organization = $this->organizationRepository->findOneBy(['id' => $id]);

        if ($organization === null) {
            throw new NoResultException();
        }

        $name = $request->get('name');
        $legalEntity = $request->get('legalEntity');

        $organization->setName($name);
        $organization->setLegalEntity($legalEntity);

        $this->organizationRepository->updateOrganization($organization);

        return new JsonResponse(['status' => 'Organization updated'], Response::HTTP_OK);
    }

    /**
     * @Route("api/organization/{id}", name="delete_organization", methods={"DELETE"})
     * @param $id
     * @return JsonResponse
     * @throws NoResultException
     */
    public function delete($id): JsonResponse
    {
        $organization = $this->organizationRepository->findOneBy(['id' => $id]);

        if ($organization === null) {
            throw new NoResultException();
        }
        $chargePoints = $organization->getChargePoints();

        foreach ($chargePoints as $chargePoint) {
            $chargePoint->setCpo(null);
            $this->chargePointRepository->updateChargePoint($chargePoint);
        }

        $this->organizationRepository->deleteOrganization($organization);

        return new JsonResponse(['status' => 'Organization deleted'], Response::HTTP_OK);
    }
}
