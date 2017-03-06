<?php
/**
 * Created by PhpStorm.
 * User: ilham
 * Date: 02.03.17
 * Time: 21:52
 */

namespace Model\Rules;

/**
 * Реализация правила "Описание содержит “стоп слова”"
 *
 * @package Model\Rules
 */
class StopWords extends AbstractRules
{

    private $files_path = "/data/";

    private $file_name = 'stop-words.txt';

    public function runCheckById($advId){
        $data = $this->adv->getById($advId);
        $file = ROOT_PATH . $this->files_path . $this->file_name;
        foreach ($this->readFile($file) as $word) {
            if(empty($word))continue;

            if (false !== mb_stristr($data['description'],trim($word), null, 'utf8')) {
                return true;
            }
        }
        return false;
    }
}