# Prompt para Assistente de Desenvolvimento (Gemini)

Este documento serve como um guia para as minhas interações com você, o assistente Gemini. O objetivo é manter um registro claro das metas, tecnologias e tarefas do projeto "Frango Fusca".

## 1. Visão Geral do Projeto

*   **Nome do Projeto:** Frango Fusca
*   **Objetivo Principal:** Desenvolver um sistema web para gerenciamento de um negócio, que parece ser um ponto de venda (PDV). As tabelas do banco de dados (`cliente`, `produto`, `venda`, `fornecedor`) sugerem funcionalidades de controle de vendas, estoque e finanças.

## 2. Tecnologias Utilizadas

*   **Back-end:** PHP. O uso do `composer.json` indica que as dependências são gerenciadas pelo Composer.
*   **Front-end:** JavaScript (vanilla) e CSS.
*   **Banco de Dados:** SQL (provavelmente MySQL/MariaDB, a julgar pela sintaxe comum nos arquivos `.sql`).
*   **Estrutura:** Aplicação web "clássica" com renderização no lado do servidor (SSR), utilizando includes em PHP para templates (`header.php`, `footer.php`).

## 3. Visão Geral do Código Existente

A estrutura atual do projeto é:
*   `index.php`: Provável ponto de entrada da aplicação.
*   `config/config.php`: Arquivo para configurações (ex: credenciais do banco de dados).
*   `assets/`: Contém os arquivos estáticos (CSS, JS, imagens).
*   `templates/`: Partes reutilizáveis de HTML/PHP.
*   `db/`: Scripts para criação da estrutura do banco de dados.
*   `vendor/`: Dependências de terceiros instaladas pelo Composer.

## 4. Estilo e Convenções

Para manter a consistência, todo novo código deve seguir o estilo e as práticas já estabelecidas no projeto. Ao adicionar novas funcionalidades, devemos tentar separar as responsabilidades (lógica de negócio, acesso a dados, apresentação).

## 5. Meu Papel (Gemini)

Como seu assistente de IA, minhas funções são:
*   Escrever, refatorar e depurar código (PHP, JS, SQL, CSS).
*   Criar novos arquivos e funcionalidades.
*   Seguir as instruções e convenções aqui descritas.
*   Auxiliar na criação de uma arquitetura de software organizada.

## 6. Tarefas

Aqui você pode listar as tarefas que preciso executar. Por favor, seja específico.

**Exemplos de como você pode me pedir algo:**

*   "Crie um arquivo `database.php` que estabeleça uma conexão PDO com o banco de dados usando as configurações de `config/config.php`."
*   "Na `index.php`, inclua o `header.php` e o `footer.php` e, no meio, liste todos os produtos da tabela `produto`."
*   "Implemente a funcionalidade de login. Crie o formulário HTML, o script PHP para validar o usuário contra a tabela `usuario` e o JS para feedback dinâmico."

---

**Sua vez! Qual a primeira tarefa que você quer que eu realize?**
