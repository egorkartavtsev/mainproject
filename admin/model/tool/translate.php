<?php

class ModelToolTranslate extends Model{
    private $letters = array(
        'А'  =>  'а', 'Б'  =>  'b', 'В'  =>  'v', 'Г'  =>  'g', 'Д'  =>  'd', 'Е'  =>  'e', 'Ё'  =>  'e', 'Ж'  =>  'g',
        'З'  =>  'z', 'И'  =>  'i', 'К'  =>  'k', 'Л'  =>  'l', 'М'  =>  'm', 'Н'  =>  'n', 'О'  =>  'o', 'П'  =>  'p',
        'Р'  =>  'r', 'С'  =>  's', 'Т'  =>  't', 'У'  =>  'u', 'Ф'  =>  'f', 'Х'  =>  'h', 'Ц'  =>  'c', 'Ч'  =>  'c',
        'Ш'  =>  's', 'Щ'  =>  's', 'Ь'  =>  '', 'Ы'  =>  'y', 'Ъ'  =>  '', 'Э'  =>  'e', 'Ю'  =>  'u', 'Я'  =>  'a',
        'а'  =>  'а', 'б'  =>  'b', 'в'  =>  'v', 'г'  =>  'g', 'д'  =>  'd', 'е'  =>  'e', 'ё'  =>  'e', 'ж'  =>  'g',
        'з'  =>  'z', 'и'  =>  'i', 'к'  =>  'k', 'л'  =>  'l', 'м'  =>  'm', 'н'  =>  'n', 'о'  =>  'o', 'п'  =>  'p',
        'р'  =>  'r', 'с'  =>  's', 'т'  =>  't', 'у'  =>  'u', 'ф'  =>  'f', 'х'  =>  'h', 'ц'  =>  'c', 'ч'  =>  'c',
        'ш'  =>  's', 'щ'  =>  's', 'ь'  =>  '', 'ы'  =>  'y', 'ъ'  =>  '', 'э'  =>  'e', 'ю'  =>  'u', 'я' =>  'a',
        ' '  =>  '_', 'й'  =>  'y', 'Й'  =>  'y'
    );
    
    private $bansign = array('!', '@', '#', '=', '$', '%', '^', '&', '*', '(', ')', '/', '\\', '+', '-', '.', ',', '~', '?', '№', '"', '`', '\'', '<', '>', ';', ':');
    
    public function validation($string){
        foreach($this->bansign as $sign){
            if(strripos($string, $sign)){return FALSE;}
        }
        return TRUE;
    }
    
    public function translate($string){
        $transStr = $string;
        foreach ($this->letters as $let => $trans) {
            $transStr = str_replace($let, $trans, $transStr);
            $transStr = str_replace($this->bansign, '', $transStr);
        }
        return $transStr;
    }
}

