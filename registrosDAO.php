<?php

date_default_timezone_set('America/Sao_Paulo');

class registrosDAO {
    public $id;
    public $efetivos_id;
    public $trainees_id;
    public $hora_entrada;
    public $hora_saida;
    public $total_horas_dia;


    public function set($prop, $value) {
        $this->$prop = $value;
    }
    public function get($prop) {
        return isset($this->$prop) ? $this->$prop : null;
    }
    
    public function efetivos_entrada() {
        $objeto = new Conexao();

        $data_atual = date("Y-m-d");

        // Verifica se já tem entrada registrada
        $verificaSQL = "SELECT hora_entrada FROM efetivos_registros 
                        WHERE efetivos_id = '$this->efetivos_id'
                        AND data = '$data_atual';";
        $objeto->set("sql", $verificaSQL);
        $resultado = $objeto->query($verificaSQL);
        $linha = $resultado->fetch_assoc();

        if (!$linha) {
            $SQL = "INSERT INTO efetivos_registros (efetivos_id, data, hora_entrada) 
                    VALUES ('$this->efetivos_id', '$data_atual', '$this->hora_entrada');";
            $objeto->set("sql", $SQL);
            $objeto->query($SQL);
            return "Você registrou a sua entrada!";
        } 
        else {
            return "Entrada já registrada anteriormente.";
        }
    }

    public function trainees_entrada() {
        $objeto = new Conexao();

        $data_atual = date("Y-m-d");

        // Verifica se já tem entrada registrada
        $verificaSQL = "SELECT hora_entrada FROM trainees_registros 
                        WHERE trainees_id = '$this->trainees_id'
                        AND data = '$data_atual';";
        $objeto->set("sql", $verificaSQL);
        $resultado = $objeto->query($verificaSQL);
        $linha = $resultado->fetch_assoc();

        if (!$linha) {
            $SQL = "INSERT INTO trainees_registros (trainees_id, data, hora_entrada) 
                    VALUES ('$this->trainees_id', '$data_atual', '$this->hora_entrada');";
            $objeto->set("sql", $SQL);
            $objeto->query($SQL);
            return "Você registrou a sua entrada!";
        } 
        else {
            return "Entrada já registrada anteriormente.";
        }
    }

    public function efetivos_saida() {
        $objeto = new Conexao();

        $data_atual = date("Y-m-d");
    
        // Verifica se já tem entrada/registro existente
        $verificaSQL = "SELECT hora_entrada, hora_saida FROM efetivos_registros 
                        WHERE efetivos_id = '$this->efetivos_id'
                        AND data = '$data_atual';";
        $objeto->set("sql", $verificaSQL);
        $resultado = $objeto->query($verificaSQL);
        $linha = $resultado->fetch_assoc();
    
        if (!$linha) {
            return "Você precisa registrar a entrada antes de registrar a saída.";
        }
    
        if (empty($linha['hora_entrada'])) {
            return "Você precisa registrar a entrada antes de registrar a saída.";
        }
    
        if (!empty($linha['hora_saida'])) {
            return "Saída já registrada anteriormente.";
        }

        // Cálculo do total de horas
        $hora_entrada = strtotime($linha['hora_entrada']);
        $hora_saida = strtotime($this->hora_saida);
        $total_segundos = $hora_saida - $hora_entrada;
        $total = gmdate("H:i:s", $total_segundos);

        // Atualiza a saída e o total de horas
        $SQL = "UPDATE efetivos_registros SET 
                hora_saida = '$this->hora_saida', 
                total_horas_dia = '$total'
                WHERE efetivos_id = '$this->efetivos_id'
                AND data = '$data_atual';";
        $objeto->set("sql", $SQL);
        $objeto->query();

        // Agora, vamos calcular as horas totais da semana
        $this->efetivos_totais_semanais();

        return "Você registrou a sua saída e o total de horas do dia!";
    }

    public function trainees_saida() {
        $objeto = new Conexao();

        $data_atual = date("Y-m-d");
    
        // Verifica se já tem entrada/registro existente
        $verificaSQL = "SELECT hora_entrada, hora_saida FROM trainees_registros 
                        WHERE trainees_id = '$this->trainees_id'
                        AND data = '$data_atual';";
        $objeto->set("sql", $verificaSQL);
        $resultado = $objeto->query($verificaSQL);
        $linha = $resultado->fetch_assoc();
    
        if (!$linha) {
            return "Você precisa registrar a entrada antes de registrar a saída.";
        }
    
        if (empty($linha['hora_entrada'])) {
            return "Você precisa registrar a entrada antes de registrar a saída.";
        }
    
        if (!empty($linha['hora_saida'])) {
            return "Saída já registrada anteriormente.";
        }

        // Cálculo do total de horas
        $hora_entrada = strtotime($linha['hora_entrada']);
        $hora_saida = strtotime($this->hora_saida);
        $total_segundos = $hora_saida - $hora_entrada;
        $total = gmdate("H:i:s", $total_segundos);

        // Atualiza a saída e o total de horas
        $SQL = "UPDATE trainees_registros SET 
                hora_saida = '$this->hora_saida', 
                total_horas_dia = '$total'
                WHERE trainees = '$this->trainees_id'
                AND data = '$data_atual';";
        $objeto->set("sql", $SQL);
        $objeto->query();

        // Agora, vamos calcular as horas totais da semana
        $this->trainees_totais_semanais();

        return "Você registrou a sua saída e o total de horas do dia!";
    }

    public function efetivos_totais_semanais() {
        $objeto = new Conexao();
        $data_atual = date("Y-m-d");

        // Determina a semana e o ano
        $semana = date("W", strtotime($data_atual)); // Semana do ano
        $ano = date("Y", strtotime($data_atual));    // Ano atual

        // Soma as horas totais da semana (segunda a sábado)
        $sql_total_horas = " SELECT SUM(TIME_TO_SEC(total_horas_dia)) AS total_segundos
                             FROM efetivos_registros
                             WHERE efetivos_id = '$this->efetivos_id'
                             AND WEEK(data, 1) = '$semana'
                             AND YEAR(data) = '$ano'
                             AND WEEKDAY(data) < 7;";
        $objeto->set("sql", $sql_total_horas);
        $resultado = $objeto->query($sql_total_horas);
        $linha = $resultado->fetch_assoc();

        // Converte os segundos totais para o formato H:i:s
        $total_segundos = $linha['total_segundos'];
        $total_horas = gmdate("H:i:s", $total_segundos);

        // Verifica se já existe um registro de horas totais semanais
        $verificaSemanaSQL = "SELECT id FROM efetivos_totais_semanais
                              WHERE efetivos_id = '$this->efetivos_id'
                              AND semana = '$semana'
                              AND ano = '$ano';";
        $objeto->set("sql", $verificaSemanaSQL);
        $resultado = $objeto->query($verificaSemanaSQL);
        $linha = $resultado->fetch_assoc();

        if (!$linha) {
            // Se não houver registro, insere um novo
            $SQL = "INSERT INTO efetivos_totais_semanais (efetivos_id, semana, ano, total_horas) 
                    VALUES ('$this->efetivos_id', '$semana', '$ano', '$total_horas');";
            $objeto->set("sql", $SQL);
            $objeto->query($SQL);
        } else {
            // Se já existir, atualiza o total de horas
            $SQL = "UPDATE efetivos_totais_semanais SET 
                    total_horas = '$total_horas'
                    WHERE efetivos_id = '$this->efetivos_id'
                    AND semana = '$semana'
                    AND ano = '$ano';";
            $objeto->set("sql", $SQL);
            $objeto->query($SQL);
        }
    }

    public function trainees_totais_semanais() {
        $objeto = new Conexao();
        $data_atual = date("Y-m-d");

        // Determina a semana e o ano
        $semana = date("W", strtotime($data_atual)); // Semana do ano
        $ano = date("Y", strtotime($data_atual));    // Ano atual

        // Soma as horas totais da semana (segunda a sábado)
        $sql_total_horas = " SELECT SUM(TIME_TO_SEC(total_horas_dia)) AS total_segundos
                             FROM trainees_registros
                             WHERE trainees_id = '$this->trainees_id'
                             AND WEEK(data, 1) = '$semana'
                             AND YEAR(data) = '$ano'
                             AND WEEKDAY(data) < 7;";
        $objeto->set("sql", $sql_total_horas);
        $resultado = $objeto->query($sql_total_horas);
        $linha = $resultado->fetch_assoc();

        // Converte os segundos totais para o formato H:i:s
        $total_segundos = $linha['total_segundos'];
        $total_horas = gmdate("H:i:s", $total_segundos);

        // Verifica se já existe um registro de horas totais semanais
        $verificaSemanaSQL = "SELECT id FROM trainees_totais_semanais
                              WHERE trainees_id = '$this->trainees_id'
                              AND semana = '$semana'
                              AND ano = '$ano';";
        $objeto->set("sql", $verificaSemanaSQL);
        $resultado = $objeto->query($verificaSemanaSQL);
        $linha = $resultado->fetch_assoc();

        if (!$linha) {
            // Se não houver registro, insere um novo
            $SQL = "INSERT INTO trainees_totais_semanais (trainees_id, semana, ano, total_horas) 
                    VALUES ('$this->trainees_id', '$semana', '$ano', '$total_horas');";
            $objeto->set("sql", $SQL);
            $objeto->query($SQL);
        } else {
            // Se já existir, atualiza o total de horas
            $SQL = "UPDATE trainees_totais_semanais SET 
                    total_horas = '$total_horas'
                    WHERE trainees_id = '$this->trainees_id'
                    AND semana = '$semana'
                    AND ano = '$ano';";
            $objeto->set("sql", $SQL);
            $objeto->query($SQL);
        }
    }
}

?>