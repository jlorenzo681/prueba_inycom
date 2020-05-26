<?php

namespace App\Controller;

use App\Repository\OrganizationRepository;
use Doctrine\ORM\NoResultException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;

class OrganizationController
{
    private $organizationRepository;

    public function __construct(OrganizationRepository $cocheRepository)
    {
        $this->organizationRepository = $cocheRepository;
    }

    /**
     * @Route("api/organization/add", name="save_organization", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $name = $data['name'];
        $legalEntity = $data['legalEntity'];

        if (empty($name) || empty($legalEntity)) {
            throw new NotFoundHttpException('Parameters are mandatory');
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
    public function get($id): JsonResponse
    {
        if ($id === null) {
            throw new MissingMandatoryParametersException('El parametro id es obligatorio');
        }

        $organization = $this->organizationRepository->findOneBy(['id' => $id]);

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
     * @Route("api/organization/all", name="get_organizations", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $organizations = $this->organizationRepository->findAll();
        $data = [];

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
     * @Route("api/organization/update/{id}", name="update_organization", methods={"PUT"})
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

        $data = json_decode($request->getContent(), true);

        empty($data['name']) ?: $organization->setName($data['name']);
        empty($data['legalEntity']) ?: $organization->setLegalEntity($data['name']);

        $this->organizationRepository->updateOrganization($organization);

        return new JsonResponse(['status' => 'Organization updated'], Response::HTTP_OK);
    }

    /**
     * @Route("api/organization/delete/{id}", name="delete_organization", methods={"DELETE"})
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

        $this->organizationRepository->deleteOrganization($organization);

        return new JsonResponse(['status' => 'Organization deleted'], Response::HTTP_OK);
    }
}
