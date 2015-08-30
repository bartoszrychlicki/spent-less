<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Category controller.
 *
 * @Route("/statistics")
 */
class StatisticsController extends Controller
{
    /**
     * @Route("/", name="statistics")
     * @Template()
     */
    public function indexAction()
    {
        /**
         * dla okresu (miesiaca)
         * oraz dla całego orkesu danych
         * info: ile srednio dziennie wydajesz, w rozbiciu na kategorie tez
         * możliwośc zobaczenia jak krzstaltowaly sie w czasie wydatki na calosc jak i na kategorie
         * to samo z przychodami
         * w granulacji miesiecznej jak i dziennej
         * podsumowanie porzychodow: skad mam przychody
         * filtrowanie przez tagi
         * dzisiaj chce sie dowiedziec ile wydaje dziennie srednio
        */
        
        $em = $this->getDoctrine()->getManager();
        
        $transactionRepo = $em->getRepository('AppBundle:Transaction');
        
        // get spendings per date

        return array(
            'avgDailyExpenses'  => $transactionRepo->getAvgDailyAmount(),
            'categorySpendings' => $transactionRepo->getCategorySpendings(),
            'expensesByDay'     => $transactionRepo->getExpensesByDay()
            );    
        
    }
}
