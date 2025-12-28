# Prompt para Assistente de Desenvolvimento (Gemini)

Este documento serve como um guia para as minhas interações com você, o assistente Gemini. O objetivo é manter um registro claro das metas, tecnologias e tarefas do projeto "Frango Fusca".

## 1. Visão Geral do Projeto

*   **Nome do Projeto:** Frango Fusca
*   **Objetivo Principal:** Desenvolver um sistema web para gerenciamento de um negócio de ponto de venda (PDV), com funcionalidades de controle de vendas, estoque e finanças.

## 2. Tecnologias Utilizadas

*   **Back-end:** PHP 8+
*   **Front-end:** HTML, CSS, JavaScript (jQuery, DataTables), Bootstrap.
*   **Banco de Dados:** SQL (MySQL/MariaDB).
*   **Gerenciador de Dependências:** Composer.

## 3. Arquitetura e Convenções

Esta seção detalha a arquitetura e as convenções que devem ser seguidas estritamente em todos os desenvolvimentos.

### 3.1. Visão Geral da Arquitetura

O projeto segue um padrão de 3 camadas, separando responsabilidades:

1.  **Endpoints (Camada de Controle):** Arquivos PHP mínimos que recebem requisições HTTP, delegam a lógica para as Entidades e formatam a resposta JSON.
2.  **Entidades (Camada de Lógica de Negócio):** Classes PHP que contêm toda a lógica de negócio, validação e acesso a dados para uma tabela específica do banco de dados.
3.  **Banco de Dados (Camada de Persistência):** A interação é feita via PDO, gerenciada por uma classe de conexão (`Conexao.php`).

### 3.2. Convenções de Back-end

*   **Namespaces e Autoloading:** O projeto utiliza PSR-4 autoloading gerenciado pelo Composer. Todas as classes devem pertencer ao namespace `FrangoFusca` e serem carregadas via `vendor/autoload.php`. **NÃO usar `require_once` para classes.**
*   **Entidades Estáticas:** As classes de entidade (ex: `UnidadeMedida`) são classes de utilitário **estático**. Elas não devem ter estado (propriedades de instância) nem métodos de instância (sem getters, setters ou construtor).
*   **Validação Centralizada na Entidade:** Toda a validação (campos obrigatórios, duplicidade, existência de registros) deve ser feita **dentro da classe da entidade**, que deve lançar uma `\Exception` com uma mensagem clara e um código de erro HTTP (400, 404) em caso de falha.
*   **Endpoints Mínimos ("Thin Controllers"):** Os endpoints devem:
    1.  Incluir `config.php` e `autoload.php`.
    2.  Usar o helper `verificarMetodo()` para validar o método HTTP.
    3.  Capturar os dados da requisição (usando `filter_input`).
    4.  Envolver a chamada à entidade em um bloco `try-catch`.
    5.  Chamar o método estático da entidade apropriada.
    6.  Retornar uma resposta JSON.
*   **Injeção da Conexão:** A conexão PDO deve ser obtida no endpoint (`Conexao::obterConexao()`) e **passada como argumento** para os métodos da entidade.
*   **Funções Helper:** Lógica de aplicação reaproveitável, como `verificarMetodo()`, deve ser colocada em `src/helpers/` e registrada no `composer.json` (seção `files`) para autoloading.
*   **Caminhos de Arquivo:** Usar sempre `__DIR__` para construir caminhos de arquivo em `require_once`/`include`.

### 3.3. Convenções de Front-end

*   **Separação de Responsabilidades (JavaScript):** O código JS para um módulo deve ser dividido em arquivos com responsabilidades únicas (ex: `tabela.js`, `cadastrar_editar.js`, `deletar.js`) dentro de uma pasta específica para o módulo (`assets/js/nome_do_modulo/`).
*   **Comunicação Assíncrona:** Toda a interação entre o front-end e o back-end deve ser feita via AJAX (usando `$.ajax` do jQuery), sem recarregamentos de página.
*   **Frameworks:** jQuery é a base para manipulação de DOM e AJAX. DataTables para tabelas. Bootstrap para UI e modais.

### 3.4. Estrutura de Diretórios

*   `pages/`: Contém as páginas PHP visíveis para o usuário.
*   `src/`: Contém todo o código-fonte da aplicação PHP.
    *   `core/`: Interfaces e classes base.
    *   `db/`: Classe de conexão com o banco de dados.
    *   `entidades/`: As classes de entidade.
    *   `helpers/`: Funções de auxílio.
    *   `cadastrar/`, `consultar/`, etc.: Pastas contendo os arquivos de endpoint.
*   `assets/`: Arquivos estáticos (CSS, Imagens, JS).
*   `templates/`: Partes de HTML reutilizáveis (header, footer).
*   `config/`: Arquivos de configuração.

## 4. Meu Papel (Gemini)

Como seu assistente de IA, minhas funções são:
*   Escrever, refatorar e depurar código (PHP, JS, SQL, CSS).
*   Criar novos arquivos e funcionalidades.
*   **Seguir estritamente as instruções e convenções aqui descritas.**
*   Auxiliar na manutenção da arquitetura de software organizada.

## 5. Tarefas

Aqui você pode listar as tarefas que preciso executar. Por favor, seja específico.

**Exemplos de como você pode me pedir algo:**

*   "Implemente o CRUD completo para a entidade `MetodoPagamento`, seguindo a arquitetura e convenções definidas neste documento."
*   "Refatore o endpoint `login.php` para usar o helper `verificarMetodo` e mover a validação para uma entidade `Usuario`."

---

**Sua vez! Qual a próxima tarefa que você quer que eu realize?**
