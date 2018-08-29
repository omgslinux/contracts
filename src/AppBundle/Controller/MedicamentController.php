<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Medicament;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Medicament controller.
 *
 * @Route("medicament")
 */
class MedicamentController extends Controller
{
    /**
     * Lists all medicament entities.
     *
     * @Route("/", name="medicament_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $medicaments = $em->getRepository('AppBundle:Medicament')->findAll();

        return $this->render('medicament/index.html.twig', array(
            'medicaments' => $medicaments,
        ));
    }

    /**
     * Creates a new medicament entity.
     *
     * @Route("/new", name="medicament_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $medicament = new Medicament();
        $form = $this->createForm('AppBundle\Form\MedicamentType', $medicament);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($medicament);
            $em->flush();

            return $this->redirectToRoute('medicament_show', array('id' => $medicament->getId()));
        }

        return $this->render('medicament/new.html.twig', array(
            'medicament' => $medicament,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a medicament entity.
     *
     * @Route("/{id}", name="medicament_show")
     * @Method("GET")
     */
    public function showAction(Medicament $medicament)
    {
        $deleteForm = $this->createDeleteForm($medicament);

        return $this->render('medicament/show.html.twig', array(
            'medicament' => $medicament,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing medicament entity.
     *
     * @Route("/{id}/edit", name="medicament_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Medicament $medicament)
    {
        $deleteForm = $this->createDeleteForm($medicament);
        $editForm = $this->createForm('AppBundle\Form\MedicamentType', $medicament);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('medicament_edit', array('id' => $medicament->getId()));
        }

        return $this->render('medicament/edit.html.twig', array(
            'medicament' => $medicament,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a medicament entity.
     *
     * @Route("/{id}", name="medicament_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Medicament $medicament)
    {
        $form = $this->createDeleteForm($medicament);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($medicament);
            $em->flush();
        }

        return $this->redirectToRoute('medicament_index');
    }

    /**
     * Creates a form to delete a medicament entity.
     *
     * @param Medicament $medicament The medicament entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Medicament $medicament)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('medicament_delete', array('id' => $medicament->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
