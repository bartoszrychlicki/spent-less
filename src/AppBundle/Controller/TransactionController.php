<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Transaction;
use AppBundle\Form\TransactionType;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Transaction controller.
 *
 * @Route("/transaction")
 */
class TransactionController extends Controller
{

    /**
     * Lists all Transaction entities.
     *
     * @Route("/", name="transaction")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $tagManager = $this->get('fpn_tag.tag_manager');

        $entities = new ArrayCollection();
        foreach($em->getRepository('AppBundle:Transaction')->findAllOrderedByDate() as $row) {
            $tagManager->loadTagging($row);
            $entities->add($row);
        }
        
        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Transaction entity.
     *
     * @Route("/", name="transaction_create")
     * @Method("POST")
     * @Template("AppBundle:Transaction:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Transaction();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $tagManager = $this->get('fpn_tag.tag_manager');
            //create or load tags
            $tags = $form->get("tags")->getData();

            // Load or create a list of tags
            $tagNames = $tagManager->splitTagNames($tags);
            $tags = $tagManager->loadOrCreateTags($tagNames);

            // Add a list of tags on your taggable resource..
            $tagManager->addTags($tags, $entity);

            $em->persist($entity);
            $em->flush();

            $tagManager->saveTagging($entity);
            $tagManager->loadTagging($entity);
            return $this->redirect($this->generateUrl('transaction'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Transaction entity.
     *
     * @param Transaction $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Transaction $entity)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new TransactionType(array(
            'em' => $em)
            ), $entity, array(
            'action' => $this->generateUrl('transaction_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Transaction entity.
     *
     * @Route("/new", name="transaction_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Transaction();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Transaction entity.
     *
     * @Route("/{id}", name="transaction_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Transaction')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Transaction entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Transaction entity.
     *
     * @Route("/{id}/edit", name="transaction_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Transaction')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Transaction entity.');
        }
        $tagManager = $this->get('fpn_tag.tag_manager');
        $tagManager->loadTagging($entity);
        
        $editForm = $this->createEditForm($entity);
        $tags = join(', ', $entity->getTags()->map(
                function($row) {
                    return $row->getName();
                }
            )->toArray()
            );
            
        $editForm->get('tags')->setData($tags);
        //var_dump($editForm); exit();
        $deleteForm = $this->createDeleteForm($id);


        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Transaction entity.
    *
    * @param Transaction $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Transaction $entity)
    {
        $form = $this->createForm(new TransactionType(), $entity, array(
            'action' => $this->generateUrl('transaction_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Transaction entity.
     *
     * @Route("/{id}", name="transaction_update")
     * @Method("PUT")
     * @Template("AppBundle:Transaction:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Transaction')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Transaction entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $tagManager = $this->get('fpn_tag.tag_manager');
            //create or load tags
            $tags = $editForm->get("tags")->getData();

            // Load or create a list of tags
            $tagNames = $tagManager->splitTagNames($tags);
            $tags = $tagManager->loadOrCreateTags($tagNames);

            // Add a list of tags on your taggable resource..
            $tagManager->addTags($tags, $entity);

            $em->flush();

            $tagManager->saveTagging($entity);
            return $this->redirect($this->generateUrl('transaction_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Transaction entity.
     *
     * @Route("/{id}", name="transaction_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Transaction')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Transaction entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('transaction'));
    }

    /**
     * Creates a form to delete a Transaction entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('transaction_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
