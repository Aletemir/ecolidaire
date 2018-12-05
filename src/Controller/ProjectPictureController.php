<?php

namespace App\Controller;

use App\Entity\ProjectPicture;
use App\Form\ProjectPictureType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/project/picture")
 */
class ProjectPictureController extends AbstractController
{
    /**
     * @Route("/", name="project_picture_index", methods="GET")
     */
    public function index(): Response
    {
        $projectPictures = $this->getDoctrine()
            ->getRepository(ProjectPicture::class)
            ->findAll();

        return $this->render('project_picture/index.html.twig', ['project_pictures' => $projectPictures]);
    }

    /**
     * @Route("/new", name="project_picture_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $projectPicture = new ProjectPicture();
        $form = $this->createForm(ProjectPictureType::class, $projectPicture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($projectPicture);
            $em->flush();

            return $this->redirectToRoute('project_picture_index');
        }

        return $this->render('project_picture/new.html.twig', [
            'project_picture' => $projectPicture,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="project_picture_show", methods="GET")
     */
    public function show(ProjectPicture $projectPicture): Response
    {
        return $this->render('project_picture/show.html.twig', ['project_picture' => $projectPicture]);
    }

    /**
     * @Route("/{id}/edit", name="project_picture_edit", methods="GET|POST")
     */
    public function edit(Request $request, ProjectPicture $projectPicture): Response
    {
        $form = $this->createForm(ProjectPictureType::class, $projectPicture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('project_picture_index', ['id' => $projectPicture->getId()]);
        }

        return $this->render('project_picture/edit.html.twig', [
            'project_picture' => $projectPicture,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="project_picture_delete", methods="DELETE")
     */
    public function delete(Request $request, ProjectPicture $projectPicture): Response
    {
        if ($this->isCsrfTokenValid('delete'.$projectPicture->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($projectPicture);
            $em->flush();
        }

        return $this->redirectToRoute('project_picture_index');
    }
}
