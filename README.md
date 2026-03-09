<div align="center">

# Registro de Ponto Apoio Consultoria

**Sistema de controle de presença por QR Code**

![PHP](https://img.shields.io/badge/PHP-96.9%25-777BB4?style=for-the-badge&logo=php&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-2.3%25-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![CSS](https://img.shields.io/badge/CSS-0.8%25-1572B6?style=for-the-badge&logo=css3&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white)

</div>

---

## Sobre o Projeto

O **Registro de Ponto** é uma aplicação web desenvolvida em PHP para a **Apoio Consultoria**, que controla a entrada e saída de membros da sede. Ao escanear o QR Code com o celular, o membro é direcionado a uma página (form) onde informa seu tipo (efetivo ou trainee), seleciona seu nome na lista e marca se está **entrando ou saindo** da sede. O registro é salvo automaticamente com data e hora no banco de dados.

> **Fluxo resumido:** Escaneia o QR Code → preenche o formulário → sistema registra data, hora e movimento → administrador consulta no painel.

---

## Funcionalidades

- **Registro via QR Code** — escaneia e abre direto o formulário de ponto
- **Seleção de membro** — escolha entre efetivo ou trainee e seleciona o nome na lista
- **Entrada e saída** — o membro marca se está chegando ou saindo da sede
- **Painel administrativo** — visualização e gestão de todos os registros
- **Acesso protegido** — painel restrito com autenticação por sessão PHP
- **Arquitetura em camadas** — padrão DAO para separação entre lógica e banco de dados

---

## Estrutura do Projeto

```
RegistroDePonto/
│
├── 📁 DAO/                  → Camada de acesso ao banco de dados
├── 📁 painel/               → Painel administrativo (área restrita)
├── 📁 resgistro/            → Lógica de registro de ponto
├── 📁 sql/                  → Scripts SQL para criação das tabelas
│
├── qrcode.php               → Gerador de QR Code genérico
├── qrcode_sede.php          → QR Code da sede
├── qrcode_evento.php        → QR Code para eventos
├── qrcode_rg.php            → QR Code vinculado ao RG
├── qrcodeDAO.php            → DAO das operações de QR Code
├── verificaLogin.php        → Controle de autenticação do painel
│
├── sede_qrcode.png          → Imagem do QR Code da sede
├── evento_qrcode.png        → Imagem do QR Code do evento
└── rg_qrcode.png            → Imagem do QR Code de RG
```

---

## Fluxo do Sistema

```
┌─────────────────────────────────────────────────────────────┐
│                                                             │
│  1. Membro escaneia o QR Code com o celular                 │
│                         ⬇                                   │
│  2. Navegador abre a página de registro (/resgistro)        │
│                         ⬇                                   │
│  3️. Membro seleciona: tipo (efetivo/trainee),               │
│     nome e entrada ou saída                                 │
│                         ⬇                                   │
│  4️. Sistema captura data e hora do servidor                 │
│                         ⬇                                   │
│  5️. DAO executa INSERT no banco de dados MySQL              │
│                         ⬇                                   │
│  6️. Registro aparece no /painel para o administrador        │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

---

## Páginas e Componentes

### Página Inicial (`qrcode.php`)

> <img width="1919" height="863" alt="image" src="https://github.com/user-attachments/assets/bf512963-66c8-4617-8540-2a7f61e234f5" />

### QR Codes (`qrcode_sede.php` · `qrcode_evento.php` · `qrcode_rg.php`)
Geram as imagens QR Code salvas no servidor. Cada arquivo codifica a URL da página de registro correspondente ao seu contexto. O QR Code fica fixado na sede — ao ser escaneado pelo celular, abre diretamente o formulário de registro de ponto.

> <img width="1919" height="862" alt="image" src="https://github.com/user-attachments/assets/eb29d918-3f11-430c-ad6b-e8339464e57c" />

---

### Tela de Registro (`/resgistro`)
Página de registro. É acessada ao escanear o QR Code e apresenta um formulário com três campos obrigatórios:

- **Tipo de membro** — efetivo ou trainee
- **Nome** — selecionado em uma lista (sem digitação livre)
- **Movimento** — entrada ou saída da sede

Após o envio, o sistema captura automaticamente a data e hora do servidor e persiste o registro no banco via DAO. O membro recebe uma confirmação visual na tela.

> <img width="1919" height="869" alt="image" src="https://github.com/user-attachments/assets/f49323fe-07e1-47f9-a621-bdd430bba938" />
> <img width="1919" height="860" alt="image" src="https://github.com/user-attachments/assets/c47b3643-6ee0-4947-bd3b-8d961f7e21ce" />
> <img width="1919" height="860" alt="image" src="https://github.com/user-attachments/assets/f501e9a5-70bc-4ee4-8ccb-c51995fafe21" />

---

### Painel Administrativo (`/painel`)
Área restrita ao administrador da Apoio Consultoria. Exibe todos os registros de ponto com nome do membro, tipo (efetivo/trainee), movimento (entrada/saída), data e hora. Permite filtrar por período e oferece uma visão consolidada da presença na sede. Só pode ser acessado após autenticação válida.

> <img width="1919" height="862" alt="image" src="https://github.com/user-attachments/assets/203d08cf-85fc-4e4c-b0a8-cd6afeeee3c8" />

---

### Controle de Acesso (`verificaLogin.php`)
Incluído no topo de todas as páginas restritas. Verifica se há uma sessão PHP ativa; caso contrário, redireciona para o login. É a "porteira" do sistema — sem ele, qualquer pessoa com a URL poderia acessar os dados.

><img width="1919" height="859" alt="image" src="https://github.com/user-attachments/assets/b1162121-7f93-4063-99a0-637141d64f9b" />

---

### Camada de Dados (`/DAO` · `qrcodeDAO.php`)
Implementam o padrão **DAO** (*Data Access Object*), encapsulando todas as queries SQL. As páginas PHP chamam funções do DAO sem escrever SQL diretamente, tornando o código mais organizado e fácil de manter.

Operações típicas:
- `inserirRegistro($nome, $tipo, $movimento, $data, $hora)` — salva um novo ponto
- `listarRegistros($filtro)` — retorna registros para o painel
- `buscarPorData($inicio, $fim)` — filtra por período
- `listarMembros($tipo)` — retorna a lista de nomes por tipo (efetivo/trainee)

---

## Arquitetura

O projeto segue uma **arquitetura em camadas** inspirada no padrão MVC:

| Camada | Responsabilidade | Onde está |
|--------|-----------------|-----------|
| **Apresentação** | Telas que o usuário acessa | `/painel`, QR Codes |
| **Lógica de Negócio** | Processa o scan e decide o que salvar | `/resgistro` |
| **Acesso a Dados** | Executa as queries SQL | `/DAO`, `qrcodeDAO.php` |
| **Banco de Dados** | Armazena os registros | MySQL (scripts em `/sql`) |

> **Benefício do DAO:** se o banco de dados mudar, apenas os arquivos DAO precisam ser atualizados. O restante do sistema continua igual.

---

## Tecnologias Utilizadas

| Tecnologia | Uso no Projeto |
|-----------|---------------|
| **PHP** | Linguagem principal — lógica, geração de QR Code, sessões |
| **MySQL / MariaDB** | Banco de dados dos registros de ponto |
| **Padrão DAO** | Separação entre lógica de negócio e SQL |
| **Sessões PHP** | Autenticação e controle de acesso (`$_SESSION`) |
| **Geração de QR Code** | Criação das imagens `.png` via biblioteca PHP |
| **JavaScript** | Ajustes de comportamento na interface |
| **CSS** | Estilização das telas |

---

## Como Executar

### Pré-requisitos

- PHP 7.x ou superior (XAMPP, WAMP ou servidor Linux)
- MySQL ou MariaDB
- Extensão PHP para MySQL (`mysqli` ou `PDO_MySQL`)
- Biblioteca PHP de geração de QR Code (ex.: [phpqrcode](http://phpqrcode.sourceforge.net/))

### Passo a Passo

**1. Clone o repositório**
```bash
git clone https://github.com/kallinyester/RegistroDePonto.git
```

**2. Configure o banco de dados**
```bash
# Importe os scripts da pasta /sql no seu MySQL
mysql -u seu_usuario -p seu_banco < sql/nome_do_script.sql
```

**3. Configure as credenciais**

Edite os arquivos na pasta `/DAO` com os dados do seu banco:
```php
$host   = "localhost";
$usuario = "seu_usuario";
$senha   = "sua_senha";
$banco   = "nome_do_banco";
```

**4. Suba os arquivos**

Coloque o projeto na pasta do seu servidor web:
- XAMPP → `htdocs/RegistroDePonto`
- Linux → `/var/www/html/RegistroDePonto`

**5. Gere os QR Codes**

Acesse no navegador:
```
http://localhost/RegistroDePonto/qrcode_sede.php
http://localhost/RegistroDePonto/qrcode_evento.php
http://localhost/RegistroDePonto/qrcode_rg.php
```

**6. Use o sistema**

- Imprima ou exiba o QR Code na sede — ao ser escaneado, abre o formulário de registro
- O membro seleciona seu tipo, nome e se está entrando ou saindo
- Acesse `/painel` com login de administrador para visualizar os registros

---

<div align="center">

Feito por [kallinyester](https://github.com/kallinyester)

</div>
