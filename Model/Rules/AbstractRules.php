<?php
/**
 * Created by PhpStorm.
 * User: ilham
 * Date: 02.03.17
 * Time: 21:50
 */

namespace Model\Rules;

use Model\Adv;

/**
 * Абстрактынй класс для реализации правила модерации
 *
 * @package Model\Rules
 */
abstract class AbstractRules
{

    /** @var Adv|null  */
    protected $adv = null;

    public function __construct()
    {
        $this->adv = new Adv();
    }

    /**
     * Реализация правила
     *
     * @param $advId
     * @return mixed
     */
    abstract public function runCheckById($advId);

    /**
     * Чтение файла
     *
     * @param $file
     * @return \Generator
     * @throws \Exception
     */
    protected function readFile($file) {
        $handle = fopen($file, 'rb');
        if ($handle === false) {
            throw new \Exception(" Не удалось открыть файл '$file' с номерами телефонов. ", 201);
        }
        while (feof($handle) === false) {
            yield fgets($handle);
        }
        fclose($handle);
    }



}