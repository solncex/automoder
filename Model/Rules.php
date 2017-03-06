<?php
/**
 * Created by PhpStorm.
 * User: ilham
 * Date: 02.03.17
 * Time: 20:41
 */

namespace Model;

use Kernel\Mysql;

class Rules extends Mysql
{
    private $table_adv = 'rules';

    public function loadByFilter($filter='', $data=[]){
        if(empty(!$filter)){
            $filter = 'WHERE ' . $filter;
        }
        return $this->getRowsKey("id", "select * from {$this->table_adv} $filter", $data);
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
}