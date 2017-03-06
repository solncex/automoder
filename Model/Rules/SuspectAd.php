<?php
/**
 * Created by PhpStorm.
 * User: ilham
 * Date: 02.03.17
 * Time: 21:52
 */

namespace Model\Rules;

/**
 * Реализация правила "Подозрительное объявление"
 *
 * @package Model\Rules
 */
class SuspectAd extends AbstractRules
{

    public function runCheckById($advId){
        $data = $this->adv->getById($advId);

        if($data['owner_type']=='owner'){
            $filter = "id!=:id and owner_phone=:phone";
            $count = $this->adv->getCountByFilter($filter, ['id'=>$data['id'],'phone' => $data['owner_phone']]);
            if($count>1){
                return true;
            }
        }

        return false;
    }
}