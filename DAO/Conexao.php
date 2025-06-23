<?php

class Conexao{    
    private $host = ''; // Host (Servidor) que executa o banco de dados
    private $user = ''; // Usuário que se conecta ao servidor de banco de dados
    private $pass = ''; // Senha do usuário para conexão ao banco de dados
    private $db = 'registro'; // Nome do banco de dados a ser utilizado
    private $sql; // String da consulta SQL a ser executada
    
    function Conexao(){
    }
    
    function set($prop, $value) {
        $this->$prop = $value;
    }

    function getConnection() {
    $con = new mysqli($this->host, $this->user, $this->pass, $this->db);
    $con->set_charset("utf8");

        if ($con->connect_errno) {
            die("Falha na conexão: " . $con->connect_error);
        }

        return $con;
    }

    function query() {
        $con = new mysqli($this->host, $this->user, $this->pass, $this->db);
        $con->set_charset("utf8");

        if ($con->connect_errno) {
            echo "Falha ao conectar: (" . $con->connect_errno . ") " . $con->connect_error;
        }
        
        $qry = mysqli_query($con, $this->sql) or 
                die($this->erro(mysqli_error($con)));
        
        mysqli_close($con);
        return $qry;
        
    }

    function erro($erro) {
        echo $erro;
    }
}
?>