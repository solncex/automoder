<?php
/**
 * Created by PhpStorm.
 * User: ilham
 * Date: 02.03.17
 * Time: 21:52
 */

namespace Model\Rules;

/**
 * Реализация правила "Несоответствие описания"
 *
 * @package Model\Rules
 */
class MismatchDesc extends AbstractRules
{

    public function runCheckById($advId){
        $words = ['продажа от собственника'];
        $data = $this->adv->getById($advId);

        if($data['owner_type']=='realtor'){
            foreach($words as $word){
                if (false !== mb_stristr($data['description'],trim($word), null, 'utf8')) {
                    return true;
                }
            }
        }

        return false;
    }
}