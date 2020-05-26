<?php

namespace App\Controller;

use App\Repository\ChargePointRepository;
use Doctrine\ORM\NoResultException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;

class ChargePointController
{
    private $chargePointRepository;

    public function __construct(ChargePointRepository $chargePointRepository)
    {
        $this->chargePointRepository = $chargePointRepository;
    }

    /**
     * @Route("api/chargepoint/add", name="save_chargepoint", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $identity = $data['identity'];
        $cpo = $data['cpo'];

        if (empty($identity) || empty($cpo)) {
            throw new NotFoundHttpException('Parameters are mandatory');
        }

        $this->chargePointRepository->saveChargePoint($identity, $cpo);

        return new JsonResponse(['status' => 'Chargepoint saved'], Response::HTTP_CREATED);
    }

    /**
     * @Route("api/chargepoint/{id}", name="get_chargepoint", methods={"GET"})
     * @param $id
     * @return JsonResponse
     * @throws NoResultException
     */
    public function get($id): JsonResponse
    {
        if ($id === null) {
            throw new MissingMandatoryParametersException('Parameter id is mandatory');
        }

        $chargePoint = $this->chargePointRepository->findOneBy(['id' => $id]);

        if ($chargePoint === null) {
            throw new NoResultException;
        }

        $data = [
            'id' => $chargePoint->getId(),
            'identity' => $chargePoint->getIdentity(),
            'cpo' => $chargePoint->getCpo()
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("api/chargepoint/all", name="get_chargepoints", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $coches = $this->chargePointRepository->findAll();
        $data = [];

        foreach ($coches as $coche) {
            $data[] = [
                'id' => $coche->getId(),
                'identity' => $coche->getIdentity(),
                'cpo' => $coche->getCpo()
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("api/chargepoint/update/{id}", name="update_chargepoint", methods={"PUT"})
     * @param $id
     * @param Request $request
     * @return JsonResponse
     * @throws NoResultException
     */
    public function update($id, Request $request): JsonResponse
    {
        $chargePoint = $this->chargePointRepository->findOneBy(['id' => $id]);

        if ($chargePoint === null) {
            throw new NoResultException();
        }

        $data = json_decode($request->getContent(), true);

        empty($data['identity']) ?: $chargePoint->setIdentity($data['identity']);
        empty($data['cpo']) ?: $chargePoint->setCpo($data['cpo']);

        $this->chargePointRepository->updateChargePoint($chargePoint);

        return new JsonResponse(['status' => 'Chargepoint updated'], Response::HTTP_OK);
    }

    /**
     * @Route("api/chargepoint/delete/{id}", name="delete_chargepoint", methods={"DELETE"})
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
