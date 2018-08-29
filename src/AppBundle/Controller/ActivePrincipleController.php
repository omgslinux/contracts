<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ActivePrinciple;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * ActivePrinciple controller.
 *
 * @Route("activePrinciple")
 */
class ActivePrincipleController extends Controller
{
    /**
     * Lists all activePrinciple entities.
     *
     * @Route("/", name="activePrinciple_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $activePrinciples = $em->getRepository(ActivePrinciple::class)->findAll();

        return $this->render('activePrinciple/index.html.twig', array(
            'activePrinciples' => $activePrinciples,
        ));
    }

    /**
     * Creates a new activePrinciple entity.
     *
     * @Route("/new", name="activePrinciple_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $activePrinciple = new ActivePrinciple();
        $form = $this->createForm('AppBundle\Form\ActivePrincipleType', $activePrinciple);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($activePrinciple);
            $em->flush();

            return $this->redirectToRoute('activePrinciple_show', array('id' => $activePrinciple->getId()));
        }

        return $this->render('activePrinciple/new.html.twig', array(
            'activePrinciple' => $activePrinciple,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a activePrinciple entity.
     *
     * @Route("/{id}", name="activePrinciple_show")
     * @Method("GET")
     */
    public function showAction(ActivePrinciple $activePrinciple)
    {
        $deleteForm = $this->createDeleteForm($activePrinciple);

        return $this->render('activePrinciple/show.html.twig', array(
            'activePrinciple' => $activePrinciple,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing activePrinciple entity.
     *
     * @Route("/{id}/edit", name="activePrinciple_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ActivePrinciple $activePrinciple)
    {
        $deleteForm = $this->createDeleteForm($activePrinciple);
        $editForm = $this->createForm('AppBundle\Form\ActivePrincipleType', $activePrinciple);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('activePrinciple_edit', array('id' => $activePrinciple->getId()));
        }

        return $this->render('activePrinciple/edit.html.twig', array(
            'activePrinciple' => $activePrinciple,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a activePrinciple entity.
     *
     * @Route("/{id}", name="activePrinciple_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ActivePrinciple $activePrinciple)
    {
        $form = $this->createDeleteForm($activePrinciple);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($activePrinciple);
            $em->flush();
        }

        return $this->redirectToRoute('activePrinciple_index');
    }

    /**
     * Creates a form to delete a activePrinciple entity.
     *
     * @param ActivePrinciple $activePrinciple The activePrinciple entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ActivePrinciple $activePrinciple)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('activePrinciple_delete', array('id' => $activePrinciple->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
