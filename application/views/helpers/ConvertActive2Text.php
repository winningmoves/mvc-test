<?php
class Zend_View_Helper_ConvertActive2Text extends Zend_View_Helper_Abstract
{
    public function convertActive2Text($int){
        if($int == 1){
            return "yes";
        }else if($int == 2){
            return "awaiting";
        }else if($int == 0){
            return "no";
        }
    }
}
?>
