<?php

namespace AppBundle\Controller;

use AppBundle\Entity\House;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * House controller.
 *
 * @Route("house")
 */
class HouseController extends Controller
{
    /**
     * Lists all house entities.
     *
     * @Route("/", name="house_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $houses = $em->getRepository('AppBundle:House')->findAll();

        return $this->render('house/index.html.twig', array(
            'houses' => $houses,
        ));
    }

    /**
     * Creates a new house entity.
     *
     * @Route("/new", name="house_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $house = new House();
        $form = $this->createForm('AppBundle\Form\HouseType', $house);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($house);
            $em->flush();

            return $this->redirectToRoute('house_show', array('id' => $house->getId()));
        }

        return $this->render('house/new.html.twig', array(
            'house' => $house,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a house entity.
     *
     * @Route("/{id}", name="house_show")
     * @Method("GET")
     */
    public function showAction(House $house)
    {
        $deleteForm = $this->createDeleteForm($house);

        return $this->render('house/show.html.twig', array(
            'house' => $house,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing house entity.
     *
     * @Route("/{id}/edit", name="house_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, House $house)
    {
        $deleteForm = $this->createDeleteForm($house);
        $editForm = $this->createForm('AppBundle\Form\HouseType', $house);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('house_edit', array('id' => $house->getId()));
        }

        return $this->render('house/edit.html.twig', array(
            'house' => $house,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a house entity.
     *
     * @Route("/{id}", name="house_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, House $house)
    {
        $form = $this->createDeleteForm($house);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($house);
            $em->flush();
        }

        return $this->redirectToRoute('house_index');
    }

    /**
     * Creates a form to delete a house entity.
     *
     * @param House $house The house entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(House $house)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('house_delete', array('id' => $house->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
