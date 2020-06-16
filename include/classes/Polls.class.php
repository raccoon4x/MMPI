<?php

class Polls {

    private $mmpi = '';
    private $db;
    private $test;

    /**
     * @var bool
     */
    private $debug;

    /**
     * Polls constructor.
     * @param $test - на сколько вопросов тест
     * @param bool $debug
     */
    public function __construct($test, $debug=false)
    {
        $this->test= $test;
        $this->db = DataBase::getDB();
        $this->mmpi = mmpi::getMMPI();
        $this->debug = $debug;
    }

    private function getQuestions()
    {
        $query = "SELECT * FROM `questions`";
        $table = $this->db->select($query);
        return $table;
    }

    public function showPollTitle()
    {
        echo 'Опросник MMPI на '.$this->test.' вопросов';
    }

    private function randChecked(){
        if(rand(0,1) == 1){
            $checked1 = 'checked';
            $checked2 = '';
        }else{
            $checked1 = '';
            $checked2 = 'checked';
        }
        return [$checked1, $checked2];
    }

    /**
     *
     */
    public function showTableResults()
    {
        $results = $this->mmpi->getAnswers();
        echo '<table class="table">
<thead>
<tr>
  <th scope="col">#</th>
  <th scope="col">True</th>
  <th scope="col">False</th>
</tr>
</thead>
<tbody>';
        foreach ($results as $item => $value) {
            echo '
<tr>
<th scope="row">' . $item . '</th>';
            if ($value === 'true') {
                echo '<td>1</td>';
            } else {
                echo '<td>0</td>';
            }
            if ($value === 'false') {
                echo '<td>1</td>';
            } else {
                echo '<td>0</td>';
            }
            echo '</tr>';
        }
        echo '  </tbody>
</table>';
    }

    /**
     * Выводит табличку шкал
     */
    public function showTableScales(){
        $results = $this->mmpi->getScales();
        echo '<table class="table table-bordered">
        <thead>
        <tr><th class="text-center" colspan="2">Баллы</th><th class="text-center align-middle" rowspan="2">Шкалы</th></tr>
        <tr><th class="text-center">T-баллы</th><th class="text-center">Сырые</th></tr></thead>';
        foreach ($results as $item => $value){
            echo '<tr>';
            echo '<td>'.$value['t'].'</td><td>'.$value['raw'].'</td>';
            echo '<td>'.$value['name'].'</td>';
            echo '</tr>';
        }
        echo '</table>';
    }

    public function showContact()
    {
        echo '<div class="row">
              <div class="col-md-6 mb-3">
                <label for="firstName">Имя</label>
                <input type="text" class="form-control" id="firstName" name="firstName" placeholder="" value="" required>
                <div class="invalid-feedback">
                  Укажите, пожалуйста, имя.
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label for="lastName">Фамилия</label>
                <input type="text" class="form-control" id="lastName" name="lastName" placeholder="" value="" required>
                <div class="invalid-feedback">
                  Укажите, пожалуйста, фамилию.
                </div>
              </div>
            </div>
            <div class="mb-3">
            <label>Ваш пол</label>
            <div class="custom-control custom-radio">
                <input id="sexm" name="sex" value="male" type="radio" class="custom-control-input" required>
                <label class="custom-control-label" for="sexm">Мужской</label>
            </div>
            <div class="custom-control custom-radio">
                <input id="sexf" name="sex" value="female" type="radio" class="custom-control-input" required>
                <label class="custom-control-label" for="sexf">Женский</label>
            </div>
        </div>';
    }

    public function showQuestions()
    {
        $table = $this->getQuestions();
        foreach ($table as $item=>$value){
            if($this->debug===true){
                list($checked1, $checked2) = $this->randChecked();
            }else{
                $checked1 = ''; $checked2 = '';
            }
            echo '        <div class="mb-3">
            <label>'.$value['id'].'. '.$value['text'].'</label>
            <div class="custom-control custom-radio">
                <input id="q'.$value['id'].'t" name="'.$value['id'].'" value="true" type="radio" class="custom-control-input" required '.$checked1.'>
                <label class="custom-control-label" for="q'.$value['id'].'t">Верно</label>
            </div>
            <div class="custom-control custom-radio">
                <input id="q'.$value['id'].'f" name="'.$value['id'].'" value="false" type="radio" class="custom-control-input" required '.$checked2.'>
                <label class="custom-control-label" for="q'.$value['id'].'f">Неверно</label>
            </div>
        </div>';
        }
    }

    public function showDataset1()
    {
        $array = [];
        $results = $this->mmpi->getScales();
        for ($i=0; $i<3; $i++){
            $array[] = $results[$i]['t'];
        }
        echo implode(',', $array);
    }

    public function showDataset2()
    {
        $array = ['','',''];
        $results = $this->mmpi->getScales();
        for ($i=3; $i<13; $i++){
            $array[] = $results[$i]['t'];
        }
        echo implode(',', $array);
    }

    public function showSex()
    {
        $sex = $this->mmpi->getSex();
        echo ($sex==='male') ? 'мужской' : 'женский';
    }

    public function showName()
    {
        echo $this->mmpi->getName();
    }
}