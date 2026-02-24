<?php

class funcionariosDAO {
    public $id;
    public $nome;
    public $situacao;

    public function buscar_rg() {
        $objeto = new Conexao();
        $SQL = "SELECT * FROM funcionarios_rg;";
        $objeto->set("sql", $SQL);
        $resultados = $objeto->query()->fetch_all(MYSQLI_ASSOC);
        return $resultados;
    }

    public function buscar_evento(){
        $objeto = new Conexao();
        $SQL = "SELECT id, evento, data, hora_inicio FROM evento;";
        $objeto->set("sql", $SQL);
        $resultados = $objeto->query()->fetch_all(MYSQLI_ASSOC);
        return $resultados;
    }

    public function busca_por_situacao() {
        $objeto = new Conexao();
        $SQL = "SELECT id, nome FROM funcionarios 
                WHERE situacao = '$this->situacao';";
        $objeto->set("sql", $SQL);
        $resultados = $objeto->query()->fetch_all(MYSQLI_ASSOC);
        return $resultados;
    }

    public function buscar_efetivos() {
        $objeto = new Conexao();
        $SQL = "SELECT id, nome, situacao FROM funcionarios
                WHERE situacao = 'efetivo'
                ORDER BY situacao ASC, nome ASC";
        $objeto->set("sql", $SQL);
        $resultados = $objeto->query()->fetch_all(MYSQLI_ASSOC);
        return $resultados;
    }

    public function buscar_trainees() {
        $objeto = new Conexao();
        $SQL = "SELECT id, nome, situacao FROM funcionarios
                WHERE situacao = 'trainee'
                ORDER BY situacao ASC, nome ASC";
        $objeto->set("sql", $SQL);
        $resultados = $objeto->query()->fetch_all(MYSQLI_ASSOC);
        return $resultados;
    }

    public function buscar_eventos() {
        $objeto = new Conexao();
        $SQL = "SELECT f.id, f.nome, f.situacao, evento.evento, evento.data, e.data, evento.hora_inicio, e.hora_entrada
                FROM funcionarios AS f
                JOIN funcionarios_evento as e
                    ON f.id = e.funcionarios_id
                JOIN evento
                    ON e.evento_id = evento.id
                ORDER BY f.situacao ASC, f.nome ASC;";
        $objeto->set("sql", $SQL);
        $resultados = $objeto->query()->fetch_all(MYSQLI_ASSOC);
        return $resultados;
    }

    public function funcionarios_buscar() {
        $objeto = new Conexao();
        $SQL = "SELECT id, nome, situacao FROM funcionarios
                ORDER BY situacao ASC, nome ASC";
        $objeto->set("sql", $SQL);
        $resultados = $objeto->query()->fetch_all(MYSQLI_ASSOC);
        return $resultados;
    }

    public function cadastrar() {
        $objeto = new Conexao();
        $SQL = "INSERT INTO funcionarios (nome, situacao)
                VALUES ('$this->nome', '$this->situacao');";
        $objeto->set("sql", $SQL);
        $objeto->query();
        return "Cadastrado com Sucesso";
    }

    public function alterar() {
        $objeto = new Conexao();
        $sql = "UPDATE funcionarios SET nome='$this->nome', situacao ='$this->situacao' 
                WHERE id ='$this->id';";
        $objeto->set("sql", $sql);
        $objeto->query($sql);
        return "Alterado com Sucesso";
    }

    public function deletar(){
        $objeto = new Conexao();

        $a = "DELETE FROM funcionarios_sede WHERE funcionarios_id = '$this->id';";
        $objeto->set("sql", $a);
        $objeto->query($a);

        $b = "DELETE FROM funcionarios_rg WHERE funcionarios_id = '$this->id';";
        $objeto->set("sql", $b);
        $objeto->query($b);

        $c = "DELETE FROM funcionarios_evento WHERE funcionarios_id = '$this->id';";
        $objeto->set("sql", $c);
        $objeto->query($c);

        $d = "DELETE FROM totais_semanais WHERE funcionarios_id = '$this->id';";
        $objeto->set("sql", $d);
        $objeto->query($d);

        $SQL = "DELETE FROM funcionarios WHERE id = '$this->id';";
        $objeto->set("sql", $SQL);
        $objeto->query($SQL);

        return "Excluido com Sucesso";
    }

    public function set($prop, $value) {
        $this->$prop = $value;
    }
}

?>