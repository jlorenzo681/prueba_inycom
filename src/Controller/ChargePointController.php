<?php

namespace App\Controller;

use App\Entity\ChargePoint;
use App\Entity\Organization;
use App\Repository\ChargePointRepository;
use App\Repository\OrganizationRepository;
use Doctrine\ORM\NoResultException;
use Symfony\Component\Console\Exception\MissingInputException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;

class ChargePointController
{

    private $chargePointRepository;
    private $organizationRepository;

    public function __construct(ChargePointRepository $chargePointRepository, OrganizationRepository $organizationRepository)
    {
        $this->chargePointRepository = $chargePointRepository;
        $this->organizationRepository = $organizationRepository;
    }

    /**
     * @Route("api/chargepoint", name="save_chargepoint", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $identity = $data['identity'];
        $cpo = $data['cpo'];

        if (empty($identity) || empty($cpo)) {
            throw new MissingInputException('Parameters are mandatory');
        }

        /** @var Organization $cpoObject */
        $cpoObject = $this->organizationRepository->find($cpo);

        $newChargePoint = $this->chargePointRepository->saveChargePoint($identity, $cpoObject);
        $cpoObject->addChargePoint($newChargePoint);

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("api/chargepoint/{id}", name="get_chargepoint", methods={"GET"})
     * @param $id
     * @return JsonResponse
     * @throws NoResultException
     */
    public function get($id): Object
    {
        $data = null;

        if ($id === null) {
            throw new MissingMandatoryParametersException('Parameter id is mandatory');
        }

        /** @var ChargePoint $chargePoint */
        $chargePoint = $this->chargePointRepository->findAll();

        if ($chargePoint === null) {
            throw new NoResultException;
        }

        $organization = $chargePoint->getCpo();
        if ($organization !== null) {
            $data = [
                'id' => $chargePoint->getId(),
                'identity' => $chargePoint->getIdentity(),
                'cpo' => $organization->getName()
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("api/chargepoints", name="get_chargepoints", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $chargePoints = $this->chargePointRepository->findAll();
        $result = array();

        /** @var ChargePoint $chargePoint */
        foreach ($chargePoints as $key => $chargePoint) {
            $data[$key] = [
                'id' => $chargePoint->getId(),
                'identity' => $chargePoint->getIdentity(),
                'cpo' => ''
            ];

            if ($chargePoint->getCpo() !== null) {
                $data[$key]['cpo'] = $chargePoint->getCpo()->getName();
            }

            $result[] = $data[$key];
            $data = array();
        }

        return new JsonResponse($result, Response::HTTP_OK);
    }

    /**
     * @Route("api/chargepoint/{id}", name="update_chargepoint", methods={"PUT"})
     * @param $id
     * @param Request $request
     * @return JsonResponse
     * @throws NoResultException
     */
    public function update($id, Request $request): JsonResponse
    {
        $chargePoint = $this->chargePointRepository->find($id);

        if ($chargePoint === null) {
            throw new NoResultException();
        }

        $data = json_decode($request->getContent(), true);

        $identity = $data['identity'];
        $cpo = $data['cpo'];

        if (empty($identity) || empty($cpo)) {
            throw new MissingInputException('Parameters are mandatory');
        }

        $cpoObject = $this->organizationRepository->find($cpo);

        $chargePoint->setIdentity($identity);
        $chargePoint->setCpo($cpoObject);

        $this->chargePointRepository->updateChargePoint($chargePoint);

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("api/chargepoint/{id}", name="delete_chargepoint", methods={"DELETE"})
     * @param $id
     * @return JsonResponse
     * @throws NoResultException
     */
    public function delete($id): JsonResponse
    {
        $chargePoint = $this->chargePointRepository->findOneBy(['id' => $id]);

        if ($chargePoint === null) {
            throw new NoResultException();
        }

        $this->chargePointRepository->deleteChargePoint($chargePoint);

        return new JsonResponse(['status' => 'Deleted chargepoint'], Response::HTTP_OK);
    }
}
