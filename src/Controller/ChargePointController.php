<?php

namespace App\Controller;

use App\Entity\ChargePoint;
use App\Entity\Organization;
use App\Repository\ChargePointRepository;
use App\Repository\OrganizationRepository;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;

class ChargePointController extends AbstractController
{
    private $chargePointRepository;
    private $organizationRepository;

    public function __construct(ChargePointRepository $chargePointRepository, OrganizationRepository $organizationRepository)
    {
        $this->chargePointRepository = $chargePointRepository;
        $this->organizationRepository = $organizationRepository;
    }

    /**
     * @Route("api/chargepoint/add", name="save_chargepoint", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request): JsonResponse
    {
        $identity = $request->get('identity');
        $cpo = $request->get('cpo');

        if (empty($identity) || empty($cpo)) {
            throw new NotFoundHttpException('Parameters are mandatory');
        }

        /** @var Organization $cpoObject */
        $cpoObject = $this->organizationRepository->find($cpo);

        $newChargePoint = $this->chargePointRepository->saveChargePoint($identity, $cpoObject);
        $cpoObject->addChargePoint($newChargePoint);

        return new JsonResponse(['status' => 'Chargepoint saved'], Response::HTTP_CREATED);
    }

    /**
     * @Route("api/chargepoint/{id}", name="get_chargepoint", methods={"GET"})
     * @param $id
     * @return JsonResponse
     * @throws NoResultException
     */
    public function get($id): Object
    {
        if ($id === null) {
            throw new MissingMandatoryParametersException('Parameter id is mandatory');
        }

        $chargePoint = $this->getDoctrine()->getRepository(ChargePoint::class)->find($id);

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
     * @Route("api/chargepoints/all", name="get_chargepoints", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $chargePoints = $this->getDoctrine()->getRepository(ChargePoint::class)->findAll();
        $result = array();

        /** @var ChargePoint $chargePoint */
        foreach ($chargePoints as $key => $chargePoint) {

            $data[$key] = array(
                'id' => $chargePoint->getId(),
                'identity' => $chargePoint->getIdentity(),
                'cpo' => ''
            );

            if ($chargePoint->getCpo() !== null) {
                $data[$key]['cpo'] = $chargePoint->getCpo()->getName();
            }

            $result[] = $data;
        }

        return new JsonResponse($result, Response::HTTP_OK);
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

        $identity = $request->get('identity');
        $cpo = $request->get('cpo');

        /** @var Organization $cpoObject */
        $cpoObject = $this->organizationRepository->find($cpo);

        $chargePoint->setIdentity($identity);
        $chargePoint->setCpo($cpoObject);

        $newChargePoint = $this->chargePointRepository->saveChargePoint($identity, $cpoObject);
        $cpoObject->addChargePoint($newChargePoint);

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
