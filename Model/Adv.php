<?php
/**
 * Created by PhpStorm.
 * User: ilham
 * Date: 01.03.17
 * Time: 23:20
 */

namespace Model;

use Kernel\Mysql;

/**
 * Класс для работы с объявлениями
 *
 * @package Model
 */
class Adv extends Mysql
{
    private $table_adv = 'adv';

    const STATUS_IN_MODERATION = 0;
    const STATUS_MODERATED = 1;
    const STATUS_NOT_MODERATED = 2;

    public $statuses = [];

    public function __construct($host = null, $user = null, $password = null, $db = null)
    {
        parent::__construct($host, $user, $password, $db);
        $this->statuses = [
            self::STATUS_IN_MODERATION => 'Автомодерация',
            self::STATUS_MODERATED => 'Прошло автомодерацию',
            self::STATUS_NOT_MODERATED => 'Не прошло автомодерацию'
        ];
    }

    public function getById($id){
        return $this->getRow("select * from {$this->table_adv} WHERE id=:id limit 1", ['id' => $id]);
    }

    public function loadByFilter($filter='', $data=[]){
        if(empty(!$filter)){
            $filter = 'WHERE ' . $filter;
        }
        return $this->getRowsKey("id", "select * from {$this->table_adv} $filter", $data);
    }

    public function getCountByFilter($filter='', $data=[]){
        if(empty(!$filter)){
            $filter = 'WHERE ' . $filter;
        }
        $result = $this->getRow("select count(id) as cnt from {$this->table_adv} $filter", $data);
        return (!empty($result['cnt']))?$result['cnt']:0;
    }

    public function setById($id,$data){
        $result = $this->update($this->table_adv, "id=:id", ['id' => $id], $data);
        if ($result) {
            return $id;
        }else{
            return false;
        }
    }

    public function add($data){
        return $this->insert($this->table_adv, $data);
    }

    public function deleteById($advId){
        return $this->deleteRows($this->table_adv, "id=:id", ['id' => $advId], 1);
    }
}