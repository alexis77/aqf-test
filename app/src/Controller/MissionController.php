<?php

namespace App\Controller;

use App\Entity\Mission;
use App\Form\FilterType;
use App\Form\MissionType;
use App\Repository\MissionRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mission")
 * @IsGranted({"ROLE_USER"})
 */
class MissionController extends AbstractController
{
    /**
     * @Route("/", name="mission_list", methods={"GET"})
     * @IsGranted({"ROLE_CLIENT", "ROLE_ADMIN"})
     */
    public function index(MissionRepository $missionRepository, Request $request, PaginatorInterface $pagination): Response
    {
        $criteria = $this->getFilterParameters($request->query->get('filter'));
        $form = $this->createForm(FilterType::class, $criteria, ['method' => 'GET']);

        $result = $pagination->paginate(
            $missionRepository->findAllByCriteria($criteria),
            $request->query->getInt('page', 1),
            $this->getParameter('search.limit')
        );

        return $this->render('mission/index.html.twig', [
            'missions' => $result,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/create", name="mission_create", methods={"GET","POST"})
     * @IsGranted({"ROLE_CLIENT"})
     */
    public function create(Request $request): Response
    {
        $mission = new Mission();
        $form = $this->createForm(MissionType::class, $mission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Easy way to do without using HiddenFormType
            $mission->setClient($this->getUser());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mission);
            $entityManager->flush();

            return $this->redirectToRoute('mission_show', ['id' => $mission->getId()]);
        }

        return $this->render('mission/create.html.twig', [
            'mission' => $mission,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="mission_show", methods={"GET"})
     * @IsGranted({"ROLE_CLIENT", "ROLE_ADMIN"})
     * @IsGranted("MISSION_VIEW", subject="mission")
     */
    public function show(Mission $mission): Response
    {
        return $this->render('mission/show.html.twig', [
            'mission' => $mission,
        ]);
    }

    /**
     * @Route("/{id}/update", name="mission_update", methods={"GET","POST"})
     * @IsGranted("MISSION_UPDATE", subject="mission")
     */
    public function update(Request $request, Mission $mission): Response
    {
        $form = $this->createForm(MissionType::class, $mission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('mission_list');
        }

        return $this->render('mission/edit.html.twig', [
            'mission' => $mission,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="mission_delete", methods={"DELETE", "GET"})
     * @IsGranted({"ROLE_CLIENT", "ROLE_ADMIN"})
     * @IsGranted("MISSION_DELETE", subject="mission")
     */
    public function delete(Request $request, Mission $mission): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mission->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mission);
            $entityManager->flush();
            return $this->redirectToRoute('mission_list');
        }

        return $this->render('mission/delete.html.twig', [
            'mission' => $mission
        ]);
    }

    private function getFilterParameters($parameters)
    {
        $criteria = $parameters ?: [];

        if ($this->isGranted(['ROLE_CLIENT'])) {
            $criteria['client'] = true;
        }
        if (!empty($criteria['startDate'])) {
            $criteria['startDate'] = \DateTime::createFromFormat('Y-m-d', $criteria['startDate']);
        }

        if (!empty($criteria['endDate'])) {
            $criteria['endDate'] = \DateTime::createFromFormat('Y-m-d', $criteria['endDate']);
        }

        return array_filter($criteria);
    }
}
