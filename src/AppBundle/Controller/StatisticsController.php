<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use \Colors\RandomColor;

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
            'expensesByDayChartData_labels'     => json_encode(array_keys($this->getJsonDataForDailyExpensesChart())),
            'expensesByDayChartData_values'     => json_encode(array_values($this->getJsonDataForDailyExpensesChart())),
            'categoryExpensesChartData'         => json_encode($this->getJsonDataForCategoryExpensesChart())
            );    
        
    }
    
    /**
     * Fetch data for daily expenses graph. Includes two elements: labels, and values.
     * */
    private function getJsonDataForDailyExpensesChart()
    {
        $em = $this->getDoctrine()->getManager();
        $transactionRepo = $em->getRepository('AppBundle:Transaction');
        $elements = $transactionRepo->getExpensesByDay();
        
        $return = array(); 
        foreach($elements as $element) {
            $return[$element['createdAt']->format("d-m-Y")] = $element['daily_expenses_sum'];
        }
        
        return $return;
    }
    
    private function getJsonDataForCategoryExpensesChart()
    {
        $em = $this->getDoctrine()->getManager();
        $transactionRepo = $em->getRepository('AppBundle:Transaction');
        
        $return = array();
        foreach($transactionRepo->getCategorySpendings() as $category) {
            $return[] = array(
                'label' => $category['name'],
                'value' => ceil($category['sum_category']),
                'color' => RandomColor::one(),
                );
        }
        
        return $return;
        
    }
}
