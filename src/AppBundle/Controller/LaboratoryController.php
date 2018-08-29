<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Laboratory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Laboratory controller.
 *
 * @Route("laboratory")
 */
class LaboratoryController extends Controller
{
    /**
     * Lists all laboratory entities.
     *
     * @Route("/", name="laboratory_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $laboratories = $em->getRepository('AppBundle:Laboratory')->findAll();

        return $this->render('laboratory/index.html.twig', array(
            'laboratories' => $laboratories,
        ));
    }

    /**
     * Creates a new laboratory entity.
     *
     * @Route("/new", name="laboratory_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $laboratory = new Laboratory();
        $form = $this->createForm('AppBundle\Form\LaboratoryType', $laboratory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($laboratory);
            $em->flush();

            return $this->redirectToRoute('laboratory_show', array('id' => $laboratory->getId()));
        }

        return $this->render('laboratory/new.html.twig', array(
            'laboratory' => $laboratory,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a laboratory entity.
     *
     * @Route("/{id}", name="laboratory_show")
     * @Method("GET")
     */
    public function showAction(Laboratory $laboratory)
    {
        $deleteForm = $this->createDeleteForm($laboratory);

        return $this->render('laboratory/show.html.twig', array(
            'laboratory' => $laboratory,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing laboratory entity.
     *
     * @Route("/{id}/edit", name="laboratory_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Laboratory $laboratory)
    {
        $deleteForm = $this->createDeleteForm($laboratory);
        $editForm = $this->createForm('AppBundle\Form\LaboratoryType', $laboratory);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('laboratory_edit', array('id' => $laboratory->getId()));
        }

        return $this->render('laboratory/edit.html.twig', array(
            'laboratory' => $laboratory,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a laboratory entity.
     *
     * @Route("/{id}", name="laboratory_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Laboratory $laboratory)
    {
        $form = $this->createDeleteForm($laboratory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($laboratory);
            $em->flush();
        }

        return $this->redirectToRoute('laboratory_index');
    }

    /**
     * Creates a form to delete a laboratory entity.
     *
     * @param Laboratory $laboratory The laboratory entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Laboratory $laboratory)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('laboratory_delete', array('id' => $laboratory->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
