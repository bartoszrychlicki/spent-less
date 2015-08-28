<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * TransactionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TransactionRepository extends EntityRepository
{
    function findAllOrderedByDate($month = 'current', $year = 'current')
    {
        if($month == 'current') {
            $month = date("m");
        }
        if($year == 'current') {
            $year = date('Y');
        }
        $fromDate   = \DateTime::createFromFormat('j-m-Y H:i:s', '1-'.$month.'-'.$year.' 00:00:00');
        $endDayOfTheMonth = date("t");
        $toDate     = \DateTime::createFromFormat('j-m-Y H:i:s', $endDayOfTheMonth.'-'.$month.'-'.$year.' 23:59:59');

        $query = $this->createQueryBuilder('t')
            ->orderBy('t.createdAt', 'DESC')
            ->addOrderBy('t.id', 'DESC')
            ->where('t.createdAt >= :from_date and t.createdAt <= :to_date')
            ->setParameter('from_date', $fromDate)
            ->setParameter('to_date', $toDate)
            ->getQuery();
        
        return $query->getResult();
    }
    
    /**
     * If fromDate is null it takes the earliest tranaction.
     * if toDate is null is takes the latest transaction.
     */
    function getAvgDailyAmount(\DateTime $fromDate = null, \DateTime $toDate = null, $isExpense = 1) {
        $query = $this->createQueryBuilder('t')
            ->select('SUM(t.amount) as sum_amount')
            ->addSelect('MIN(t.createdAt as date_start')
            //->where('t.createdAt >= :from_date and t.createdAt <= :to_date')
            ->where('t.isExpense = ?1')
            ->setParameter(1, $isExpense)
            //->setParameter('from_date', $fromDate)
            //->setParameter('to_date', $toDate)
            ->getQuery();
        
        $result = $query->getSingleResult();
        
        // caluclate days amount
        $now = time(); // or your date as well
        $your_date = strtotime($result['date_start']);
        $datediff = $now - $your_date;
        $daysAmount = floor($datediff/(60*60*24));
        return array(
            'avg_daily_expense' => $result['sum_amount']/$daysAmount,
            'start_from'        => $result['date_start'],
            'days_count'        => $daysAmount
            );
    }
    
    public function getCategorySpendings(\DateTime $fromDate = null, \DateTime $toDate = null) 
    {
        $query = $this->createQueryBuilder('t')
            ->select('SUM(t.amount) as sum_category')
            ->addSelect('c.name')
            //->addSelect('c')
            ->leftJoin('t.category','c')
            ->where('t.isExpense = 1')
            ->groupBy('t.category')
            ->orderBy('sum_category', 'DESC')
            ->getQuery();
            
        return $result = $query->getResult();
        
    }

    public function getPayeeList()
    {
            $query = $this->createQueryBuilder('t')
            ->select('DISTINCT t.payee')
            ->where('t.payee is not null')
            ->orderBy('t.payee', 'ASC')
            ->getQuery();
            
        $result = $query->getArrayResult();
        $arrayResult = array();
        foreach($result as $payee) {
             $arrayResult[] = $payee['payee'];
        }
        return $arrayResult;
    }
}
