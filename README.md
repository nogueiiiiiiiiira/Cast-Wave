# 🎬 CastWave - Projeto Locadora

**CastWave** é um sistema web inspirado no Stremio, desenvolvido com tecnologias como **HTML**, **CSS**, **JavaScript**, **PHP** e **MySQL**. Seu principal objetivo é permitir a navegação, o aluguel e o gerenciamento de conteúdos audiovisuais, com integração à API pública **The Movie Database (TMDb)**.

---

## ✨ Funcionalidades

- Integração com a **API TMDb**
- Cadastro, visualização, edição e exclusão de registros (CRUD)
- Sistema de login e logout com controle de sessão
- Criptografia de senhas com `password_hash()` e `password_verify()`
- Cadastro de usuários com validações de dados
- Registro e controle de aluguéis de filmes
- Interface responsiva e dinâmica (mobile e desktop)
- Proteção contra acesso a páginas privadas sem login
- Mensagens de feedback (sucesso, erro, etc.)
- Recomendação personalizada de filmes via modelo Ollama "gemma"

---

## 🛠 Tecnologias Utilizadas

- **HTML5**
- **CSS3**
- **JavaScript (ES6)**
- **PHP 7+**
- **MySQL**
- **XAMPP** (Apache + MySQL)
- **API TMDb**
- **Ollama CLI** (modelo "gemma")

---

## 🚀 Como Iniciar

1. Inicie seu servidor local (ex: XAMPP).
2. Acesse o endereço no navegador:
   ```
   http://localhost/projetoLocadora/index.html
   ```

---

## ⚙️ Requisitos para Executar o Projeto

1. **Servidor Local**
   - Instale o [XAMPP](https://www.apachefriends.org/index.html)
   - Inicie os módulos **Apache** e **MySQL**

2. **Banco de Dados**
   - Acesse o `phpMyAdmin`
   - Crie o banco de dados `castwave`
   - Execute o script SQL abaixo para criar as tabelas necessárias:

```sql
CREATE DATABASE IF NOT EXISTS castwave;
USE castwave;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cpf VARCHAR(20) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    telefone VARCHAR(20) UNIQUE NOT NULL,
    data_nasc DATE NOT NULL,
    senha VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS alugueis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    filme_id INT NOT NULL,
    preco DECIMAL(5,2) NOT NULL,
    nome_filme VARCHAR(255) NOT NULL,
    genero_filme VARCHAR(255) NOT NULL,
    classificacao VARCHAR(10) NOT NULL,
    data_inicio DATE NOT NULL,
    data_fim DATE,
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

## 🎯 Como Funciona a Recomendação pela IA

- O backend (`paginas/recomendacoes.php`) consulta os gêneros de filmes mais assistidos pelo usuário.
- Gera uma mensagem personalizada para o modelo Ollama "gemma".
- Executa o comando Ollama CLI para obter recomendações.
- Retorna as recomendações em JSON para o frontend.
- O frontend (`assets/scripts/minhas_recomendacoes.js`) faz uma requisição para o backend e exibe as recomendações.

---

## 🧪 Testando a Recomendação

- Acesse a página que utiliza o script `minhas_recomendacoes.js`.
- Certifique-se que o usuário está logado.
- Clique para carregar as recomendações.
- Verifique o console do navegador e os logs do servidor para possíveis erros.

---

## 📂 Estrutura dos Arquivos Importantes

- `index.html` - Página inicial do sistema.
- `paginas/` - Contém os scripts PHP para backend (login, cadastro, recomendações, etc).
- `assets/scripts/` - Scripts JavaScript para funcionalidades do frontend.
- `assets/css/` - Arquivos CSS para estilos.
- `assets/imagens/` - Imagens usadas no projeto.

---

## ⚠️ Dicas e Solução de Problemas

- **Erro "Erro ao executar o modelo de IA"**:
  - Verifique se o comando `ollama` está acessível no ambiente do PHP (variável PATH).
  - Confirme que o modelo "gemma" está instalado (`ollama list`).
  - Assegure que o PHP tem permissão para executar comandos shell (`shell_exec` habilitado).
- Para testar manualmente o modelo, execute:
  ```
  ollama run gemma "teste"
  ```
- Ajuste o script PHP se precisar alterar a forma de comunicação com o Ollama.

---

## 📞 Contato

Para dúvidas, sugestões ou contribuições, entre em contato com o desenvolvedor.

---

Obrigado por usar o CastWave! 🎉
