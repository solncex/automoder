<?php
/**
 * Created by PhpStorm.
 * User: ilham
 * Date: 16.02.17
 * Time: 6:10
 */

namespace Kernel;

/**
 * Класс для работы со страницами сайта
 *
 * @package Kernel
 */
class Kernel
{
    /**
     * Вызывает метод(action) страницы по его url
     *
     * @param string $url url страницы
     * @throws \Exception
     */
    public function execution($url){
        $page = $this->parseUrl($url);
        if (class_exists($page['class'])) {
            $obj = new $page['class'];
            if (is_callable(array($obj,$page['method']))) {
                View::execution($page,$obj->$page['method']());
            }else{
                throw new \Exception("Метод {$page['method']} не существует",2);
            }
        }else{
            throw new \Exception("Класс {$page['class']} не существует",1);
        }
    }

    /**
     * Парсит строку url
     *
     * @param string $url строка url
     * @return array массив с данными
     */
    private function parseUrl($url){
        $data = array(
            'class' => '',
            'method' => '',
        );
        $path = parse_url($url, PHP_URL_PATH);
        $parts = explode('/', trim($path, ' /'));
        $data['class_name'] = $data['class'] = (!empty($parts[0]))?$parts[0]:'home';
        $data['method_name'] = $data['method'] = (!empty($parts[1]))?$parts[1]:'index';

        $data['class'] = '\Controller\\' . ucfirst($data['class']);
        $data['method'] .= "Action";
        return $data;
    }
}