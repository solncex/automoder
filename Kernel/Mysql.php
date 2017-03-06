<?php
/**
 * Created by PhpStorm.
 * User: ilham
 * Date: 17.02.17
 * Time: 18:39
 */

namespace Kernel;

/**
 * Класс для работы с Mysql
 *
 * @package Kernel
 */
class Mysql
{
    /**
     * Идентификатор ссылки соединения с MySQL {@link \mysqli}.
     * @var \mysqli
     */
    protected $db = null;

    /**
     * @var string строка с последним запросом
     */
    protected $last_query = '';

    public function __construct($host=null,$user=null,$password=null,$db=null){
        $this->connect($host, $user, $password, $db);
        $this->db->set_charset('utf8');
    }

    /**
     *  Устанавливает кодировку по умолчанию
     *
     * @param $charset
     * @return bool
     */
    protected function setCharset($charset){
        if (!$this->db) {
            return false;
        }
        return $this->db->set_charset($charset);
    }

    /**
     * Устанавливает соединение с Mysql
     *
     * @param null $host
     * @param null $user
     * @param null $password
     * @param null $db
     * @return \mysqli
     * @throws \Exception
     */
    protected function connect($host=null,$user=null,$password=null,$db=null){
        if (is_null($host)) {
            $host = MYSQL_HOST;
        }
        if (is_null($user)) {
            $user = MYSQL_USER;
        }
        if (is_null($password)) {
            $password = MYSQL_PASSWORD;
        }
        if (is_null($db)) {
            $db = MYSQL_DB;
        }
         $this->db = @new \mysqli($host, $user, $password, $db);


        if($this->db->connect_errno){
            throw new \Exception(" Код ошибки MySql {$this->db->connect_errno}. " . $this->db->connect_error,100);
        }
        return $this->db;
    }

    /**
     * Выполняет запрос к Mysql
     *
     * @param string $sql строка запроса
     * @param array $params параметры запроса
     * @param string $keyName название поля которое будет являться ключом массива
     * @return array массив с данными
     * @throws \Exception
     */
    protected function select($sql,$params=array(),$keyName=''){
        if (!empty($params)) {
            foreach($params as $key => $val){
                if(is_array($val)){
                    foreach($val as $k => $v){
                        $val[$k] = $this->db->real_escape_string($v);
                    }
                    $in = implode("', '", $val);
                    $sql = str_replace(":$key", "'$in'",$sql);
                }else{
                    $val = $this->db->real_escape_string($val);
                    $sql = str_replace(":$key", "'$val'",$sql);
                }
            }
        }
        $result = $this->db->query($sql);
        $this->last_query = $sql;
        if($result !== false){
            $data = array();
            while ($row = $result->fetch_assoc()) {
                if (!empty($keyName) && array_key_exists($keyName, $row)) {
                    $data[$row[$keyName]] = $row;
                }else{
                    $data[] = $row;
                }
            }
            return $data;
        }else{
            throw new \Exception(" Код ошибки MySql {$this->db->errno}. " . $this->db->error,101);
        }
    }

    /**
     * Выполняет запрос и возвращает первый резлуьтат запроса
     *
     * @param $sql
     * @param array $params
     * @return bool
     * @throws \Exception
     */
    protected function getRow($sql,$params=array()){
        $data = $this->select($sql, $params);
        if (!empty($data)) {
            return $data[0];
        }
        return false;
    }

    /**
     * Выполняет запрос и возвращает все резлуьтаты запроса
     *
     * @param $sql
     * @param array $params
     * @return array
     * @throws \Exception
     */
    protected function getRows($sql,$params=array()){
        return $this->select($sql, $params);
    }

    /**
     * Выполняет запрос и возвращает резлуьтаты запроса установив $key в качестве ключя массива
     *
     * @param $key
     * @param $sql
     * @param array $params
     * @return array
     * @throws \Exception
     */
    protected function getRowsKey($key, $sql, $params=array()){
        return $this->select($sql, $params,$key);
    }

    /**
     * Выполненяет выражения INSERT
     *
     * @param string $table название таблицы
     * @param mixed $data массив с данными
     * @return mixed
     * @throws \Exception
     */
    protected function insert($table,$data){
        $str = '';
        foreach ($data as $key => $value) {
            if(!empty($str)) $str .= ', ';
            $str .= "`$key`='" . $this->db->real_escape_string($value) . "'";
        }
        $sql = "insert into $table set $str";
        $result = $this->db->query($sql);
        $this->last_query = $sql;
        if($result !== false){
            return $this->db->insert_id;
        }else{
            throw new \Exception(" Код ошибки MySql {$this->db->errno}. " . $this->db->error,103);
        }
    }

    /**
     * Выполненяет выражения INSERT с on DUPLICATE KEY UPDATE
     *
     * @param string $table название таблицы
     * @param mixed $data массив с данными
     * @return mixed
     * @throws \Exception
     */
    protected function insertUpdate($table,$data){
        $str = '';
        foreach ($data as $key => $value) {
            if(!empty($str)) $str .= ', ';
            $str .= "`$key`='" . $this->db->real_escape_string($value) . "'";
        }
        $sql = "insert into $table set $str on DUPLICATE KEY UPDATE $str";
        $result = $this->db->query($sql);
        $this->last_query = $sql;
        if($result !== false){
            return $this->db->insert_id;
        }else{
            throw new \Exception(" Код ошибки MySql {$this->db->errno}. " . $this->db->error,103);
        }
    }

    /**
     * Выполненяет выражения UPDATE
     *
     * @param string $table название таблицы
     * @param $filter
     * @param $filterData
     * @param $updateData
     * @return bool|\mysqli_result
     * @throws \Exception
     */
    protected function update($table,$filter,$filterData,$updateData){
        if (!empty($filterData)) {
            foreach($filterData as $key => $val){
                $val = $this->db->real_escape_string($val);
                $filter = str_replace(":$key", "'$val'",$filter);
            }
        }
        $str = '';
        foreach ($updateData as $key => $value) {
            if(!empty($str)) $str .= ', ';
            $str .= "`$key`='" . $this->db->real_escape_string($value) . "'";
        }
        $sql = "update $table set $str where $filter";
        $result = $this->db->query($sql);
        $this->last_query = $sql;
        if($result !== false){
            return $result;
        }else{
            throw new \Exception(" Код ошибки MySql {$this->db->errno}. " . $this->db->error,102);
        }
    }

    /**
     * Удаляет строки из таблицы
     *
     * @param string $table название таблицы
     * @param $filter
     * @param $filterData
     * @param int $limit
     * @return bool|\mysqli_result
     * @throws \Exception
     */
    protected function deleteRows($table,$filter,$filterData,$limit=0){
        if (!empty($filterData)) {
            foreach($filterData as $key => $val){
                $val = $this->db->real_escape_string($val);
                $filter = str_replace(":$key", "'$val'",$filter);
            }
        }
        $limitStr = '';
        if(!empty($limit)){
            $limitStr = "limit $limit";
        }
        $sql = "delete from $table where $filter $limitStr";
        $result = $this->db->query($sql);
        $this->last_query = $sql;
        if($result !== false){
            return $result;
        }else{
            throw new \Exception(" Код ошибки MySql {$this->db->errno}. " . $this->db->error,102);
        }
    }
}