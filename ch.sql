CREATE DATABASE ch_apoio;

CREATE TABLE efetivos (
  id int(4) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  nome varchar(100) NOT NULL
);

CREATE TABLE trainees (
  id int(4) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  nome varchar(100) NOT NULL
);

CREATE TABLE efetivos_registros (
  id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  efetivos_id int(4) DEFAULT NULL,
  data date DEFAULT NULL,
  hora_entrada time DEFAULT NULL,
  hora_saida time DEFAULT NULL,
  total_horas_dia time DEFAULT NULL,
  FOREIGN KEY (efetivos_id) REFERENCES efetivos (id)
);

CREATE TABLE trainees_registros (
  id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  trainees_id int(4) DEFAULT NULL,
  data date DEFAULT NULL,
  hora_entrada time DEFAULT NULL,
  hora_saida time DEFAULT NULL,
  total_horas_dia time DEFAULT NULL,
  FOREIGN KEY (trainees_id) REFERENCES trainees (id)
);

CREATE TABLE efetivos_totais_semanais (
  id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  efetivos_id int(11) DEFAULT NULL,
  semana int(11) DEFAULT NULL,
  ano int(11) DEFAULT NULL,
  total_horas time DEFAULT NULL,
  FOREIGN KEY (efetivos_id) REFERENCES efetivos (id)
);

CREATE TABLE trainees_totais_semanais (
  id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  trainees_id int(11) DEFAULT NULL,
  semana int(11) DEFAULT NULL,
  ano int(11) DEFAULT NULL,
  total_horas time DEFAULT NULL,
  FOREIGN KEY (trainees_id) REFERENCES trainees (id)
);

CREATE TABLE tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    token VARCHAR(64) NOT NULL,
    usado TINYINT(1) DEFAULT 0,
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO efetivos (nome) VALUES
('Adrielly Cristine Barcelos Silva'),
('Ana Júlia Fares Casagrande'),
('Ana Maria Marques Silveira'),
('Bruno Agapito Moraes'),
('Cecília Mamede Motta'),
('Fabiane lage Silva'),
('Fernanda Cristina Faria Almeida de Paula'),
('Fernnanda Queiroz Calmon Almeida Gomes'),
('Gabriela Cavalheiro Bassi'),
('Gustavo Verçosa Rezende'),
('Higor Elias Lopes De Andrade'),
('Isabella Teodoro de Andrade'),
('Kalliny Ester Ribeiro Feitosa do Nascimento'),
('Luana Sant’anna Lula Maciel'),
('Maria Eduarda Resende Nascimento'),
('Mariana Dias de Paula'),
('Matheus Eduardo Coelho'),
('Matheus Henrique Araújo de Carvalho'),
('Savio Humberto Freitas Reis'),
('Solano Anute Furtado'),
('Tarsila Medina Teixeira'),
('Tiago Alexandre Malaquias'),
('Vítor Luís Tendolini da Silva'),
('Yasmin dos Reis Maurício');

INSERT INTO trainees (nome) VALUES
('Alice Campos Fonseca'),
('Ana Carolina de Souza França Melo'),
('Ana Luísa Bizinoto Martins'),
('Augusto Naves Fernandes'),
('Beatriz Roque Brugin'),
('Bernardo Mousinho Frade'),
('Daniel Carlos Medeiros Alves'),
('Diogo dos Santos Tiago'),
('Gabriel dos Santos Diniz'),
('Isadora do Vale Rezende'),
('Jeanny Cristine Vieira Silva'), 
('João Paulo Guimarães'),
('João Vitor de Oliveira Botelho'),
('João Vitor Romão Paulino'),
('Lais Suitt Gomides'),
('Lanna Maria Vital Almeida Messias'),
('Leandra Sousa Araújo'),
('Lorenzo Milazzo Corticioni'),
('Luisa Cavalcanti Brandão da Fonseca'),
('Luiza Giroto Mendonça'),
('Luiza Vieira Pavarina'),
('Marcelo Marques Rocha'),
('Marcus Vinicius Alves Marques'),
('Maria Fernanda Ferreira Bereniz'),
('Maria Paula Kitagawa'),
('Matheus Miranda de Morais'),
('Nicole Christine Silva'),
('Nicollas Ferreira Santos Rocha'),
('Patrícia dos Santos Pereira'),
('Pedro Henrique de Oliveira Bezerra'),
('Yasmin Carolyne Souza Soares');
