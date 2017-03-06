<?php
/**
 * Created by PhpStorm.
 * User: ilham
 * Date: 02.03.17
 * Time: 23:51
 */

namespace Model\Rules;

/**
 * Запуск  классов модерации по его названию
 * @package Model\Rules
 */
class Runner
{
    public function runByName($name,$advId){
        $defaultMethod = 'runCheckById';
        $name = __NAMESPACE__ . '\\' . $name;
        if (class_exists($name)) {
            $obj = new $name;
            if (is_callable(array($obj,$defaultMethod))) {
                return $obj->$defaultMethod($advId);
            }else{
                throw new \Exception("Метод {$defaultMethod} не существует",302);
            }
        }else{
            throw new \Exception("Класс {$name} не существует",301);
        }
    }
}