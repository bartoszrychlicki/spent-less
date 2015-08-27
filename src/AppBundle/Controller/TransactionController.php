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
     * @Route("/{month}/{year}", name="transaction", defaults={"month" = "current", "year" = "current"}, requirements={
     *      "month": "\d+",
     *      "year": "\d+"
     * })
     * @Method("GET")
     * @Template()
     */
    public function indexAction($month, $year)
    {
        $em = $this->getDoctrine()->getManager();
        $tagManager = $this->get('fpn_tag.tag_manager');
        
        if($month == 'current') {
            $month = date("m");
        }
        if($year == 'current') {
            $year = date('Y');
        }
        
        $entities = new ArrayCollection();
        
        $expenseSum = 0;
        $incomeSum = 0;
        foreach($em->getRepository('AppBundle:Transaction')->findAllOrderedByDate($month, $year) as $row) {
            $tagManager->loadTagging($row);
            $entities->add($row);
            if ($row->getIsExpense() == true) {
                $expenseSum = $expenseSum + $row->getAmount();
            } elseif($row->getIsExpense() == false) {
                $incomeSum = $incomeSum + $row->getAmount();
            }
        }
        
        $balance = $incomeSum - $expenseSum;
        

        
        return array(
            'entities' => $entities,
            'expenseSum' => $expenseSum,
            'incomeSum' => $incomeSum,
            'balance' => $balance,
            'status' => $this->_getStatus($balance),
            'month' => $month,
            'year' => $year
        );
    }
    
    private function _getStatus($balance)
    {
        $level = 'info';
        if ($balance < 0) {
            $message =  'W tym okresie wydajesz więcej niż zarobiłeś. Zwolnij!';
            $level = 'danger';
        } else if ($balance == 0) {
            $message =  'Wydajesz dokładnie tyle ile zarabiasz. Spróbuj wydać troche mniej aby zarobic.';
            $level = 'warning';
        } else if ($balance > 0) {
            $message =  'Dobra robota! Wydajesz mniej niż zarabiasz. Możesz się poklepać po ramieniu i nagrodzić.';
            $level = 'success';
        }
        return array('message' => $message, 'level' => $level);
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
        
        // options for form
        $options = array();
        $options['preferred_choices'] = $em->getRepository('AppBundle:Category')->getMostPopularCategories(true, 3);
        $form = $this->createForm(new TransactionType($options), $entity, array(
            'action' => $this->generateUrl('transaction_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array(
            'label' => 'Dodaj', 
            'attr' => array(
                'class' => 'btn-lg btn-block', 
                'role' => 'button',
                'data-loading-text' => 'Dodaje...'
                )
            )
        );

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
        $em = $this->getDoctrine()->getManager();

        // options for form
        $options = array();
        $options['preferred_choices'] = $em->getRepository('AppBundle:Category')->getMostPopularCategories(true, 3);
        $form = $this->createForm(new TransactionType($options), $entity, array(
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
            return $this->redirect($this->generateUrl('transaction'));
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
