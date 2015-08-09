<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class TransactionController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        return array(
                // ...
            );    }

    /**
     * @Route("/add")
     * @Template()
     */
    public function addAction()
    {
        return array(
                // ...
            );    }

    /**
     * @Route("/delete")
     * @Template()
     */
    public function deleteAction()
    {
        return array(
                // ...
            );    }

    /**
     * @Route("/update")
     * @Template()
     */
    public function updateAction()
    {
        return array(
                // ...
            );    }

}
