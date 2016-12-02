<?php

include_once "../app/bootstrap.php";

$config = [
    'count_states' => 4, // Количество состояний
    'step' => 2, // Шаг изменения состояния после события
    'names_states' => [ // Названия состояний от плохого к хорошему
        'Настроение: не попадись на глаза',
        'Плохое настроение',
        'Нормальное настроение',
        'Хорошее настроение',
    ]
];

$terminator = new Controller\Terminator($config);

print_r($terminator->exec(4,[1,0,0,1,0,1,1,1,1,1,0,0,1,0,0,0,0,0]));

echo "<br>";
echo "Количество выговоров: ".$terminator->getHRStatistic()."<br>";
echo "Количество похвал: ".$terminator->getManagerStatistic()."<br>";

echo "<br>";
echo "<h4>Лог состояний Тимлида</h4>";
echo str_replace("\n","<br>",print_r($terminator->getLogOfStates(),1));