<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ContractType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Contracttype controller.
 *
 * @Route("contracttype")
 */
class ContractTypeController extends Controller
{
    /**
     * Lists all contractType entities.
     *
     * @Route("/", name="contracttype_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $contractTypes = $em->getRepository('AppBundle:ContractType')->findAll();

        return $this->render('contracttype/index.html.twig', array(
            'contractTypes' => $contractTypes,
        ));
    }

    /**
     * Creates a new contractType entity.
     *
     * @Route("/new", name="contracttype_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $contractType = new Contracttype();
        $form = $this->createForm('AppBundle\Form\ContractTypeType', $contractType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contractType);
            $em->flush();

            return $this->redirectToRoute('contracttype_show', array('id' => $contractType->getId()));
        }

        return $this->render('contracttype/new.html.twig', array(
            'contractType' => $contractType,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a contractType entity.
     *
     * @Route("/{id}", name="contracttype_show")
     * @Method("GET")
     */
    public function showAction(ContractType $contractType)
    {
        $deleteForm = $this->createDeleteForm($contractType);

        return $this->render('contracttype/show.html.twig', array(
            'contractType' => $contractType,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing contractType entity.
     *
     * @Route("/{id}/edit", name="contracttype_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ContractType $contractType)
    {
        $deleteForm = $this->createDeleteForm($contractType);
        $editForm = $this->createForm('AppBundle\Form\ContractTypeType', $contractType);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('contracttype_edit', array('id' => $contractType->getId()));
        }

        return $this->render('contracttype/edit.html.twig', array(
            'contractType' => $contractType,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a contractType entity.
     *
     * @Route("/{id}", name="contracttype_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ContractType $contractType)
    {
        $form = $this->createDeleteForm($contractType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($contractType);
            $em->flush();
        }

        return $this->redirectToRoute('contracttype_index');
    }

    /**
     * Creates a form to delete a contractType entity.
     *
     * @param ContractType $contractType The contractType entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ContractType $contractType)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('contracttype_delete', array('id' => $contractType->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
