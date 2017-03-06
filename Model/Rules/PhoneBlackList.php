<?php
/**
 * Created by PhpStorm.
 * User: ilham
 * Date: 02.03.17
 * Time: 21:52
 */

namespace Model\Rules;

/**
 * Реализация правила "Номер телефона собственника находится в “черном списке”"
 *
 * @package Model\Rules
 */
class PhoneBlackList extends AbstractRules
{

    private $files_path = "/data/";
    private $file_name = 'phones.txt';

    public function runCheckById($advId){
        $data = $this->adv->getById($advId);
        $file = ROOT_PATH . $this->files_path . $this->file_name;
        foreach ($this->readFile($file) as $phone) {
            if ($data['owner_phone'] == $phone) {
                return true;
            }
        }
        return false;
    }


}