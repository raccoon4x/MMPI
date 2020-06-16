<?php

class mmpi extends mmpi_const {

    private static $mmpi = null;

    /**
     * Необработанные входные данные
     * @var
     */
    private $raw;

    /**
     * Ответы на вопросы
     *
     * @var array
     */
    private $answers = [];

    /**
     * Пол
     *
     * @var
     */
    private $sex;

    private $name;

    /**
     * Шкалы
     *
     * @var int
     */
    private $scaleL = 0;
    private $scaleF = 0;
    private $scaleK = 0;
    private $scaleHS = 0;
    private $scaleD = 0;
    private $scaleHy = 0;
    private $scalePd = 0;
    private $scaleMM = 0;
    private $scaleMf = 0;
    private $scalePa = 0;
    private $scalePt = 0;
    private $scaleSc = 0;
    private $scaleMa = 0;
    private $scaleSi = 0;

    /**
     * T-баллы
     *
     * @var int
     */
    private $tPointL = 0;
    private $tPointF = 0;
    private $tPointK = 0;
    private $tPointHS = 0;
    private $tPointD = 0;
    private $tPointHy = 0;
    private $tPointPd = 0;
    private $tPointMM = 0;
    private $tPointMf = 0;
    private $tPointPa = 0;
    private $tPointPt = 0;
    private $tPointSc = 0;
    private $tPointMa = 0;
    private $tPointSi = 0;


    /**
     * mmpi337 constructor.
     * Получает на вход данные теста
     */
    public function __construct()
    {
        $this->raw = $_POST;
    }

    /**
     * Получение экземпляра класса.
     * Если он уже существует, то возвращается.
     * Если его не было, то создаётся и возвращается (паттерн Singleton)
     */
    public static function getMMPI() {
        if (self::$mmpi == null) self::$mmpi = new mmpi();
        return self::$mmpi;
    }

    /**
     * @throws Exception
     */
    public function validate()
    {
        if(!isset($this->raw['firstName'])){
            throw new Exception('Не задано имя');
        }
        if(!isset($this->raw['lastName'])){
            throw new Exception('Не задана фамилия');
        }
        $this->name = $this->raw['firstName'] . ' ' . $this->raw['lastName'];
        if(!isset($this->raw['sex'])){
            throw new Exception('Не указан пол');
        }
        $this->sex = $this->raw['sex'];
        if($this->sex!=='male' && $this->sex!=='female'){
            throw new Exception('Неверно указан пол');
        }
        foreach ($this->raw as $item => $value) {
            if (is_int($item)) {
                $this->answers[$item] = $value;
            }
        }
        if(count($this->answers)!==377){
            throw new Exception('Получены ответы не на все вопросы');
        }
        $this->toCount();
    }

    /**
     * Подсчёт баллов Шкал
     *
     * @param $questions
     * @param $flag
     * @return int
     */
    private function countPoints($questions, $flag)
    {
        $count = 0;
        foreach ($questions as $question){
            if($this->answers[$question]===$flag){
                $count++;
            }
        }
        return $count;
    }

    public function getAnswers()
    {
        return $this->answers;
    }

    private function formulaL()
    {
        $questions = [50,58,65,90,120,150,163,180,210,231,240,270,300,330,360];
        $flag = 'false';
        $this->scaleL = $this->countPoints($questions, $flag);
    }

    private function formulaF()
    {
        $questions = [12,25,26,27,28,54,55,56,72,83,85,86,102,105,113,115,116,117,132,143,145,146,147,173,175,177,203,206,207,236,237,265,266,267,294,295,297,324,325,326,327,334,353,354,355,356,357];
        $flag = 'true';
        $this->scaleF = $this->countPoints($questions, $flag);
        $questions = [24,57,58,84,37,176,193,205,233,235,261,263,293,296,323,364];
        $flag = 'false';
        $this->scaleF+= $this->countPoints($questions, $flag);
    }

    private function formulaK()
    {
        $questions = [340];
        $flag = 'true';
        $this->scaleK = $this->countPoints($questions, $flag);
        $questions = [8,13,38,43,73,94,98,103,124,128,133,154,158,163,188,193,217,218,223,253,277,280,282,283,310,312,313,342,372];
        $flag = 'false';
        $this->scaleK+= $this->countPoints($questions, $flag);
    }

    private function formulaHS()
    {
        $questions = [15,17,45,46,77,105,107,135,137,165,197,225,255,285,286,308,314,315,316,344,345,346,375,376];
        $flag = 'true';
        $this->scaleHS = $this->countPoints($questions, $flag);
        $questions = [16,47,75,131,167,195,254,284,374];
        $flag = 'false';
        $this->scaleHS+= $this->countPoints($questions, $flag);
        $this->scaleHS+= (0.5 * $this->scaleK);
    }

    private function formulaD()
    {
        $questions = [9,19,48,49,79,98,105,108,109,139,165,168,169,225,228,229,253,257,258,259,315,337,36];
        $flag = 'true';
        $this->scaleD = $this->countPoints($questions, $flag);
        $questions = [18,20,41,43,50,75,78,124,131,137,138,161,163,167,193,198,199,223,227,244,254,277,284,287,288,289,317,318,319,338,347,348,349,368,370,377];
        $flag = 'false';
        $this->scaleD+= $this->countPoints($questions, $flag);
    }

    private function formulaHy()
    {
        $questions = [14,15,45,46,76,105,106,134,135,136,165,166,194,225,255,285,314,315,337,344,345,373,375];
        $flag = 'true';
        $this->scaleHy = $this->countPoints($questions, $flag);
        $questions = [8,11,16,38,41,43,44,71,73,74,75,101,103,104,124,133,155,163,164,184,187,195,196,214,218,224,226,248,254,256,278,280,284,343,370,374];
        $flag = 'false';
        $this->scaleHy+= $this->countPoints($questions, $flag);
    }

    private function formulaPd()
    {
        $questions = [40,42,70,72,100,102,132,162,190,191,192,221,222,247,250,251,252,281,311,337,341,367,369,371];
        $flag = 'true';
        $this->scalePd = $this->countPoints($questions, $flag);
        $questions = [8,10,11,12,38,41,68,71,94,101,130,131,160,161,187,217,220,277,280,307,310,340,370];
        $flag = 'false';
        $this->scalePd+= $this->countPoints($questions, $flag);
        $this->scalePd+= (0.4 * $this->scaleK);
    }

    private function formulaMM()
    {
        $questions = [1,3,5,32,62,64,94,122,151,152,154,181,213,242,243,273,274,301,302,303,304,331,332,334,361,362];
        $flag = 'true';
        $this->scaleMM = $this->countPoints($questions, $flag);
        $questions = [2,4,31,33,34,35,61,63,65,91,92,93,121,123,124,153,182,183,184,211,212,214,241,244,271,272,333,363,364];
        $flag = 'false';
        $this->scaleMM+= $this->countPoints($questions, $flag);
    }

    private function formulaMf()
    {
        $questions = [1,3,5,32,62,64,93,94,122,151,152,154,181,213,242,243,273,274,301,302,303,304,331,332,334,361,362];
        $flag = 'true';
        $this->scaleMf = $this->countPoints($questions, $flag);
        $questions = [2,4,31,33,34,35,61,63,65,91,92,121,123,124,153,182,183,184,211,212,214,241,244,271,272,333,363,364];
        $flag = 'false';
        $this->scaleMf+= $this->countPoints($questions, $flag);
    }

    private function formulaPa()
    {
        $questions = [5,12,28,42,51,88,113,114,143,144,162,171,178,192,203,208,222,231,252,259,262,267,291,297,308,327,339,357,371];
        $flag = 'true';
        $this->scalePa = $this->countPoints($questions, $flag);
        $questions = [34,118,148,188,196,218,226,238,268,298,370];
        $flag = 'false';
        $this->scalePa+= $this->countPoints($questions, $flag);
    }

    private function formulaPt()
    {
        $questions = [19,21,39,49,51,69,76,79,80,81,99,106,109,110,111,129,136,140,141,154,159,170,171,189,191,201,219,221,230,231,251,253,258,260,290,291,315,320,337,350,367];
        $flag = 'true';
        $this->scalePt = $this->countPoints($questions, $flag);
        $questions = [41,195,200,261,288,318,348];
        $flag = 'false';
        $this->scalePt+= $this->countPoints($questions, $flag);
        $this->scalePt = $this->scalePt + $this->scaleK;
    }

    private function formulaSc()
    {
        $questions = [12,21,22,23,42,51,52,53,54,79,81,82,83,106,109,111,112,113,114,136,139,141,142,143,144,166,169,171,172,173,174,197,199,201,202,203,204,229,231,232,234,247,258,262,264,274,279,281,304,308,309,311,321,337,341,345,350,351,353,371,375,352];
        $flag = 'true';
        $this->scaleSc = $this->countPoints($questions, $flag);
        $questions = [24,41,84,233,249,263,283,292,293,322,323,348];
        $flag = 'false';
        $this->scaleSc+= $this->countPoints($questions, $flag);
        $this->scaleSc = $this->scaleSc + $this->scaleK;
    }

    private function formulaMa()
    {
        $questions = [20,21,29,51,59,60,94,106,108,119,149,174,179,196,204,209,222,234,239,256,262,264,269,276,281,289,298,299,319,328,339,349,353,359];
        $flag = 'true';
        $this->scaleMa = $this->countPoints($questions, $flag);
        $questions = [8,35,38,71,80,89,90,120,217,249,313,358];
        $flag = 'false';
        $this->scaleMa+= $this->countPoints($questions, $flag);
        $this->scaleMa+= (0.2 * $this->scaleK);
    }

    private function formulaSi()
    {
        $questions = [6,7,8,9,34,37,38,39,69,95,97,98,99,126,127,128,129,155,158,159,187,188,217,218,219,243,245,248,278,279,305,307,308,309,337,338,365,366,367];
        $flag = 'true';
        $this->scaleSi = $this->countPoints($questions, $flag);
        $questions = [4,36,66,67,68,96,125,156,157,185,186,189,215,216,246,247,249,273,275,276,277,303,306,333,335,336,339,363,368];
        $flag = 'false';
        $this->scaleSi+= $this->countPoints($questions, $flag);
    }

    private function toCount()
    {
        $this->formulaL();
        $this->formulaF();
        $this->formulaK();
        $this->formulaHS();
        $this->formulaD();
        $this->formulaHy();
        $this->formulaPd();
        $this->formulaMM();
        $this->formulaMf();
        $this->formulaPa();
        $this->formulaPt();
        $this->formulaSc();
        $this->formulaMa();
        $this->formulaSi();
        $this->toCountTPoints();
    }

    private function toCountTPoints()
    {
        if($this->sex==='male'){
            $this->tPointL = 50 + ((10 * ($this->scaleL - self::MALE_M['L'])) / self::MALE_Q['L']);
            $this->tPointF = 50 + ((10 * ($this->scaleF - self::MALE_M['F'])) / self::MALE_Q['F']);
            $this->tPointK = 50 + ((10 * ($this->scaleK - self::MALE_M['K'])) / self::MALE_Q['K']);
            $this->tPointHS = 50 + ((10 * ($this->scaleHS - self::MALE_M['HS'])) / self::MALE_Q['HS']);
            $this->tPointD = 50 + ((10 * ($this->scaleD - self::MALE_M['D'])) / self::MALE_Q['D']);
            $this->tPointHy = 50 + ((10 * ($this->scaleHy - self::MALE_M['Hy'])) / self::MALE_Q['Hy']);
            $this->tPointPd = 50 + ((10 * ($this->scalePd - self::MALE_M['Pd'])) / self::MALE_Q['Pd']);
            $this->tPointMM = 50 + ((10 * ($this->scaleMM - self::MALE_M['MM'])) / self::MALE_Q['MM']);
            $this->tPointPa = 50 + ((10 * ($this->scalePa - self::MALE_M['Pa'])) / self::MALE_Q['Pa']);
            $this->tPointPt = 50 + ((10 * ($this->scalePt - self::MALE_M['Pt'])) / self::MALE_Q['Pt']);
            $this->tPointSc = 50 + ((10 * ($this->scaleSc - self::MALE_M['Sc'])) / self::MALE_Q['Sc']);
            $this->tPointMa = 50 + ((10 * ($this->scaleMa - self::MALE_M['Ma'])) / self::MALE_Q['Ma']);
            $this->tPointSi = 50 + ((10 * ($this->scaleSi - self::MALE_M['Si'])) / self::MALE_Q['Si']);
        }else{
            $this->tPointL = 50 + ((10 * ($this->scaleL - self::FEMALE_M['L'])) / self::FEMALE_Q['L']);
            $this->tPointF = 50 + ((10 * ($this->scaleF - self::FEMALE_M['F'])) / self::FEMALE_Q['F']);
            $this->tPointK = 50 + ((10 * ($this->scaleK - self::FEMALE_M['K'])) / self::FEMALE_Q['K']);
            $this->tPointHS = 50 + ((10 * ($this->scaleHS - self::FEMALE_M['HS']))/ self::FEMALE_Q['HS']);
            $this->tPointD = 50 + ((10 * ($this->scaleD - self::FEMALE_M['D'])) / self::FEMALE_Q['D']);
            $this->tPointHy = 50 + ((10 * ($this->scaleHy - self::FEMALE_M['Hy'])) / self::FEMALE_Q['Hy']);
            $this->tPointPd = 50 + ((10 * ($this->scalePd - self::FEMALE_M['Pd'])) / self::FEMALE_Q['Pd']);
            $this->tPointMf = 50 + ((10 * ($this->scaleMf - self::FEMALE_M['Mf'])) / self::FEMALE_Q['Mf']);
            $this->tPointPa = 50 + ((10 * ($this->scalePa - self::FEMALE_M['Pa'])) / self::FEMALE_Q['Pa']);
            $this->tPointPt = 50 + ((10 * ($this->scalePt - self::FEMALE_M['Pt'])) / self::FEMALE_Q['Pt']);
            $this->tPointSc = 50 + ((10 * ($this->scaleSc - self::FEMALE_M['Sc'])) / self::FEMALE_Q['Sc']);
            $this->tPointMa = 50 + ((10 * ($this->scaleMa - self::FEMALE_M['Ma'])) / self::FEMALE_Q['Ma']);
            $this->tPointSi = 50 + ((10 * ($this->scaleSi - self::FEMALE_M['Si'])) / self::FEMALE_Q['Si']);
        }
    }

    public function getScales()
    {
        $array[] = ['name' => 'Шкала лжи (L)', 'raw' => $this->scaleL, 't' => $this->tPointL];
        $array[] = ['name' => 'Шкала достоверности (F)', 'raw' => $this->scaleF, 't' => $this->tPointF];
        $array[] = ['name' => 'Шкала коррекции (К)', 'raw' => $this->scaleK, 't' => $this->tPointK];
        $array[] = ['name' => 'Шкала ипохондрии (Hs)', 'raw' => $this->scaleHS, 't' => $this->tPointHS];
        $array[] = ['name' => 'Шкала депрессии (D)', 'raw' => $this->scaleD, 't' => $this->tPointD];
        $array[] = ['name' => 'Шкала истерии (Ну)', 'raw' => $this->scaleHy, 't' => $this->tPointHy];
        $array[] = ['name' => 'Шкала психопатии (Pd)', 'raw' => $this->scalePd, 't' => $this->tPointPd];
        if($this->sex==='male'){
            $array[] = ['name' => 'Шкала мужественности (Mm)', 'raw' => $this->scaleMM, 't' => $this->tPointMM];
        }else{
            $array[] = ['name' => 'Шкала женственности (Mf)', 'raw' => $this->scaleMf, 't' => $this->tPointMf];
        }
        $array[] = ['name' => 'Шкала паранойяльности (Ра)', 'raw' => $this->scalePa, 't' => $this->tPointPa];
        $array[] = ['name' => 'Шкала психастении (Pt)', 'raw' => $this->scalePt, 't' => $this->tPointPt];
        $array[] = ['name' => 'Шкала шизоидности (Sс)', 'raw' => $this->scaleSc, 't' => $this->tPointSc];
        $array[] = ['name' => 'Шкала гипомании (Ма)', 'raw' => $this->scaleMa, 't' => $this->tPointMa];
        $array[] = ['name' => 'Шкала социальной интроверсии (Si)', 'raw' => $this->scaleSi, 't' => $this->tPointSi];
        return $array;
    }

    public function getSex()
    {
        return $this->sex;
    }

    public function getName()
    {
        return $this->name;
    }
}