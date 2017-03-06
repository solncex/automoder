<?php
/**
 * Created by PhpStorm.
 * User: ilham
 * Date: 16.02.17
 * Time: 19:47
 */

namespace Controller;

use Model\Adv;

/**
 * Контроллер галвной страницы
 *
 * @package Controller
 */
class Home
{
    public function indexAction(){
        $adv = new Adv();
        $data = $adv->loadByFilter();

        return [
            'adv' => $data,
            'status' => $adv->statuses,
        ];
    }
}