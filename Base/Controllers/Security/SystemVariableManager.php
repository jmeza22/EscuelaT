<?php
ob_start();
class SystemVariableManager {
    private $index_periodo = 'id_periodo';
    private $index_corte = 'id_corte';
    private $index_numcorte = 'numero_corte';
    
    function __construct() {
        
    }
    function setIdPeriodoAnualForm($periodo) {
        if ($periodo != null) {
            $_SESSION[$this->index_periodo] = $periodo;
        }
    }
    function setIdCortePeriodoForm($corte) {
        if ($corte != null) {
            $_SESSION[$this->index_corte] = $corte;
        }
    }
    
    function setNumCortePeriodoForm($corte) {
        if ($corte != null) {
            $_SESSION[$this->index_numcorte] = $corte;
        }
    }
    
    function getIdPeriodoAnual() {
        if (isset($_SESSION[$this->index_periodo])) {
            return $_SESSION[$this->index_periodo];
        }
        return null;
    }
    
    function getIdCortePeriodo() {
        if (isset($_SESSION[$this->index_corte])) {
            return $_SESSION[$this->index_corte];
        }
        return null;
    }
    
    function getNumCortePeriodo() {
        if (isset($_SESSION[$this->index_numcorte])) {
            return $_SESSION[$this->index_numcorte];
        }
        return null;
    }
    
}
