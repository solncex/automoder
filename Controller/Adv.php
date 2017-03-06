<?php
/**
 * Created by PhpStorm.
 * User: ilham
 * Date: 02.03.17
 * Time: 19:25
 */

namespace Controller;


use Model\Moderator;
use Model\Source;
use Model\SourceRules;

/**
 * Контроллер для работы с объявлениями
 *
 * @package Controller
 */
class Adv
{
    /**
     * Список объявлений
     *
     * @return array
     */
    public function indexAction(){
        $adv = new \Model\Adv();
        $data = $adv->loadByFilter();
        return [
            'adv' => $data,
        ];
    }

    /**
     * Редактирование объявлений
     *
     * @return array
     */
    public function editAction(){
        $id = (!empty($_GET['id'])) ? intval($_GET['id']) : null;
        $adv = new \Model\Adv();
        $source = new Source();
        $errorText = '';
        $data = array();
        $sourceData = $source->loadByFilter();

        if(!empty($_POST['adv'])){
            $data = $_POST['adv'];
            # обнуляем статус при изменении объявления
            $data['status'] = $adv::STATUS_IN_MODERATION;

            if (!empty($id)) {
                $result = $adv->setById($id, $data);
            }else{
                $result = $adv->add($data);
            }

            if($result){
                $moder = new Moderator();
                $moder->moderatedById($result);

                # чтобы при нажатии f5 не пытался еще раз сохранить.
                header("Location: /adv/edit/?id=$result");
            }else{
                $errorText = "Не удалось сохранить объявления. Попробуйте еще раз.";
            }
        }

        if (!empty($id) && !$errorText) {
            $data = $adv->getById($id);
        }

        return [
            'adv' => $data,
            'sources' => $sourceData,
            'message' => $errorText
        ];
    }

    /**
     * Просмотр истории модерации
     *
     * @return array
     */
    public function historyAction(){
        $id = (!empty($_GET['id'])) ? intval($_GET['id']) : null;
        $adv = new \Model\Adv();
        $moder = new Moderator();
        $data = $adv->getById($id);
        $history = $moder->loadHistoryByAdvId($id);


        return  [
            'adv' => $data,
            'history' => $history,
            'status' => $adv->statuses,
        ];
    }

    /**
     * Удаление объявления
     */
    public function deleteAction(){
        $id = (!empty($_GET['id'])) ? intval($_GET['id']) : null;
        $adv = new \Model\Adv();
        $adv->deleteById($id);

        header("Location: /adv/");
    }
}