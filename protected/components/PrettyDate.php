<?php

class PrettyDate extends CApplicationComponent
{
    private $_model=null;


    /*public function setModel($id)
    {
        $this->_model=Region::model()->findByPk($id);
    }

    public function getModel()
    {
        if (!$this->_model)
        {
            if (isset($_GET['region']))
                $this->_model=Region::model()->findByAttributes(array('url_name'=> $_GET['region']));
            else
                $this->_model=Region::model()->find();
        }
        return $this->_model;
    }*/

    public function plural($n, &$plurals) {
        $plural =
            ($n % 10 == 1 && $n % 100 != 11 ? 0 :
                ($n % 10 >= 2 && $n % 10 <= 4 &&
                    ($n % 100 < 10 or $n % 100 >= 20) ? 1 : 2));
        return $plurals[$plural];
    }

    /*
     * Функция вывода строки относительно даты
     * */
    public function relativeTime($dt, $precision = 2) {

        if($dt<=0)
            return null;

        $times = array(
            365*24*60*60    =>  array("год", "года", "лет"),
            30*24*60*60     =>  array("месяц", "месяца", "месяцев"),
            7*24*60*60      =>  array("неделя", "недели", "недель"),
            24*60*60        =>  array("день", "дня", "дней"),
            60*60           =>  array("час", "часа", "часов"),
            60              =>  array("мин", "мин", "мин"),
            1               =>  array("сек", "сек", "сек"),
        );

        $passed = time() - $dt;

        // условие можно изменить, я думаю
        if($passed < 5) {
            $output='менее 5 секунд назад';
        } else {
            $output = array();
            $exit = 0;
            foreach($times as $period => $name) {

                if($exit >= $precision || ($exit > 0 && $period < 60)) break;
                $result = floor($passed / $period);

                if ($result > 0) {
                    $output[] = $result . ' ' . self::plural($result, $name);
                    $passed -= $result * $period;
                    $exit++;
                } else if ($exit > 0) $exit++;
            }
            $output = implode(' и ', $output).' назад';
        }
        return $output;
    }
}