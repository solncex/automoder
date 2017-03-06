<?php
/**
 * Created by PhpStorm.
 * User: ilham
 * Date: 02.03.17
 * Time: 20:41
 */

namespace Model;

use Kernel\Mysql;

class SourceRules extends Mysql
{
    private $table = 'source_rules';

    private $table_rules = 'rules';

    public function loadByFilter($filter='', $data=[]){
        if(empty(!$filter)){
            $filter = 'WHERE ' . $filter;
        }
        return $this->getRowsKey("id", "select * from {$this->table_adv} $filter", $data);
    }

    public function loadBySourceId($sourceId){
        return $this->getRowsKey("id", "select sr.id,sr.source_id,r.id as rule_id,r.name as rule_name
          from {$this->table} as sr
          LEFT JOIN {$this->table_rules} as r ON sr.rule_id=r.id
          WHERE sr.source_id=:source_id ", ['source_id'=>$sourceId]);
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
        return $this->insert($this->table, $data);
    }
}