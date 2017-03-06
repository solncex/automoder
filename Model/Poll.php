<?php
/**
 * Created by PhpStorm.
 * User: ilham
 * Date: 17.02.17
 * Time: 18:36
 */

namespace Model;


use Kernel\Mysql;

/**
 * Класс для работы с опросами
 *
 * @package Model
 */
class Poll extends Mysql
{
    /** @var string Таблица с названиями опросов и пр. общей информацией */
    private $table_poll = '`poll`';

    /** @var string Таблица с вопросами */
    private $table_question = '`question`';

    /** @var string Таблица с ответами */
    private $table_answer = '`answer`';

    /** @var string Таблица с общей информацией о прохождении опроса */
    private $table_result = '`results`';

    /** @var string Таблица с результатами */
    private $table_result_counter = '`results_counter`';

    /** @var array Возможные состояния опросов */
    private $statuses = array();

    /** Автивные опросы */
    const POLL_ACTIVE = 1;

    /** Черновик */
    const POLL_DRAFT = 2;

    /** Закрытые опросы */
    const POLL_CLOSE = 3;

    public function __construct($host = null, $user = null, $password = null, $db = null)
    {
        parent::__construct($host, $user, $password, $db);

        $this->statuses[self::POLL_ACTIVE] = 'Активный';
        $this->statuses[self::POLL_DRAFT] = 'Черновик';
        $this->statuses[self::POLL_CLOSE] = 'Закрыт';
    }

    /**
     * Возвращает сисок статусов
     *
     * @return array
     */
    public function getStatuses(){
        return $this->statuses;
    }

    /**
     * Возвращает информацию об опросе по его идентификатору
     *
     * @param $id
     * @return bool
     */
    public function getById($id){
        $poll = $this->getRow("select * from {$this->table_poll} WHERE id=:id limit 1;",['id'=>$id]);

        if(!empty($poll)){
            $poll['question'] = $this->getRowsKey('id',"select * from {$this->table_question} WHERE poll_id=:poll_id ", ['poll_id' => $poll['id']]);
        }

        if(!empty($poll['question'])){
            $questionIds = array_keys($poll['question']);
            $answer = $this->getRowsKey('id', "select * from {$this->table_answer} WHERE question_id IN(:qIds)",['qIds'=>$questionIds]);
            if(!empty($answer)){
                foreach($answer as $answerId => $row){
                    $poll['question'][$row['question_id']]['answer'][$answerId] = $row;
                }
            }
        }
        return $poll;
    }

    /**
     * Возвращает список опросов по фильтру
     *
     * @param string $filter
     * @param array $filterData
     * @return array
     */
    public function getPollsByFilter($filter='',$filterData=array()){
        if(!empty($filter)){
            $filter = "where $filter";
        }
        $result = $this->getRows("select * from {$this->table_poll} $filter order by `id` DESC;",$filterData);
        return $result;
    }

    /**
     * Проверяет наличие активного опроса
     *
     * @return bool id опроса если есть активный опрос, иначе false
     */
    public function activePollExist(){
        $data = $this->getRow("select id from {$this->table_poll} WHERE `status`='".self::POLL_ACTIVE."' limit 1;");
        if(!empty($data)){
            return $data['id'];
        }
        return false;
    }

    /**
     * Возвращает количество прохождений опроса по его индетификатору
     *
     * @param int $id индентификатор опроса
     * @return int
     */
    public function getResultCountById($id){
        $data = $this->getRow("select count(id) as `count` from {$this->table_result} WHERE poll_id=:poll_id ", ['poll_id' => $id]);
        return (!empty($data['count']))?$data['count']:0;
    }

    /**
     * Возвращает релуьтаты опроса
     *
     * @param int $id идентификатор опроса
     * @return array
     */
    public function getResultById($id){
        $data = $this->getRows("SELECT c.* FROM {$this->table_result} as r
          left join {$this->table_result_counter} as c on r.id=c.result_id WHERE r.poll_id=:poll_id",['poll_id'=>$id]);

        $result = array();
        if(!empty($data)){
            foreach($data as $row){
                if(empty($result[$row['question_id']][$row['answer_id']])){
                    $result[$row['question_id']][$row['answer_id']] = 1;
                }else{
                    $result[$row['question_id']][$row['answer_id']] ++;
                }
            }
        }
        return $result;
    }

    /**
     * Возвращает количество результатов опроса по фильтру
     *
     * @param mixed $filter массив с данными
     * @return int
     */
    public function getResultCountByFilter($filter){
        $count = count($filter);
        $questions = array_keys($filter);
        $answers = array();
        foreach($filter as $qId => $answ) {
            foreach ($answ as $aId) {
                $answers[] = $aId;
            }
        }

        /*
        $data = $this->getRows("SELECT result_id FROM {$this->table_result_counter}
          WHERE question_id in(:questions) and answer_id in(:answers)
          group by result_id HAVING count(result_id)>=:count",['questions'=>$questions,'answers'=>$answers, 'count'=>$count]);
        */

        $data = $this->getRows("select s.result_id from
          (SELECT c.result_id,c.question_id FROM {$this->table_result_counter} as c WHERE c.question_id in(:questions) and c.answer_id in(:answers)
            GROUP by concat_ws('_',c.result_id,c.question_id)) as s
            GROUP by s.result_id HAVING count(s.result_id)>=:count",['questions'=>$questions,'answers'=>$answers, 'count'=>$count]);

        return count($data);
    }

    /**
     * Возвращает результаты опроса по фильтру
     *
     * @param mixed $filter массив с данными
     * @return array
     */
    public function getResultByFilter($filter){
        $count = count($filter);
        $questions = array_keys($filter);
        $answers = array();
        foreach($filter as $qId => $answ) {
            foreach ($answ as $aId) {
                $answers[] = $aId;
            }
        }
        /*
        $data = $this->getRows("select * from results_counter where result_id in(
          SELECT result_id FROM {$this->table_result_counter}
          WHERE question_id in(:questions) and answer_id in(:answers)
          group by result_id HAVING count(result_id)>=:count)",['questions'=>$questions,'answers'=>$answers, 'count'=>$count]);
        */


        $data = $this->getRows("select * from results_counter where result_id in(select s.result_id from
          (SELECT c.result_id,c.question_id FROM {$this->table_result_counter} as c WHERE c.question_id in(:questions) and c.answer_id in(:answers)
            GROUP by concat_ws('_',c.result_id,c.question_id)) as s
            GROUP by s.result_id HAVING count(s.result_id)>=:count)",['questions'=>$questions,'answers'=>$answers, 'count'=>$count]);

        $result = array();
        if(!empty($data)){
            foreach($data as $row){
                if(empty($result[$row['question_id']][$row['answer_id']])){
                    $result[$row['question_id']][$row['answer_id']] = 1;
                }else{
                    $result[$row['question_id']][$row['answer_id']] ++;
                }
            }
        }
        return $result;
    }

    /**
     * Сохраняет результат прохождения опроса
     *
     * @param int $pollId  идентификатор опроса
     * @param mixed $data массив с данными
     * @return bool|int идентификатор сохраненного результата, при ошибке false
     * @throws \Exception
     */
    public function saveResult($pollId,$data){
        $user = time();
        $resultId = $this->insert('results', ['poll_id' => $pollId, 'user' => $user]);
        if(!$resultId){
            return false;
        }
        foreach($data as $qId => $answers){
            foreach($answers as $aId){
                $save = array(
                    'result_id' => $resultId,
                    'question_id' => $qId,
                    'answer_id' => $aId,
                );
                $this->insert('results_counter', $save);
            }
        }
        return $resultId;
    }

    /**
     * Сохраняет(обновляет) название опроса, либо добавляет новый, если не указан идентификатор опроса
     *
     * @param null $id идентификатор опроса
     * @param string $name название опроса
     * @return bool|int идентификатор сохраненного опроса
     * @throws \Exception
     */
    public function addPoll($id = null, $name){
        if (is_null($id)) {
            $result = $this->insert($this->table_poll, ['name' => $name]);
        }else{
            $result = $this->update($this->table_poll, "id=:id",['id' => $id], ['name' => $name]);
            if($result){
                $result = $id;
            }
        }
        return $result;
    }

    /**
     * Добавляет вопросы(и ответы к ним) к опросу $pollId
     *
     * @param int $pollId идентификатор опроса
     * @param mixed $data массив с данными
     * @return bool
     * @throws \Exception
     */
    public function addQuestions($pollId,$data){
        $this->deleteRows($this->table_question,"poll_id=:poll_id", ['poll_id' => $pollId]);
        foreach($data as $question){
            $answers = $question['answers'];
            unset($question['answers']);

            $questionId = $this->insert($this->table_question, $question);
            if(!$questionId){
                return false;
            }
            if(!$this->addAnswer($questionId, $answers)){
                return false;
            }
        }
        return true;
    }

    /**
     * Добовляет возможные варианты ответов для вопроса $questionId
     *
     * @param int $questionId
     * @param mixed $data массив с данными
     * @return bool
     * @throws \Exception
     */
    private function addAnswer($questionId,$data){
        $this->deleteRows($this->table_answer, "question_id=:question_id", ['question_id' => $questionId]);
        foreach($data as $row){
            $row['question_id'] = $questionId;
            $result = $this->insert($this->table_answer, $row);
            if(!$result){
                return false;
            }
        }
        return true;
    }

    /**
     * Изменяет общую информацию по опросу
     *
     * @param int $poll_id идентификатор опроса
     * @param array $data массив с данными
     * @return bool
     * @throws \Exception
     */
    public function setPollById($poll_id, $data=array()){
        $filter = "id=:id";
        $filterData = ['id'=>$poll_id];
        # при попытке активации проверяем наличие уже активного опроса
        if(!empty($data['status']) && $data['status']==self::POLL_ACTIVE){
            if($this->activePollExist()){
                return false;
            }
        }
        return $this->update($this->table_poll, $filter, $filterData, $data);
    }

    /**
     * Удаляет опрос
     *
     * @param int $poll_id идентификатор опроса
     * @return bool
     * @throws \Exception
     */
    public function deletePollById($poll_id){
        return $this->deleteRows($this->table_poll, "id=:id", ['id' => $poll_id]);
    }
}