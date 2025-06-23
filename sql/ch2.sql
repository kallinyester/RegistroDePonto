CREATE DATABASE ch2;

CREATE TABLE funcionarios (
    id int(4) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nome varchar(100) NOT NULL,
    situacao varchar(30) NOT NULL
);

CREATE TABLE usuario (
    id int(4) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    usuario varchar(50) NOT NULL,
    senha varchar(50) NOT NULL
);

CREATE TABLE evento (
    id int(4) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    evento varchar(50) NOT NULL,
    data date NOT NULL,
    hora_inicio time DEFAULT NULL
);

CREATE TABLE funcionarios_sede (
    id int(4) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    funcionarios_id int(4) DEFAULT NULL,
    data date DEFAULT NULL,
    hora_entrada time DEFAULT NULL,
    hora_saida time DEFAULT NULL,
    total_horas_dia time DEFAULT NULL,
    FOREIGN KEY (funcionarios_id) REFERENCES funcionarios (id)
);

CREATE TABLE funcionarios_rg (
    id int(4) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    funcionarios_id int(4) DEFAULT NULL,
    data date DEFAULT NULL,
    hora_entrada time DEFAULT NULL,
    FOREIGN KEY (funcionarios_id) REFERENCES funcionarios (id)
);

CREATE TABLE funcionarios_evento (
    id int(4) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    evento_id int(4) NOT NULL,
    funcionarios_id int(4) DEFAULT NULL,
    data date DEFAULT NULL,
    hora_entrada time DEFAULT NULL,
    FOREIGN KEY (evento_id) REFERENCES evento (id),
    FOREIGN KEY (funcionarios_id) REFERENCES funcionarios (id)
);

CREATE TABLE totais_semanais (
    id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    funcionarios_id int(11) DEFAULT NULL,
    semana int(11) DEFAULT NULL,
    ano int(11) DEFAULT NULL,
    total_horas time DEFAULT NULL,
    FOREIGN KEY (funcionarios_id) REFERENCES funcionarios (id)
);

CREATE TABLE tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    token INT NOT NULL,
    usado TINYINT(1) DEFAULT 0,
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
);