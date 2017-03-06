<?php
/**
 * Created by PhpStorm.
 * User: ilham
 * Date: 02.03.17
 * Time: 21:52
 */

namespace Model\Rules;

/**
 * Реализация правила "Найден дубликат"
 *
 * @package Model\Rules
 */
class DoubleAd extends AbstractRules
{

    public function runCheckById($advId){
        $data = $this->adv->getById($advId);

        $notEmpty = !empty($data['address']) && !empty($data['floor']) && !empty($data['rooms'])
            && !empty($data['area']) && !empty($data['price']);

        if($notEmpty){
            $filter = "id!=:id and address=:address";
            $filter .= " and floor=:floor";
            $filter .= " and rooms=:rooms";
            $filter .= " and area=:area";
            $filter .= " and price=:price";
            $count = $this->adv->getCountByFilter($filter, $data);
            if($count){
                return true;
            }
        }
        return false;
    }
}