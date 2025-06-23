<?php

class registros {
    public $id;
    public $funcionarios_id;
    public $hora_entrada;
    public $hora_saida;
    public $total_horas_dia;

    public $hora_inicio;
    public $evento;
    public $evento_id;

    public $nome;
    public $data;

    public $usuario;
    public $senha;

    public function set($prop, $value) {
        $this->$prop = $value;
    }

    public function cadastrar_evento(){
        $objeto = new registrosDAO();
        $objeto->set("evento", $this->evento);
        $objeto->set("data", $this->data);
        $objeto->set("hora_inicio", $this->hora_inicio);
        return $objeto->cadastrar_evento();
    }

    public function alterar_presenca_rg() {
        $objeto = new registrosDAO();
        $objeto->set("funcionarios_id", $this->funcionarios_id);
        $objeto->set("hora_entrada", $this->hora_entrada);
        $objeto->set("data", $this->data);
        return $objeto->alterar_presenca_rg();  
    }

    public function alterar_horas_SEDE() {
        $objeto = new registrosDAO();
        $objeto->set("funcionarios_id", $this->funcionarios_id);
        $objeto->set("data", $this->data);
        $objeto->set("hora_entrada", $this->hora_entrada);  
        $objeto->set("hora_saida", $this->hora_saida);        
        return $objeto->alterar_horas_SEDE();
    }

    public function funcionarios_entrada() {
        $objeto = new registrosDAO();
        $objeto->set("funcionarios_id", $this->funcionarios_id);
        $objeto->set("hora_entrada", $this->hora_entrada);
        return $objeto->funcionarios_entrada();  
    }

    public function funcionarios_presenca_rg() {
        $objeto = new registrosDAO();
        $objeto->set("funcionarios_id", $this->funcionarios_id);
        $objeto->set("hora_entrada", $this->hora_entrada);
        return $objeto->funcionarios_presenca_rg();  
    }

    public function funcionarios_presenca_evento() {
        $objeto = new registrosDAO();
        $objeto->set("evento_id", $this->evento_id);
        $objeto->set("funcionarios_id", $this->funcionarios_id);
        $objeto->set("data", $this->data);
        $objeto->set("hora_entrada", $this->hora_entrada);
        return $objeto->funcionarios_presenca_evento();  
    }

    public function funcionarios_saida() {
        $objeto = new registrosDAO();
        $objeto->set("funcionarios_id", $this->funcionarios_id);
        $objeto->set("hora_saida", $this->hora_saida);
        return $objeto->funcionarios_saida();  
    }
}

?>