<?php

/**
 * Represents a instruction.
 * 
 * Instruction is OPCODE and 
 */
class Instruction {

    var $opCode;
    var $operands = array();
    
    function __construct(Line $line){
       $this->opCode = $line->elements[0];
       for ($i=1; $i < $line->cnt(); $i++) { 
           array_push($this->operands, $line->elements[$i]);
       }
       $this->opCodeToLower();
    }

    function opCodeToLower() {
        $this->opCode = strtolower($this->opCode);
    }

    function operandsCnt() {
        return count($this->operands);
    }

    function checkOpCnt($count) {
        return ($this->operandsCnt() == $count) ? true : false;
    }

    function checkVar(int $opnum) {
        if ( preg_match("/^(LF|GF|TF)@[A-Za-z_\-$&%*!?][0-9A-Za-z_\-$&%*!?]*$/", $this->operands[$opnum]) == 1 ) {
            return true;
        } else {
            return false;
        }
    }

    function checkConst (int $opnum) {
        if ( preg_match("/^int@-{0,1}[0-9]+$/u",$this->operands[$opnum]) == 1) {
            return true;
        } elseif ( preg_match("/^bool@(true|false)$/u",$this->operands[$opnum]) == 1) {
            return true;
        } elseif ( preg_match("/^string@([\p{L}!\"\$-\[\]-~]|\\\\\d{3})*$/um",$this->operands[$opnum]) == 1) {
            return true;
        } elseif ( preg_match("/^nil@nil$/u", $this->operands[$opnum]) == 1) {
            return true;
        } else {
            return false;
        }
    }

    function checkSymb(int $opnum){
        if ( $this->checkVar($opnum) == true ) {
            return true;
        } elseif($this->checkConst($opnum)) {
            return true;
        } else {
            return false;
        }
    }

    function checkLabel(int $opnum = 0) {
        if (preg_match("/[A-Za-z_\-$&%*!?][0-9A-Za-z_\-$&%*!?]*/", $this->operands[$opnum]) == 1) {
            return true;
        } else {
            return false;
        }
    }

    function checkType(int $opnum) {
        if (preg_match("/^(int|string|bool)$/",$this->operands[$opnum])) {
            return true;
        } else {
            return false;
        }
    }

    function operandType(int $opnum) {

        if ($this->checkConst($opnum)){

            if (preg_match("/^string@.*/",$this->operands[$opnum])) {
                return 'string';
            } elseif (preg_match("/^int@.*/",$this->operands[$opnum])) {
                return 'int';
            } elseif (preg_match("/^bool@.*/",$this->operands[$opnum])) {
                return 'bool';
            } elseif (preg_match("/^nil@.*/",$this->operands[$opnum])) {
                return 'nil';
            }

        } elseif ($this->checkVar($opnum)) {
            return 'var';
        } elseif ($this->checkType($opnum)) {
            return 'type';
        } elseif ($this->checkLabel($opnum)) {
            return 'label';
        }
    }
}

?>