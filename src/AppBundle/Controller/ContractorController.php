<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Contractor;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Contractor controller.
 *
 * @Route("contractor")
 */
class ContractorController extends Controller
{
    /**
     * Lists all contractor entities.
     *
     * @Route("/", name="contractor_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $contractors = $em->getRepository('AppBundle:Contractor')->findAll();

        return $this->render('contractor/index.html.twig', array(
            'contractors' => $contractors,
        ));
    }

    /**
     * Creates a new contractor entity.
     *
     * @Route("/new", name="contractor_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $contractor = new Contractor();
        $form = $this->createForm('AppBundle\Form\ContractorType', $contractor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contractor);
            $em->flush();

            return $this->redirectToRoute('contractor_show', array('id' => $contractor->getId()));
        }

        return $this->render('contractor/new.html.twig', array(
            'contractor' => $contractor,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a contractor entity.
     *
     * @Route("/{id}", name="contractor_show")
     * @Method("GET")
     */
    public function showAction(Contractor $contractor)
    {
        $deleteForm = $this->createDeleteForm($contractor);

        return $this->render('contractor/show.html.twig', array(
            'contractor' => $contractor,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing contractor entity.
     *
     * @Route("/{id}/edit", name="contractor_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Contractor $contractor)
    {
        $deleteForm = $this->createDeleteForm($contractor);
        $editForm = $this->createForm('AppBundle\Form\ContractorType', $contractor);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('contractor_edit', array('id' => $contractor->getId()));
        }

        return $this->render('contractor/edit.html.twig', array(
            'contractor' => $contractor,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a contractor entity.
     *
     * @Route("/{id}", name="contractor_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Contractor $contractor)
    {
        $form = $this->createDeleteForm($contractor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($contractor);
            $em->flush();
        }

        return $this->redirectToRoute('contractor_index');
    }

    /**
     * Creates a form to delete a contractor entity.
     *
     * @param Contractor $contractor The contractor entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Contractor $contractor)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('contractor_delete', array('id' => $contractor->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
