<?php

class registros {
    public $id;
    public $efetivos_id;
    public $trainees_id;
    public $hora_entrada;
    public $hora_saida;
    public $total_horas_dia;

    public function set($prop, $value) {
        $this->$prop = $value;
    }

    public function efetivos_entrada() {
        $objeto = new registrosDAO();
        $objeto->set("efetivos_id", $this->efetivos_id);
        $objeto->set("hora_entrada", $this->hora_entrada);
        return $objeto->efetivos_entrada();  
    }

    public function trainees_entrada() {
        $objeto = new registrosDAO();
        $objeto->set("trainees_id", $this->trainees_id);
        $objeto->set("hora_entrada", $this->hora_entrada);
        return $objeto->trainees_entrada();  
    }

    public function efetivos_saida() {
        $objeto = new registrosDAO();
        $objeto->set("efetivos_id", $this->efetivos_id);
        $objeto->set("hora_saida", $this->hora_saida);
        return $objeto->efetivos_saida();  
    }

    public function trainees_saida() {
        $objeto = new registrosDAO();
        $objeto->set("trainees_id", $this->trainees_id);
        $objeto->set("hora_saida", $this->hora_saida);
        return $objeto->trainees_saida();  
    }
}

?>