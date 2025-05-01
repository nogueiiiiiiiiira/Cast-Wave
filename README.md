# 🎬 CastWave

**CastWave** é um sistema web inspirado no Stremio, desenvolvido com tecnologias como **HTML**, **CSS**, **JavaScript**, **PHP** e **MySQL**. Seu principal objetivo é permitir a navegação, o aluguel e o gerenciamento de conteúdos audiovisuais, com integração à API pública **The Movie Database (TMDb)**.

---

## 🚀 Funcionalidades

- Integração com a **API TMDb**
- Cadastro, visualização, edição e exclusão de registros (CRUD)
- Sistema de login e logout com controle de sessão
- Criptografia de senhas com `password_hash()` e `password_verify()`
- Cadastro de usuários com validações de dados
- Registro e controle de aluguéis de filmes
- Interface responsiva e dinâmica (mobile e desktop)
- Proteção contra acesso a páginas privadas sem login
- Mensagens de feedback (sucesso, erro, etc.)
- Estrutura preparada para futuras extensões (comentários, avaliações, etc.)

---

## 🛠 Tecnologias Utilizadas

- **HTML5**
- **CSS3**
- **JavaScript (ES6)**
- **PHP 7+**
- **MySQL**
- **XAMPP** (Apache + MySQL)
- **API TMDb**

---

## Inicie:

- Digite: `http://localhost/projetoLocadora/index.html`

---

## ⚙️ Requisitos para Executar o Projeto

1. **Servidor Local**
   - Instale o [XAMPP](https://www.apachefriends.org/index.html)
   - Inicie os módulos **Apache** e **MySQL**

2. **Banco de Dados**
   - Acesse o `phpMyAdmin`
   - Crie um banco de dados chamado `castwave`
   - Execute o script SQL abaixo para criar as tabelas:

```sql
CREATE DATABASE IF NOT EXISTS castwave;
USE castwave;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cpf VARCHAR(20) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    dataNasc DATE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    telefone VARCHAR(20) UNIQUE NOT NULL
);

CREATE TABLE IF NOT EXISTS alugueis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    filme_id INT NOT NULL,
    preco DECIMAL(5,2) NOT NULL,
    nome_filme VARCHAR(255) NOT NULL,
    data_inicio DATE NOT NULL,
    data_devolucao DATE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

CREATE TABLE contatos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  assunto VARCHAR(100) NOT NULL,
  mensagem TEXT NOT NULL,
  telefone VARCHAR(20) NOT NULL,
  data_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


