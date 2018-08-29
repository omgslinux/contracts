<?php

namespace AppBundle\Controller;

use AppBundle\Entity\AwardType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Awardtype controller.
 *
 * @Route("awardtype")
 */
class AwardTypeController extends Controller
{
    /**
     * Lists all awardType entities.
     *
     * @Route("/", name="awardtype_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $awardTypes = $em->getRepository('AppBundle:AwardType')->findAll();

        return $this->render('awardtype/index.html.twig', array(
            'awardTypes' => $awardTypes,
        ));
    }

    /**
     * Creates a new awardType entity.
     *
     * @Route("/new", name="awardtype_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $awardType = new Awardtype();
        $form = $this->createForm('AppBundle\Form\AwardTypeType', $awardType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($awardType);
            $em->flush();

            return $this->redirectToRoute('awardtype_show', array('id' => $awardType->getId()));
        }

        return $this->render('awardtype/new.html.twig', array(
            'awardType' => $awardType,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a awardType entity.
     *
     * @Route("/{id}", name="awardtype_show")
     * @Method("GET")
     */
    public function showAction(AwardType $awardType)
    {
        $deleteForm = $this->createDeleteForm($awardType);

        return $this->render('awardtype/show.html.twig', array(
            'awardType' => $awardType,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing awardType entity.
     *
     * @Route("/{id}/edit", name="awardtype_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, AwardType $awardType)
    {
        $deleteForm = $this->createDeleteForm($awardType);
        $editForm = $this->createForm('AppBundle\Form\AwardTypeType', $awardType);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('awardtype_edit', array('id' => $awardType->getId()));
        }

        return $this->render('awardtype/edit.html.twig', array(
            'awardType' => $awardType,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a awardType entity.
     *
     * @Route("/{id}", name="awardtype_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, AwardType $awardType)
    {
        $form = $this->createDeleteForm($awardType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($awardType);
            $em->flush();
        }

        return $this->redirectToRoute('awardtype_index');
    }

    /**
     * Creates a form to delete a awardType entity.
     *
     * @param AwardType $awardType The awardType entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AwardType $awardType)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('awardtype_delete', array('id' => $awardType->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
