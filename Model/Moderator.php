<?php
/**
 * Created by PhpStorm.
 * User: ilham
 * Date: 02.03.17
 * Time: 20:41
 */

namespace Model;

use Kernel\Mysql;
use Model\Rules\Runner;

class Moderator extends Mysql
{
    private $table = 'status_history';

    private $table_rules = 'rules';

    public function moderatedById($advId){
        $adv = new Adv();
        $sourceRules = new SourceRules();

        $data = $adv->getById($advId);
        $rules = $sourceRules->loadBySourceId($data['source_id']);

        if(!empty($rules)){
            $runner = new Runner();
            $notModerated = array();
            foreach($rules as $rule){
                $result = $runner->runByName($rule['rule_name'],$advId);
                if($result){
                    $notModerated = $rule;
                    break;
                }
            }
            $status = [
                'adv_id' => $advId,
                'status' => (empty($notModerated))?$adv::STATUS_MODERATED:$adv::STATUS_NOT_MODERATED,
                'rule_id' => (!empty($notModerated['rule_id']))?$notModerated['rule_id']:0,
            ];
            $this->saveStatus($status);
            $adv->setById($advId, ['status' => $status['status']]);
        }
    }

    public function loadHistoryByAdvId($advId){
        return $this->getRows("select r.name,r.description,h.* from {$this->table} as h
          LEFT JOIN {$this->table_rules} as r on h.rule_id=r.id
          WHERE adv_id=:id order by date DESC ", ['id' => $advId]);
    }

    public function saveStatus($data){
        return $this->insert($this->table, $data);
    }
}