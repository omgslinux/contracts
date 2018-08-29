<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ContractDescription;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Contractdescription controller.
 *
 * @Route("contractdescription")
 */
class ContractDescriptionController extends Controller
{
    /**
     * Lists all contractDescription entities.
     *
     * @Route("/", name="contractdescription_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $contractDescriptions = $em->getRepository('AppBundle:ContractDescription')->findAll();

        return $this->render('contractdescription/index.html.twig', array(
            'contractDescriptions' => $contractDescriptions,
        ));
    }

    /**
     * Creates a new contractDescription entity.
     *
     * @Route("/new", name="contractdescription_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $contractDescription = new Contractdescription();
        $form = $this->createForm('AppBundle\Form\ContractDescriptionType', $contractDescription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contractDescription);
            $em->flush();

            return $this->redirectToRoute('contractdescription_show', array('id' => $contractDescription->getId()));
        }

        return $this->render('contractdescription/new.html.twig', array(
            'contractDescription' => $contractDescription,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a contractDescription entity.
     *
     * @Route("/{id}", name="contractdescription_show")
     * @Method("GET")
     */
    public function showAction(ContractDescription $contractDescription)
    {
        $deleteForm = $this->createDeleteForm($contractDescription);

        return $this->render('contractdescription/show.html.twig', array(
            'contractDescription' => $contractDescription,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing contractDescription entity.
     *
     * @Route("/{id}/edit", name="contractdescription_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ContractDescription $contractDescription)
    {
        $deleteForm = $this->createDeleteForm($contractDescription);
        $editForm = $this->createForm('AppBundle\Form\ContractDescriptionType', $contractDescription);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('contractdescription_edit', array('id' => $contractDescription->getId()));
        }

        return $this->render('contractdescription/edit.html.twig', array(
            'contractDescription' => $contractDescription,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a contractDescription entity.
     *
     * @Route("/{id}", name="contractdescription_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ContractDescription $contractDescription)
    {
        $form = $this->createDeleteForm($contractDescription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($contractDescription);
            $em->flush();
        }

        return $this->redirectToRoute('contractdescription_index');
    }

    /**
     * Creates a form to delete a contractDescription entity.
     *
     * @param ContractDescription $contractDescription The contractDescription entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ContractDescription $contractDescription)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('contractdescription_delete', array('id' => $contractDescription->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
