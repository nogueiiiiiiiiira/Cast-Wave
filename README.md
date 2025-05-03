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

## ⚙️ Requisitos para Executar o Projeto

1. **Servidor Local**
   - Instale o [XAMPP](https://www.apachefriends.org/index.html)
   - Inicie os módulos **Apache** e **MySQL**

2. **Banco de Dados**
   - Acesse o `phpMyAdmin` pelo XAMPP
   - Crie um banco de dados chamado `castwave`
   - Execute o script SQL abaixo para criar as tabelas necessárias:

```sql
CREATE DATABASE IF NOT EXISTS castwave;
USE castwave;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cpf VARCHAR(20) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    data_nasc DATE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    telefone VARCHAR(20) UNIQUE NOT NULL
);

CREATE TABLE IF NOT EXISTS alugueis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    filme_id INT NOT NULL,
    preco DECIMAL(5,2) NOT NULL,
    nome_filme VARCHAR(255) NOT NULL,
    genero_filme VARCHAR(255) NOT NULL,
    classificacao VARCHAR(10) NOT NULL,
    data_inicio DATETIME NOT NULL,
    data_fim DATETIME,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

CREATE TABLE IF NOT EXISTS contatos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    assunto VARCHAR(100) NOT NULL,
    mensagem TEXT NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    data_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

## 🚀 Como Executar

- Certifique-se de que os módulos Apache e MySQL do XAMPP estejam rodando.
- Acesse o sistema pelo navegador em: `http://localhost/projetoLocadora/index.html`
- Faça login ou cadastre-se para começar a usar o sistema.

---

## ℹ️ Sobre a API TMDb

O sistema utiliza a API pública **The Movie Database (TMDb)** para obter informações atualizadas sobre filmes, gêneros, classificações e trailers. A chave da API está configurada no código-fonte, mas você pode substituir pela sua própria chave para evitar limitações.