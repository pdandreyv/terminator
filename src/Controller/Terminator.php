<?php

namespace Controller;

/**
 * class Terminator
 */
class Terminator implements TerminatorInterface
{
    // Текущее состояние тимлида
    private $state = 1; 
    
    // Текущее состояние тимлида
    private $begin_state = 1; 
    
    // Массив изменений состояний тимлида
    private $array_states; 
    
    // Массив результатов работы программиста
    private $juniorBehavior;
    
    // Конфигурация приложения
    private $config = [
        // Количество состояний
        'count_states' => 4, 
        
        // Шаг изменения состояния после события
        'step' => 1, 
        
        // Названия состояний от плохого к хорошему
        'names_states' => [ 
            'Настроение: не попадись на глаза',
            'Плохое настроение',
            'Нормальное настроение',
            'Хорошее настроение',
        ],
        
        // Сообщение при похвале
        'best' => 'Похвала',
        
        // Сообщение при порицании
        'worst' => 'Порицание',
        
        // Сообщение при косяках программиста
        0 => 'Программист накосячил, настроение Тимлида ухудшилось',
        
        // Сообщение при успехе программиста
        1 => 'Программист успешно выполнил задание, настроение Тимлида улучшилось',
    ]; 
    
    public function __construct($config=[])
    {
        if(count($config)){
            if(isset($config['count_states']) && $config['count_states'] > 0){
                $this->config['count_states'] = $config['count_states'];
            }
            if(isset($config['step']) && $config['step'] < $this->config['count_states']){
                $this->config['step'] = $config['step'];
            }
            if( isset($config['names_states'])  
                && is_array($config['names_states'])
                && count($config['names_states']) == $this->config['count_states']
              )
            {
                $this->config['names_states'] = [];
                foreach($config['names_states'] as $state){
                    if(is_string($state)) {
                        $this->config['names_states'][] = $state;
                    } else {
                        $this->config['names_states'][] = 'Состояние доложно быть строкой';
                    }
                }
            }
        
            foreach($config as $key => $item){
                if( isset($this->config[$key]) 
                    && gettype($this->config[$key]) == gettype($item) 
                    && !in_array($key,['count_states','step','names_states'])
                  )
                {
                    $this->config[$key] == $item;
                }
            }
        }
        
    }

    /**
     * Функция выполнения основных действий с собитиями и состояниями
     * 
     * @param int   $state
     * @param array $juniorBehavior
     *
     * @return string
     */
    public function exec($state, array $juniorBehavior)
    {
        if(is_numeric($state) && $state > 0){
            if($state > $this->config['count_states']) {
                $this->state = $this->config['count_states'];
            } else {
                $this->state = $state;
            }
        }
        $this->begin_state = $this->state;
        $this->array_states[0] = $this->begin_state;
        if(count($juniorBehavior)) {
            $this->juniorBehavior = $juniorBehavior;
            foreach ($juniorBehavior as $event){
                if($event == 0){
                    $this->state -= $this->config['step'];
                    // Порицание
                    if ($this->state < 1) {
                        $this->state = 1;
                    }
                } else {
                    $this->state += $this->config['step'];
                    // Похвала
                    if($this->state > $this->config['count_states']){
                        $this->state = $this->config['count_states'];
                    }
                }
                $this->array_states[] = $this->state;
            }
        }

        $result = [
            'Текущее состояние:' => $this->config['names_states'][$this->state - 1]
        ];
        return $result;
    }

    /**
     * Returns the worst feedback amount
     *
     * @return int
     */
    public function getHRStatistic()
    {
        $count_worst = 0;
        $previos = false;
        foreach($this->array_states as $state){
            if($previos == $state && $state == 1){
                $count_worst++;
            }
            $previos = $state;
        }
        return $count_worst;
    }

    /**
     * Returns best feedback amount
     *
     * @return int
     */
    public function getManagerStatistic()
    {
        $count_best = 0;
        $previos = false;
        foreach($this->array_states as $state){
            if($previos == $state && $state == $this->config['count_states']){
                $count_best++;
            }
            $previos = $state;
        }
        return $count_best;
    }
    
    /**
     * Возвращает массив всех изменений состояний Тимлида в виде сообщений
     *
     * @return array
     */
    public function getLogOfStates()
    {
        $count_best = 0;
        $previos = $this->begin_state;
        $log[] = "Начальное состояние Тимлида: ".$this->config['names_states'][$this->begin_state - 1];
        foreach($this->array_states as $key => $state){
            if($key==0) continue;
            $key--;
            $log[] =  $this->config[$this->juniorBehavior[$key]].": ".$this->config['names_states'][$state - 1];
            if($previos == $state && $state == $this->config['count_states']){
                $log[] = $this->config['best'];
            }
            if($previos == $state && $state == 1){
                $log[] = $this->config['worst'];
            }
            $previos = $state;
        }
        return $log;
    }
}
