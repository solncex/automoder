<?php
/**
 * Created by PhpStorm.
 * User: ilham
 * Date: 16.02.17
 * Time: 20:37
 */

namespace Kernel;

/**
 * Класс для сбора html кода страницы
 *
 * @package Kernel
 */
class View
{
    const VIEW_PATH = '/view/';

    /**
     * Генерирует html страницу
     *
     * @param string $file шаблон страницы
     * @param mixed $data массив с данными
     * @return string возвращает html код страницы
     * @throws \Exception
     */
    public static function render($file, $data)
    {
        $path = ROOT_PATH . self::VIEW_PATH . $file;
        if (is_readable($path)) {
            ob_start();
            require_once $path;
            $html = ob_get_contents();
            ob_end_clean();
            return $html;
        }else{
            throw new \Exception('Файл ' . $path . ' не существует',3);
        }
    }

    /**
     * Отображает сгенерированный код страницы
     *
     * @param string $file шаблон страницы
     * @param mixed $data массив с данными
     * @throws \Exception
     */
    public static function display($file, $data){
        $html = self::render($file, $data);
        print $html;
    }

    /**
     * Соберает полную страницу html
     *
     * @param $page
     * @param $data
     * @throws \Exception
     */
    public static function execution($page,$data){
        $file = $page['class_name'] . '/' . $page['method_name'] . '.php';
        self::display(
            'layout/layout.php', [
                'content' => self::render($file, $data),
                'current_page' => $page['class_name'] ]
        );
    }
}