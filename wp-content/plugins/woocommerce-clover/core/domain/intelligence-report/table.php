<?php

namespace Wooclover\Core\Domain\IntelligenceReport;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class Table extends Element {

	private $rows = array();
	private $columns = array();

	public function __construct () {
		;
	}

	public function getRows () {
		return $this->rows;
	}

	public function setRows ( $rows ) {
		$this->rows = $rows;
	}

	public function getColumns () {
		return $this->columns;
	}

	public function setColumns ( $columns ) {
		$this->columns = $columns;
	}

	public function addColumn ( $column ) {
		$this->columns[] = $column;
	}

	public function addRow ( $row ) {
		$this->rows[] = $row;
	}

	public function hasColumns () {
		return !\GFSeoMarketingAddOn\Core\Utils::isEmpty( $this->columns );
	}

	public function hasRows () {
		return !\GFSeoMarketingAddOn\Core\Utils::isEmpty( $this->rows );
	}

	public function getRowsCountInWords () {
		return $this->numberToWord(count($this->getRows()));
	}

	public function getColumnsCountInWords () {
		return $this->numberToWord(count($this->getColumns()));
	}

	public function toHtml () {


		return $this->processTemplate( "table", $this );
	}

	
	function singledigit($number){
    switch($number){
        case 0:$word = "zero";break;
        case 1:$word = "one";break;
        case 2:$word = "two";break;
        case 3:$word = "three";break;
        case 4:$word = "four";break;
        case 5:$word = "five";break;
        case 6:$word = "six";break;
        case 7:$word = "seven";break;
        case 8:$word = "eight";break;
        case 9:$word = "nine";break;
    }
    return $word;
}

function doubledigitnumber($number){
    if($number == 0){
        $word = "";
    }
    else{
        $word = "-".$this->singledigit($number);
    }       
    return $word;
}

function doubledigit($number){
    switch($number[0]){
        case 0:$word = $this->doubledigitnumber($number[1]);break;
        case 1:
            switch($number[1]){
                case 0:$word = "ten";break;
                case 1:$word = "eleven";break;
                case 2:$word = "twelve";break;
                case 3:$word = "thirteen";break;
                case 4:$word = "fourteen";break;
                case 5:$word = "fifteen";break;
                case 6:$word = "sixteen";break;
                case 7:$word = "seventeen";break;
                case 8:$word = "eighteen";break;
                case 9:$word = "ninteen";break;
            }break;
        case 2:$word = "twenty".$this->doubledigitnumber($number[1]);break;                
        case 3:$word = "thirty".$this->doubledigitnumber($number[1]);break;
        case 4:$word = "forty".$this->doubledigitnumber($number[1]);break;
        case 5:$word = "fifty".$this->doubledigitnumber($number[1]);break;
        case 6:$word = "sixty".$this->doubledigitnumber($number[1]);break;
        case 7:$word = "seventy".$this->doubledigitnumber($number[1]);break;
        case 8:$word = "eighty".$this->doubledigitnumber($number[1]);break;
        case 9:$word = "ninety".$this->doubledigitnumber($number[1]);break;

    }
    return $word;
}

function unitdigit($numberlen,$number){
    switch($numberlen){         
        case 3:case 6:case 9:case 12:$word = "hundred";break;
        case 4:case 5:$word = "thousand";break;
        case 7:case 8:$word = "million";break;
        case 10:case 11:$word = "billion";break;
    }
    return $word;
}

function numberToWord($number){

    $numberlength = strlen($number);
    if ($numberlength == 1) { 
        return $this->singledigit($number);
    }elseif ($numberlength == 2) {
        return $this->doubledigit($number);
    }
    else {

        $word = "";
        $wordin = "";
        switch ($numberlength ) {
        case 5:case 8:  case 11:
            if($number[0] >0){
                $unitdigit = $this->unitdigit($numberlength,$number[0]);
                $word = $this->doubledigit($number[0].$number[1]) ." ".$unitdigit." ";
                return $word." ".$this->numberToWord(substr($number,2));
            }
            else{
                return $word." ".$this->numberToWord(substr($number,1));
            }
        break;
        default:
            if($number[0] >0){
                $unitdigit = $this->unitdigit($numberlength,$number[0]);
                $word = $this->singledigit($number[0]) ." ".$unitdigit." ";
            }               
            return $word." ".$this->numberToWord(substr($number,1));
        }
    }
}
}