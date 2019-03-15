<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MyCrypt
 *
 * @author helpdesk
 */
class MyCrypt {
    
    private $pattern="92042202128";
    private $pattern2="abcd";
    private $son="2204.jjmm";
    
    function crypt($string){
        $result=  $this->pattern.sha1(md5($string.$this->son)).$this->pattern2;
        return $result;
    }
}
