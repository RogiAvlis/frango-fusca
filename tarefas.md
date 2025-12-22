# Lista de Tarefas Detalhadas - Projeto Frango Fusca

Este documento detalha todas as atividades necessárias para a conclusão do sistema "Frango Fusca", organizado por módulos e sub-tarefas, abrangendo back-end, front-end e banco de dados.

## 1. Configuração do Ambiente e Arquitetura Base

*   **1.1. Conexão com o Banco de Dados:**
    *   Criar o arquivo `src/db/database.php`.
    *   Implementar a conexão utilizando PDO (PHP Data Objects) para garantir segurança e flexibilidade.
    *   Configurar as credenciais do banco de dados a partir de `config/config.php`.
    *   Incluir tratamento de erros básico para a conexão.
*   **1.2. Definição da Estrutura de Pastas (Back-end):**
    *   Garantir a existência das seguintes pastas no diretório `src/`:
        *   `src/consultar/`: Para scripts de leitura de dados.
        *   `src/cadastrar/`: Para scripts de inserção de dados.
        *   `src/editar/`: Para scripts de atualização de dados.
        *   `src/deletar/`: Para scripts de exclusão de dados.
        *   `src/entidades/`: Para as classes PHP que representam as entidades do banco de dados.
        *   `src/core/`: Para classes utilitárias ou base, como uma classe de entidade genérica.
*   **1.3. Classe Base para Entidades (Opcional, mas recomendado):**
    *   Criar o arquivo `src/core/EntidadeBase.php` (ou similar).
    *   Definir uma classe abstrata ou interface para padronizar métodos comuns de CRUD (cadastrar, listar, buscarPorId, editar, deletar) que serão implementados pelas entidades específicas.
*   **1.4. Estrutura da Página Base (Front-end):**
    *   Revisar o `templates/header.php` para garantir que inclua todas as bibliotecas CSS (Bootstrap, Font Awesome) e JS (jQuery, Bootstrap, DataTables) necessárias globalmente.
    *   Revisar o `templates/footer.php` para incluir scripts JS globais e fechar tags HTML.
    *   Criar uma página PHP modelo (`template_crud.php` ou similar) que inclua o `header.php`, `footer.php` e tenha a estrutura básica para uma tabela e um modal de CRUD, servindo como ponto de partida para cada entidade.

---

## 2. Módulos (Entidades)

### 2.1. Módulo: Unidade de Medida

**Visão Geral:** Implementação completa do CRUD (Criar, Ler, Atualizar, Deletar) para a entidade `unidade_medida`.

*   **2.1.1. Back-end:**
    *   **2.1.1.1. Classe da Entidade `UnidadeMedida`:**
        *   Criar o arquivo `src/entidades/UnidadeMedida.php`.
        *   Definir a classe `UnidadeMedida` com as propriedades privadas `id`, `sigla` e `nome` e seus respectivos métodos `getter` e `setter`.
        *   Implementar o método estático `cadastrar(sigla, nome)`:
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `INSERT INTO unidade_medida (sigla, nome, criado_por, data_criacao)` (considerar o `criado_por` e `data_criacao` como padrão para todas as entidades).
            *   Executar a query, tratando possíveis erros e retornado um indicador de sucesso/falha (e o ID inserido, se aplicável).
        *   Implementar o método estático `listar()`:
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `SELECT id, sigla, nome FROM unidade_medida WHERE status_registro = 1`.
            *   Executar a query e retornar um array de objetos `UnidadeMedida` ou um array associativo dos dados, adequado para consumo pelo DataTable.
        *   Implementar o método estático `buscarPorId(id)`:
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `SELECT id, sigla, nome FROM unidade_medida WHERE id = :id AND status_registro = 1`.
            *   Executar a query e retornar um objeto `UnidadeMedida` ou `null` se não encontrado.
        *   Implementar o método estático `editar(id, sigla, nome)`:
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `UPDATE unidade_medida SET sigla = :sigla, nome = :nome, alterado_por = :alterado_por, data_alteracao = CURRENT_TIMESTAMP WHERE id = :id`.
            *   Executar a query, tratando erros e retornado um indicador de sucesso/falha.
        *   Implementar o método estático `deletar(id)` (Exclusão Lógica):
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `UPDATE unidade_medida SET status_registro = 0, alterado_por = :alterado_por, data_alteracao = CURRENT_TIMESTAMP WHERE id = :id`.
            *   Executar a query, tratando erros e retornado um indicador de sucesso/falha.

    *   **2.1.1.2. Endpoints AJAX (PHP):**
        *   **`src/cadastrar/unidade_medida.php`:**
            *   Verificar se a requisição é POST.
            *   Validar e sanitizar os dados de entrada (`sigla`, `nome`).
            *   Chamar `UnidadeMedida::cadastrar()`.
            *   Retornar uma resposta JSON com `{'status': 'success', 'message': '...'}` ou `{'status': 'error', 'message': '...'}`.
        *   **`src/consultar/unidade_medida.php`:**
            *   Chamar `UnidadeMedida::listar()`.
            *   Retornar uma resposta JSON formatada para o DataTables (objeto com `data` array).
        *   **`src/consultar/unidade_medida_por_id.php`:**
            *   Verificar se a requisição é GET e se um `id` válido foi fornecido.
            *   Chamar `UnidadeMedida::buscarPorId(id)`.
            *   Retornar uma resposta JSON com os dados da unidade de medida ou erro.
        *   **`src/editar/unidade_medida.php`:**
            *   Verificar se a requisição é POST.
            *   Validar e sanitizar os dados de entrada (`id`, `sigla`, `nome`).
            *   Chamar `UnidadeMedida::editar()`.
            *   Retornar uma resposta JSON com sucesso ou erro.
        *   **`src/deletar/unidade_medida.php`:**
            *   Verificar se a requisição é POST e se um `id` válido foi fornecido.
            *   Chamar `UnidadeMedida::deletar()`.
            *   Retornar uma resposta JSON com sucesso ou erro.

*   **2.1.2. Front-end:**
    *   **2.1.2.1. Página `unidade_medida.php`:**
        *   Criar o arquivo `unidade_medida.php` na raiz (ou em uma pasta `/pages`).
        *   Incluir `templates/header.php` e `templates/footer.php`.
        *   Estruturar o HTML para:
            *   Um título (`<h1>` ou similar).
            *   Um botão "Nova Unidade de Medida" que ative um modal.
            *   A tabela HTML `<table id="tabela-unidades" class="display">` com `<thead>` (colunas: ID, Sigla, Nome, Ações) e `<tbody>` vazio.
            *   O modal de cadastro/edição (Bootstrap) com campos de formulário para 'Sigla' e 'Nome', e um campo oculto para 'ID' (para edição).
            *   Um botão de 'Salvar' dentro do modal.
    *   **2.1.2.2. Estrutura JavaScript:**
        *   Criar a pasta `assets/js/unidade_medida/`.
        *   **`assets/js/unidade_medida/listar.js`:**
            *   Função `listarUnidades()`:
                *   Faz uma requisição AJAX (Fetch API ou jQuery.ajax) para `src/consultar/unidade_medida.php`.
                *   Em caso de sucesso, retorna os dados para serem usados pelo DataTable.
                *   Em caso de erro, exibe uma mensagem.
        *   **`assets/js/unidade_medida/tabela.js`:**
            *   Função `inicializarDataTable(data)`:
                *   Recebe os dados (`data`) da função `listarUnidades()`.
                *   Inicializa o `$('#tabela-unidades').DataTable()` com as configurações:
                    *   Definição das colunas (ID, Sigla, Nome, Ações).
                    *   Renderização da coluna 'Ações' com botões de "Editar" e "Deletar", passando o ID do registro para cada botão.
                    *   Configuração de internacionalização (Português Brasil).
                    *   Configuração de paginação, busca e ordenação.
        *   **`assets/js/unidade_medida/cadastrar.js`:**
            *   Função `enviarCadastro()`:
                *   Captura os valores dos campos 'Sigla' e 'Nome' do modal.
                *   Faz uma requisição AJAX (POST) para `src/cadastrar/unidade_medida.php`.
                *   Em caso de sucesso, recarrega a tabela (via `listar.js`) e fecha o modal.
                *   Em caso de erro, exibe uma mensagem de feedback.
        *   **`assets/js/unidade_medida/editar.js`:**
            *   Função `carregarParaEdicao(id)`:
                *   Faz uma requisição AJAX (GET) para `src/consultar/unidade_medida_por_id.php` com o `id`.
                *   Em caso de sucesso, preenche os campos do modal (incluindo o campo oculto 'ID') e abre o modal.
                *   Em caso de erro, exibe uma mensagem.
            *   Função `enviarEdicao()`:
                *   Captura os valores dos campos 'ID', 'Sigla' e 'Nome' do modal.
                *   Faz uma requisição AJAX (POST) para `src/editar/unidade_medida.php`.
                *   Em caso de sucesso, recarrega a tabela e fecha o modal.
                *   Em caso de erro, exibe uma mensagem.
        *   **`assets/js/unidade_medida/deletar.js`:**
            *   Função `confirmarDelecao(id)`:
                *   Exibe um modal de confirmação (ex: com SweetAlert2 ou modal Bootstrap simples).
                *   Se confirmado, chama `enviarDelecao(id)`.
            *   Função `enviarDelecao(id)`:
                *   Faz uma requisição AJAX (POST) para `src/deletar/unidade_medida.php` com o `id`.
                *   Em caso de sucesso, recarrega a tabela.
                *   Em caso de erro, exibe uma mensagem.
        *   **`assets/js/unidade_medida/index.js`:**
            *   Arquivo principal que será incluído na `unidade_medida.php`.
            *   Define `$(document).ready()` para:
                *   Chamar `listarUnidades()` para popular a tabela.
                *   Vincular eventos aos botões ('Nova Unidade', 'Salvar' do modal, 'Editar' e 'Deletar' da tabela).
                *   Lógica para resetar o formulário do modal ao fechar/abrir.

### 2.2. Módulo: Método de Pagamento

**Visão Geral:** Implementação completa do CRUD (Criar, Ler, Atualizar, Deletar) para a entidade `metodo_pagamento`.

*   **2.2.1. Back-end:**
    *   **2.2.1.1. Classe da Entidade `MetodoPagamento`:**
        *   Criar o arquivo `src/entidades/MetodoPagamento.php`.
        *   Definir a classe `MetodoPagamento` com as propriedades privadas `id`, `nome`, `banco`, `agencia`, `conta` e seus respectivos métodos `getter` e `setter`.
        *   Implementar o método estático `cadastrar(nome, banco, agencia, conta)`:
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `INSERT INTO metodo_pagamento (nome, banco, agencia, conta, criado_por, data_criacao)`.
            *   Executar a query, tratando possíveis erros e retornado um indicador de sucesso/falha.
        *   Implementar o método estático `listar()`:
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `SELECT id, nome, banco, agencia, conta FROM metodo_pagamento`. (Não tem `status_registro`)
            *   Executar a query e retornar um array de objetos `MetodoPagamento` ou um array associativo dos dados, adequado para consumo pelo DataTable.
        *   Implementar o método estático `buscarPorId(id)`:
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `SELECT id, nome, banco, agencia, conta FROM metodo_pagamento WHERE id = :id`.
            *   Executar a query e retornar um objeto `MetodoPagamento` ou `null` se não encontrado.
        *   Implementar o método estático `editar(id, nome, banco, agencia, conta)`:
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `UPDATE metodo_pagamento SET nome = :nome, banco = :banco, agencia = :agencia, conta = :conta, alterado_por = :alterado_por, data_alteracao = CURRENT_TIMESTAMP WHERE id = :id`.
            *   Executar a query, tratando erros e retornado um indicador de sucesso/falha.
        *   Implementar o método estático `deletar(id)`:
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `DELETE FROM metodo_pagamento WHERE id = :id`.
            *   Executar a query, tratando erros e retornado um indicador de sucesso/falha. (Atenção: essa tabela não tem exclusão lógica `status_registro`).

    *   **2.2.1.2. Endpoints AJAX (PHP):**
        *   **`src/cadastrar/metodo_pagamento.php`:**
            *   Verificar se a requisição é POST.
            *   Validar e sanitizar os dados de entrada (`nome`, `banco`, `agencia`, `conta`).
            *   Chamar `MetodoPagamento::cadastrar()`.
            *   Retornar uma resposta JSON com sucesso ou erro.
        *   **`src/consultar/metodo_pagamento.php`:**
            *   Chamar `MetodoPagamento::listar()`.
            *   Retornar uma resposta JSON formatada para o DataTables (objeto com `data` array).
        *   **`src/consultar/metodo_pagamento_por_id.php`:**
            *   Verificar se a requisição é GET e se um `id` válido foi fornecido.
            *   Chamar `MetodoPagamento::buscarPorId(id)`.
            *   Retornar uma resposta JSON com os dados do método de pagamento ou erro.
        *   **`src/editar/metodo_pagamento.php`:**
            *   Verificar se a requisição é POST.
            *   Validar e sanitizar os dados de entrada (`id`, `nome`, `banco`, `agencia`, `conta`).
            *   Chamar `MetodoPagamento::editar()`.
            *   Retornar uma resposta JSON com sucesso ou erro.
        *   **`src/deletar/metodo_pagamento.php`:**
            *   Verificar se a requisição é POST e se um `id` válido foi fornecido.
            *   Chamar `MetodoPagamento::deletar()`.
            *   Retornar uma resposta JSON com sucesso ou erro.

*   **2.2.2. Front-end:**
    *   **2.2.2.1. Página `metodo_pagamento.php`:**
        *   Criar o arquivo `metodo_pagamento.php`.
        *   Incluir `templates/header.php` e `templates/footer.php`.
        *   Estruturar o HTML para:
            *   Um título.
            *   Um botão "Novo Método de Pagamento".
            *   A tabela HTML `<table id="tabela-metodos-pagamento" class="display">` com `<thead>` (colunas: ID, Nome, Banco, Agência, Conta, Ações) e `<tbody>` vazio.
            *   O modal de cadastro/edição com campos de formulário para 'Nome', 'Banco', 'Agência' e 'Conta', e um campo oculto para 'ID'.
            *   Um botão de 'Salvar' dentro do modal.
    *   **2.2.2.2. Estrutura JavaScript:**
        *   Criar a pasta `assets/js/metodo_pagamento/`.
        *   **`assets/js/metodo_pagamento/listar.js`:**
            *   Função `listarMetodosPagamento()`: Faz requisição AJAX para `src/consultar/metodo_pagamento.php`.
        *   **`assets/js/metodo_pagamento/tabela.js`:**
            *   Função `inicializarDataTable(data)`: Inicializa o `$('#tabela-metodos-pagamento').DataTable()` com as colunas (ID, Nome, Banco, Agência, Conta, Ações) e renderiza botões de 'Editar' e 'Deletar'.
        *   **`assets/js/metodo_pagamento/cadastrar.js`:**
            *   Função `enviarCadastro()`: Captura dados do modal, envia via AJAX (POST) para `src/cadastrar/metodo_pagamento.php`.
        *   **`assets/js/metodo_pagamento/editar.js`:**
            *   Função `carregarParaEdicao(id)`: Faz requisição AJAX (GET) para `src/consultar/metodo_pagamento_por_id.php`, preenche o modal.
            *   Função `enviarEdicao()`: Captura dados do modal, envia via AJAX (POST) para `src/editar/metodo_pagamento.php`.
        *   **`assets/js/metodo_pagamento/deletar.js`:**
            *   Função `confirmarDelecao(id)`: Exibe confirmação.
            *   Função `enviarDelecao(id)`: Envia requisição AJAX (POST) para `src/deletar/metodo_pagamento.php`.
        *   **`assets/js/metodo_pagamento/index.js`:**
            *   Arquivo principal. Chama `listarMetodosPagamento()` e vincula eventos aos botões.

### 2.3. Módulo: Ambiente de Venda

**Visão Geral:** Implementação completa do CRUD (Criar, Ler, Atualizar, Deletar) para a entidade `ambiente_venda`.

*   **2.3.1. Back-end:**
    *   **2.3.1.1. Classe da Entidade `AmbienteVenda`:**
        *   Criar o arquivo `src/entidades/AmbienteVenda.php`.
        *   Definir a classe `AmbienteVenda` com as propriedades privadas `id`, `nome`, `descricao`, `taxa` e seus respectivos métodos `getter` e `setter`.
        *   Implementar o método estático `cadastrar(nome, descricao, taxa)`:
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `INSERT INTO ambiente_venda (nome, descricao, taxa, criado_por, data_criacao)`.
            *   Executar a query, tratando possíveis erros e retornado um indicador de sucesso/falha.
        *   Implementar o método estático `listar()`:
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `SELECT id, nome, descricao, taxa FROM ambiente_venda WHERE status_registro = 1`.
            *   Executar a query e retornar um array de objetos `AmbienteVenda` ou um array associativo dos dados, adequado para consumo pelo DataTable.
        *   Implementar o método estático `buscarPorId(id)`:
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `SELECT id, nome, descricao, taxa FROM ambiente_venda WHERE id = :id AND status_registro = 1`.
            *   Executar a query e retornar um objeto `AmbienteVenda` ou `null` se não encontrado.
        *   Implementar o método estático `editar(id, nome, descricao, taxa)`:
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `UPDATE ambiente_venda SET nome = :nome, descricao = :descricao, taxa = :taxa, alterado_por = :alterado_por, data_alteracao = CURRENT_TIMESTAMP WHERE id = :id`.
            *   Executar a query, tratando erros e retornado um indicador de sucesso/falha.
        *   Implementar o método estático `deletar(id)` (Exclusão Lógica):
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `UPDATE ambiente_venda SET status_registro = 0, alterado_por = :alterado_por, data_alteracao = CURRENT_TIMESTAMP WHERE id = :id`.
            *   Executar a query, tratando erros e retornado um indicador de sucesso/falha.

    *   **2.3.1.2. Endpoints AJAX (PHP):**
        *   **`src/cadastrar/ambiente_venda.php`:**
            *   Verificar se a requisição é POST.
            *   Validar e sanitizar os dados de entrada (`nome`, `descricao`, `taxa`).
            *   Chamar `AmbienteVenda::cadastrar()`.
            *   Retornar uma resposta JSON com sucesso ou erro.
        *   **`src/consultar/ambiente_venda.php`:**
            *   Chamar `AmbienteVenda::listar()`.
            *   Retornar uma resposta JSON formatada para o DataTables (objeto com `data` array).
        *   **`src/consultar/ambiente_venda_por_id.php`:**
            *   Verificar se a requisição é GET e se um `id` válido foi fornecido.
            *   Chamar `AmbienteVenda::buscarPorId(id)`.
            *   Retornar uma resposta JSON com os dados do ambiente de venda ou erro.
        *   **`src/editar/ambiente_venda.php`:**
            *   Verificar se a requisição é POST.
            *   Validar e sanitizar os dados de entrada (`id`, `nome`, `descricao`, `taxa`).
            *   Chamar `AmbienteVenda::editar()`.
            *   Retornar uma resposta JSON com sucesso ou erro.
        *   **`src/deletar/ambiente_venda.php`:**
            *   Verificar se a requisição é POST e se um `id` válido foi fornecido.
            *   Chamar `AmbienteVenda::deletar()`.
            *   Retornar uma resposta JSON com sucesso ou erro.

*   **2.3.2. Front-end:**
    *   **2.3.2.1. Página `ambiente_venda.php`:**
        *   Criar o arquivo `ambiente_venda.php`.
        *   Incluir `templates/header.php` e `templates/footer.php`.
        *   Estruturar o HTML para:
            *   Um título.
            *   Um botão "Novo Ambiente de Venda".
            *   A tabela HTML `<table id="tabela-ambientes-venda" class="display">` com `<thead>` (colunas: ID, Nome, Descrição, Taxa, Ações) e `<tbody>` vazio.
            *   O modal de cadastro/edição com campos de formulário para 'Nome', 'Descrição', 'Taxa' e um campo oculto para 'ID'.
            *   Um botão de 'Salvar' dentro do modal.
    *   **2.3.2.2. Estrutura JavaScript:**
        *   Criar a pasta `assets/js/ambiente_venda/`.
        *   **`assets/js/ambiente_venda/listar.js`:**
            *   Função `listarAmbientesVenda()`: Faz requisição AJAX para `src/consultar/ambiente_venda.php`.
        *   **`assets/js/ambiente_venda/tabela.js`:**
            *   Função `inicializarDataTable(data)`: Inicializa o `$('#tabela-ambientes-venda').DataTable()` com as colunas (ID, Nome, Descrição, Taxa, Ações) e renderiza botões de 'Editar' e 'Deletar'.
        *   **`assets/js/ambiente_venda/cadastrar.js`:**
            *   Função `enviarCadastro()`: Captura dados do modal, envia via AJAX (POST) para `src/cadastrar/ambiente_venda.php`.
        *   **`assets/js/ambiente_venda/editar.js`:**
            *   Função `carregarParaEdicao(id)`: Faz requisição AJAX (GET) para `src/consultar/ambiente_venda_por_id.php`, preenche o modal.
            *   Função `enviarEdicao()`: Captura dados do modal, envia via AJAX (POST) para `src/editar/ambiente_venda.php`.
        *   **`assets/js/ambiente_venda/deletar.js`:**
            *   Função `confirmarDelecao(id)`: Exibe confirmação.
            *   Função `enviarDelecao(id)`: Envia requisição AJAX (POST) para `src/deletar/ambiente_venda.php`.
        *   **`assets/js/ambiente_venda/index.js`:**
            *   Arquivo principal. Chama `listarAmbientesVenda()` e vincula eventos aos botões.

### 2.4. Módulo: Máquina de Venda

**Visão Geral:** Implementação completa do CRUD (Criar, Ler, Atualizar, Deletar) para a entidade `maquina_venda`.

*   **2.4.1. Back-end:**
    *   **2.4.1.1. Classe da Entidade `MaquinaVenda`:**
        *   Criar o arquivo `src/entidades/MaquinaVenda.php`.
        *   Definir a classe `MaquinaVenda` com as propriedades privadas `id`, `nome`, `descricao`, `taxa` e seus respectivos métodos `getter` e `setter`.
        *   Implementar o método estático `cadastrar(nome, descricao, taxa)`:
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `INSERT INTO maquina_venda (nome, descricao, taxa, criado_por, data_criacao)`.
            *   Executar a query, tratando possíveis erros e retornado um indicador de sucesso/falha.
        *   Implementar o método estático `listar()`:
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `SELECT id, nome, descricao, taxa FROM maquina_venda WHERE status_registro = 1`.
            *   Executar a query e retornar um array de objetos `MaquinaVenda` ou um array associativo dos dados, adequado para consumo pelo DataTable.
        *   Implementar o método estático `buscarPorId(id)`:
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `SELECT id, nome, descricao, taxa FROM maquina_venda WHERE id = :id AND status_registro = 1`.
            *   Executar a query e retornar um objeto `MaquinaVenda` ou `null` se não encontrado.
        *   Implementar o método estático `editar(id, nome, descricao, taxa)`:
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `UPDATE maquina_venda SET nome = :nome, descricao = :descricao, taxa = :taxa, alterado_por = :alterado_por, data_alteracao = CURRENT_TIMESTAMP WHERE id = :id`.
            *   Executar a query, tratando erros e retornado um indicador de sucesso/falha.
        *   Implementar o método estático `deletar(id)` (Exclusão Lógica):
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `UPDATE maquina_venda SET status_registro = 0, alterado_por = :alterado_por, data_alteracao = CURRENT_TIMESTAMP WHERE id = :id`.
            *   Executar a query, tratando erros e retornado um indicador de sucesso/falha.

    *   **2.4.1.2. Endpoints AJAX (PHP):**
        *   **`src/cadastrar/maquina_venda.php`:**
            *   Verificar se a requisição é POST.
            *   Validar e sanitizar os dados de entrada (`nome`, `descricao`, `taxa`).
            *   Chamar `MaquinaVenda::cadastrar()`.
            *   Retornar uma resposta JSON com sucesso ou erro.
        *   **`src/consultar/maquina_venda.php`:**
            *   Chamar `MaquinaVenda::listar()`.
            *   Retornar uma resposta JSON formatada para o DataTables (objeto com `data` array).
        *   **`src/consultar/maquina_venda_por_id.php`:**
            *   Verificar se a requisição é GET e se um `id` válido foi fornecido.
            *   Chamar `MaquinaVenda::buscarPorId(id)`.
            *   Retornar uma resposta JSON com os dados da máquina de venda ou erro.
        *   **`src/editar/maquina_venda.php`:**
            *   Verificar se a requisição é POST.
            *   Validar e sanitizar os dados de entrada (`id`, `nome`, `descricao`, `taxa`).
            *   Chamar `MaquinaVenda::editar()`.
            *   Retornar uma resposta JSON com sucesso ou erro.
        *   **`src/deletar/maquina_venda.php`:**
            *   Verificar se a requisição é POST e se um `id` válido foi fornecido.
            *   Chamar `MaquinaVenda::deletar()`.
            *   Retornar uma resposta JSON com sucesso ou erro.

*   **2.4.2. Front-end:**
    *   **2.4.2.1. Página `maquina_venda.php`:**
        *   Criar o arquivo `maquina_venda.php`.
        *   Incluir `templates/header.php` e `templates/footer.php`.
        *   Estruturar o HTML para:
            *   Um título.
            *   Um botão "Nova Máquina de Venda".
            *   A tabela HTML `<table id="tabela-maquinas-venda" class="display">` com `<thead>` (colunas: ID, Nome, Descrição, Taxa, Ações) e `<tbody>` vazio.
            *   O modal de cadastro/edição com campos de formulário para 'Nome', 'Descrição', 'Taxa' e um campo oculto para 'ID'.
            *   Um botão de 'Salvar' dentro do modal.
    *   **2.4.2.2. Estrutura JavaScript:**
        *   Criar a pasta `assets/js/maquina_venda/`.
        *   **`assets/js/maquina_venda/listar.js`:**
            *   Função `listarMaquinasVenda()`: Faz requisição AJAX para `src/consultar/maquina_venda.php`.
        *   **`assets/js/maquina_venda/tabela.js`:**
            *   Função `inicializarDataTable(data)`: Inicializa o `$('#tabela-maquinas-venda').DataTable()` com as colunas (ID, Nome, Descrição, Taxa, Ações) e renderiza botões de 'Editar' e 'Deletar'.
        *   **`assets/js/maquina_venda/cadastrar.js`:**
            *   Função `enviarCadastro()`: Captura dados do modal, envia via AJAX (POST) para `src/cadastrar/maquina_venda.php`.
        *   **`assets/js/maquina_venda/editar.js`:**
            *   Função `carregarParaEdicao(id)`: Faz requisição AJAX (GET) para `src/consultar/maquina_venda_por_id.php`, preenche o modal.
            *   Função `enviarEdicao()`: Captura dados do modal, envia via AJAX (POST) para `src/editar/maquina_venda.php`.
        *   **`assets/js/maquina_venda/deletar.js`:**
            *   Função `confirmarDelecao(id)`: Exibe confirmação.
            *   Função `enviarDelecao(id)`: Envia requisição AJAX (POST) para `src/deletar/maquina_venda.php`.
        *   **`assets/js/maquina_venda/index.js`:**
            *   Arquivo principal. Chama `listarMaquinasVenda()` e vincula eventos aos botões.

### 2.5. Módulo: Custo Mensal

**Visão Geral:** Implementação completa do CRUD (Criar, Ler, Atualizar, Deletar) para a entidade `custo_mensal`.

*   **2.5.1. Back-end:**
    *   **2.5.1.1. Classe da Entidade `CustoMensal`:**
        *   Criar o arquivo `src/entidades/CustoMensal.php`.
        *   Definir a classe `CustoMensal` com as propriedades privadas `id`, `status_registro`, `status_pagamento`, `tipo_custo`, `descricao`, `valor`, `data_pagamento`, `mes`, `ano` e seus respectivos métodos `getter` e `setter`.
        *   Implementar o método estático `cadastrar(status_registro, status_pagamento, tipo_custo, descricao, valor, data_pagamento, mes, ano)`:
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `INSERT INTO custo_mensal (status_registro, status_pagamento, tipo_custo, descricao, valor, data_pagamento, mes, ano, criado_por, data_criacao)`.
            *   Executar a query, tratando possíveis erros e retornado um indicador de sucesso/falha.
        *   Implementar o método estático `listar()`:
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `SELECT id, status_registro, status_pagamento, tipo_custo, descricao, valor, data_pagamento, mes, ano FROM custo_mensal WHERE status_registro = 1`.
            *   Executar a query e retornar um array de objetos `CustoMensal` ou um array associativo dos dados, adequado para consumo pelo DataTable.
        *   Implementar o método estático `buscarPorId(id)`:
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `SELECT id, status_registro, status_pagamento, tipo_custo, descricao, valor, data_pagamento, mes, ano FROM custo_mensal WHERE id = :id AND status_registro = 1`.
            *   Executar a query e retornar um objeto `CustoMensal` ou `null` se não encontrado.
        *   Implementar o método estático `editar(id, status_registro, status_pagamento, tipo_custo, descricao, valor, data_pagamento, mes, ano)`:
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `UPDATE custo_mensal SET status_registro = :status_registro, status_pagamento = :status_pagamento, tipo_custo = :tipo_custo, descricao = :descricao, valor = :valor, data_pagamento = :data_pagamento, mes = :mes, ano = :ano, alterado_por = :alterado_por, data_alteracao = CURRENT_TIMESTAMP WHERE id = :id`.
            *   Executar a query, tratando erros e retornado um indicador de sucesso/falha.
        *   Implementar o método estático `deletar(id)` (Exclusão Lógica):
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `UPDATE custo_mensal SET status_registro = 0, alterado_por = :alterado_por, data_alteracao = CURRENT_TIMESTAMP WHERE id = :id`.
            *   Executar a query, tratando erros e retornado um indicador de sucesso/falha.

    *   **2.5.1.2. Endpoints AJAX (PHP):**
        *   **`src/cadastrar/custo_mensal.php`:**
            *   Verificar se a requisição é POST.
            *   Validar e sanitizar os dados de entrada (`status_registro`, `status_pagamento`, `tipo_custo`, `descricao`, `valor`, `data_pagamento`, `mes`, `ano`).
            *   Chamar `CustoMensal::cadastrar()`.
            *   Retornar uma resposta JSON com sucesso ou erro.
        *   **`src/consultar/custo_mensal.php`:**
            *   Chamar `CustoMensal::listar()`.
            *   Retornar uma resposta JSON formatada para o DataTables (objeto com `data` array).
        *   **`src/consultar/custo_mensal_por_id.php`:**
            *   Verificar se a requisição é GET e se um `id` válido foi fornecido.
            *   Chamar `CustoMensal::buscarPorId(id)`.
            *   Retornar uma resposta JSON com os dados do custo mensal ou erro.
        *   **`src/editar/custo_mensal.php`:**
            *   Verificar se a requisição é POST.
            *   Validar e sanitizar os dados de entrada (`id`, `status_registro`, `status_pagamento`, `tipo_custo`, `descricao`, `valor`, `data_pagamento`, `mes`, `ano`).
            *   Chamar `CustoMensal::editar()`.
            *   Retornar uma resposta JSON com sucesso ou erro.
        *   **`src/deletar/custo_mensal.php`:**
            *   Verificar se a requisição é POST e se um `id` válido foi fornecido.
            *   Chamar `CustoMensal::deletar()`.
            *   Retornar uma resposta JSON com sucesso ou erro.

*   **2.5.2. Front-end:**
    *   **2.5.2.1. Página `custo_mensal.php`:**
        *   Criar o arquivo `custo_mensal.php`.
        *   Incluir `templates/header.php` e `templates/footer.php`.
        *   Estruturar o HTML para:
            *   Um título.
            *   Um botão "Novo Custo Mensal".
            *   A tabela HTML `<table id="tabela-custos-mensais" class="display">` com `<thead>` (colunas: ID, Status Registro, Status Pagamento, Tipo Custo, Descrição, Valor, Data Pagamento, Mês, Ano, Ações) e `<tbody>` vazio.
            *   O modal de cadastro/edição com campos de formulário para os respectivos campos, e um campo oculto para 'ID'.
            *   Um botão de 'Salvar' dentro do modal.
    *   **2.5.2.2. Estrutura JavaScript:**
        *   Criar a pasta `assets/js/custo_mensal/`.
        *   **`assets/js/custo_mensal/listar.js`:**
            *   Função `listarCustosMensais()`: Faz requisição AJAX para `src/consultar/custo_mensal.php`.
        *   **`assets/js/custo_mensal/tabela.js`:**
            *   Função `inicializarDataTable(data)`: Inicializa o `$('#tabela-custos-mensais').DataTable()` com as colunas e renderiza botões de 'Editar' e 'Deletar'.
        *   **`assets/js/custo_mensal/cadastrar.js`:**
            *   Função `enviarCadastro()`: Captura dados do modal, envia via AJAX (POST) para `src/cadastrar/custo_mensal.php`.
        *   **`assets/js/custo_mensal/editar.js`:**
            *   Função `carregarParaEdicao(id)`: Faz requisição AJAX (GET) para `src/consultar/custo_mensal_por_id.php`, preenche o modal.
            *   Função `enviarEdicao()`: Captura dados do modal, envia via AJAX (POST) para `src/editar/custo_mensal.php`.
        *   **`assets/js/custo_mensal/deletar.js`:**
            *   Função `confirmarDelecao(id)`: Exibe confirmação.
            *   Função `enviarDelecao(id)`: Envia requisição AJAX (POST) para `src/deletar/custo_mensal.php`.
        *   **`assets/js/custo_mensal/index.js`:**
            *   Arquivo principal. Chama `listarCustosMensais()` e vincula eventos aos botões.

### 2.6. Módulo: Usuário

**Visão Geral:** Implementação completa do CRUD (Criar, Ler, Atualizar, Deletar) para a entidade `usuario`.

*   **2.6.1. Back-end:**
    *   **2.6.1.1. Classe da Entidade `Usuario`:**
        *   Criar o arquivo `src/entidades/Usuario.php`.
        *   Definir a classe `Usuario` com as propriedades privadas `id`, `status_registro`, `nome`, `email`, `senha` e seus respectivos métodos `getter` e `setter`. (Adicionar `senha` e remover `criado_por` e `alterado_por` diretamente na classe, pois o usuário `criado_por` e `alterado_por` é o próprio usuário).
        *   Implementar o método estático `cadastrar(status_registro, nome, email, senha)`:
            *   Realizar a conexão via `database.php`.
            *   **Importante:** Hash da senha antes de inserir no banco de dados.
            *   Preparar a query SQL `INSERT INTO usuario (status_registro, nome, email, senha, criado_por, data_criacao)`. (Aqui, `criado_por` e `alterado_por` serão o `id` do próprio usuário que está sendo criado ou de um usuário administrador, dependendo da lógica de negócio. Por enquanto, podemos deixar como um valor padrão ou como o `id` do usuário logado se já houver um sistema de autenticação, que precisará ser definido).
            *   Executar a query, tratando possíveis erros e retornado um indicador de sucesso/falha.
        *   Implementar o método estático `listar()`:
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `SELECT id, status_registro, nome, email FROM usuario WHERE status_registro = 1`. (Não retornar a senha).
            *   Executar a query e retornar um array de objetos `Usuario` ou um array associativo dos dados.
        *   Implementar o método estático `buscarPorId(id)`:
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `SELECT id, status_registro, nome, email FROM usuario WHERE id = :id AND status_registro = 1`. (Não retornar a senha).
            *   Executar a query e retornar um objeto `Usuario` ou `null`.
        *   Implementar o método estático `editar(id, status_registro, nome, email, senha = null)`:
            *   Realizar a conexão via `database.php`.
            *   Se uma nova senha for fornecida, realizar o hash antes de atualizar.
            *   Preparar a query SQL `UPDATE usuario SET status_registro = :status_registro, nome = :nome, email = :email, senha = :senha, alterado_por = :alterado_por, data_alteracao = CURRENT_TIMESTAMP WHERE id = :id`.
            *   Executar a query, tratando erros e retornado um indicador de sucesso/falha.
        *   Implementar o método estático `deletar(id)` (Exclusão Lógica):
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `UPDATE usuario SET status_registro = 0, alterado_por = :alterado_por, data_alteracao = CURRENT_TIMESTAMP WHERE id = :id`.
            *   Executar a query, tratando erros e retornado um indicador de sucesso/falha.

    *   **2.6.1.2. Endpoints AJAX (PHP):**
        *   **`src/cadastrar/usuario.php`:**
            *   Verificar se a requisição é POST.
            *   Validar e sanitizar os dados de entrada (`status_registro`, `nome`, `email`, `senha`).
            *   Chamar `Usuario::cadastrar()`.
            *   Retornar uma resposta JSON com sucesso ou erro.
        *   **`src/consultar/usuario.php`:**
            *   Chamar `Usuario::listar()`.
            *   Retornar uma resposta JSON formatada para o DataTables.
        *   **`src/consultar/usuario_por_id.php`:**
            *   Verificar se a requisição é GET e se um `id` válido foi fornecido.
            *   Chamar `Usuario::buscarPorId(id)`.
            *   Retornar uma resposta JSON com os dados do usuário (sem a senha) ou erro.
        *   **`src/editar/usuario.php`:**
            *   Verificar se a requisição é POST.
            *   Validar e sanitizar os dados de entrada (`id`, `status_registro`, `nome`, `email`, `senha` opcional).
            *   Chamar `Usuario::editar()`.
            *   Retornar uma resposta JSON com sucesso ou erro.
        *   **`src/deletar/usuario.php`:**
            *   Verificar se a requisição é POST e se um `id` válido foi fornecido.
            *   Chamar `Usuario::deletar()`.
            *   Retornar uma resposta JSON com sucesso ou erro.

*   **2.6.2. Front-end:**
    *   **2.6.2.1. Página `usuario.php`:**
        *   Criar o arquivo `usuario.php`.
        *   Incluir `templates/header.php` e `templates/footer.php`.
        *   Estruturar o HTML para:
            *   Um título.
            *   Um botão "Novo Usuário".
            *   A tabela HTML `<table id="tabela-usuarios" class="display">` com `<thead>` (colunas: ID, Status, Nome, Email, Ações) e `<tbody>` vazio.
            *   O modal de cadastro/edição com campos de formulário para 'Nome', 'Email', 'Senha' (e 'Confirmação de Senha' para cadastro/edição de senha), 'Status Registro' (checkbox ou select), e um campo oculto para 'ID'.
            *   Um botão de 'Salvar' dentro do modal.
    *   **2.6.2.2. Estrutura JavaScript:**
        *   Criar a pasta `assets/js/usuario/`.
        *   **`assets/js/usuario/listar.js`:**
            *   Função `listarUsuarios()`: Faz requisição AJAX para `src/consultar/usuario.php`.
        *   **`assets/js/usuario/tabela.js`:**
            *   Função `inicializarDataTable(data)`: Inicializa o `$('#tabela-usuarios').DataTable()` com as colunas (ID, Status, Nome, Email, Ações) e renderiza botões de 'Editar' e 'Deletar'.
        *   **`assets/js/usuario/cadastrar.js`:**
            *   Função `enviarCadastro()`: Captura dados do modal, valida as senhas, envia via AJAX (POST) para `src/cadastrar/usuario.php`.
        *   **`assets/js/usuario/editar.js`:**
            *   Função `carregarParaEdicao(id)`: Faz requisição AJAX (GET) para `src/consultar/usuario_por_id.php`, preenche o modal (sem a senha).
            *   Função `enviarEdicao()`: Captura dados do modal, envia via AJAX (POST) para `src/editar/usuario.php`.
        *   **`assets/js/usuario/deletar.js`:**
            *   Função `confirmarDelecao(id)`: Exibe confirmação.
            *   Função `enviarDelecao(id)`: Envia requisição AJAX (POST) para `src/deletar/usuario.php`.
        *   **`assets/js/usuario/index.js`:**
            *   Arquivo principal. Chama `listarUsuarios()` e vincula eventos aos botões.

### 2.7. Módulo: Fornecedor

**Visão Geral:** Implementação completa do CRUD (Criar, Ler, Atualizar, Deletar) para a entidade `fornecedor`.

*   **2.7.1. Back-end:**
    *   **2.7.1.1. Classe da Entidade `Fornecedor`:**
        *   Criar o arquivo `src/entidades/Fornecedor.php`.
        *   Definir a classe `Fornecedor` com as propriedades privadas `id`, `status_registro`, `nome`, `cnpj_cpf`, `email`, `telefone`, `endereco` e seus respectivos métodos `getter` e `setter`.
        *   Implementar o método estático `cadastrar(status_registro, nome, cnpj_cpf, email, telefone, endereco)`:
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `INSERT INTO fornecedor (status_registro, nome, cnpj_cpf, email, telefone, endereco, criado_por, data_criacao)`.
            *   Executar a query, tratando possíveis erros e retornado um indicador de sucesso/falha.
        *   Implementar o método estático `listar()`:
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `SELECT id, status_registro, nome, cnpj_cpf, email, telefone, endereco FROM fornecedor WHERE status_registro = 1`.
            *   Executar a query e retornar um array de objetos `Fornecedor` ou um array associativo dos dados, adequado para consumo pelo DataTable.
        *   Implementar o método estático `buscarPorId(id)`:
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `SELECT id, status_registro, nome, cnpj_cpf, email, telefone, endereco FROM fornecedor WHERE id = :id AND status_registro = 1`.
            *   Executar a query e retornar um objeto `Fornecedor` ou `null`.
        *   Implementar o método estático `editar(id, status_registro, nome, cnpj_cpf, email, telefone, endereco)`:
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `UPDATE fornecedor SET status_registro = :status_registro, nome = :nome, cnpj_cpf = :cnpj_cpf, email = :email, telefone = :telefone, endereco = :endereco, alterado_por = :alterado_por, data_alteracao = CURRENT_TIMESTAMP WHERE id = :id`.
            *   Executar a query, tratando erros e retornado um indicador de sucesso/falha.
        *   Implementar o método estático `deletar(id)` (Exclusão Lógica):
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `UPDATE fornecedor SET status_registro = 0, alterado_por = :alterado_por, data_alteracao = CURRENT_TIMESTAMP WHERE id = :id`.
            *   Executar a query, tratando erros e retornado um indicador de sucesso/falha.

    *   **2.7.1.2. Endpoints AJAX (PHP):**
        *   **`src/cadastrar/fornecedor.php`:**
            *   Verificar se a requisição é POST.
            *   Validar e sanitizar os dados de entrada (`status_registro`, `nome`, `cnpj_cpf`, `email`, `telefone`, `endereco`).
            *   Chamar `Fornecedor::cadastrar()`.
            *   Retornar uma resposta JSON com sucesso ou erro.
        *   **`src/consultar/fornecedor.php`:**
            *   Chamar `Fornecedor::listar()`.
            *   Retornar uma resposta JSON formatada para o DataTables.
        *   **`src/consultar/fornecedor_por_id.php`:**
            *   Verificar se a requisição é GET e se um `id` válido foi fornecido.
            *   Chamar `Fornecedor::buscarPorId(id)`.
            *   Retornar uma resposta JSON com os dados do fornecedor ou erro.
        *   **`src/editar/fornecedor.php`:**
            *   Verificar se a requisição é POST.
            *   Validar e sanitizar os dados de entrada (`id`, `status_registro`, `nome`, `cnpj_cpf`, `email`, `telefone`, `endereco`).
            *   Chamar `Fornecedor::editar()`.
            *   Retornar uma resposta JSON com sucesso ou erro.
        *   **`src/deletar/fornecedor.php`:**
            *   Verificar se a requisição é POST e se um `id` válido foi fornecido.
            *   Chamar `Fornecedor::deletar()`.
            *   Retornar uma resposta JSON com sucesso ou erro.

*   **2.7.2. Front-end:**
    *   **2.7.2.1. Página `fornecedor.php`:**
        *   Criar o arquivo `fornecedor.php`.
        *   Incluir `templates/header.php` e `templates/footer.php`.
        *   Estruturar o HTML para:
            *   Um título.
            *   Um botão "Novo Fornecedor".
            *   A tabela HTML `<table id="tabela-fornecedores" class="display">` com `<thead>` (colunas: ID, Status, Nome, CNPJ/CPF, Email, Telefone, Endereço, Ações) e `<tbody>` vazio.
            *   O modal de cadastro/edição com campos de formulário para os respectivos campos, e um campo oculto para 'ID'.
            *   Um botão de 'Salvar' dentro do modal.
    *   **2.7.2.2. Estrutura JavaScript:**
        *   Criar a pasta `assets/js/fornecedor/`.
        *   **`assets/js/fornecedor/listar.js`:**
            *   Função `listarFornecedores()`: Faz requisição AJAX para `src/consultar/fornecedor.php`.
        *   **`assets/js/fornecedor/tabela.js`:**
            *   Função `inicializarDataTable(data)`: Inicializa o `$('#tabela-fornecedores').DataTable()` com as colunas e renderiza botões de 'Editar' e 'Deletar'.
        *   **`assets/js/fornecedor/cadastrar.js`:**
            *   Função `enviarCadastro()`: Captura dados do modal, envia via AJAX (POST) para `src/cadastrar/fornecedor.php`.
        *   **`assets/js/fornecedor/editar.js`:**
            *   Função `carregarParaEdicao(id)`: Faz requisição AJAX (GET) para `src/consultar/fornecedor_por_id.php`, preenche o modal.
            *   Função `enviarEdicao()`: Captura dados do modal, envia via AJAX (POST) para `src/editar/fornecedor.php`.
        *   **`assets/js/fornecedor/deletar.js`:**
            *   Função `confirmarDelecao(id)`: Exibe confirmação.
            *   Função `enviarDelecao(id)`: Envia requisição AJAX (POST) para `src/deletar/fornecedor.php`.
        *   **`assets/js/fornecedor/index.js`:**
            *   Arquivo principal. Chama `listarFornecedores()` e vincula eventos aos botões.

### 2.8. Módulo: Cliente

**Visão Geral:** Implementação completa do CRUD (Criar, Ler, Atualizar, Deletar) para a entidade `cliente`.

*   **2.8.1. Back-end:**
    *   **2.8.1.1. Classe da Entidade `Cliente`:**
        *   Criar o arquivo `src/entidades/Cliente.php`.
        *   Definir a classe `Cliente` com as propriedades privadas `id`, `status_registro`, `nome`, `telefone` e seus respectivos métodos `getter` e `setter`.
        *   Implementar o método estático `cadastrar(status_registro, nome, telefone)`:
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `INSERT INTO cliente (status_registro, nome, telefone, criado_por, data_criacao)`.
            *   Executar a query, tratando possíveis erros e retornado um indicador de sucesso/falha.
        *   Implementar o método estático `listar()`:
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `SELECT id, status_registro, nome, telefone FROM cliente WHERE status_registro = 1`.
            *   Executar a query e retornar um array de objetos `Cliente` ou um array associativo dos dados, adequado para consumo pelo DataTable.
        *   Implementar o método estático `buscarPorId(id)`:
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `SELECT id, status_registro, nome, telefone FROM cliente WHERE id = :id AND status_registro = 1`.
            *   Executar a query e retornar um objeto `Cliente` ou `null`.
        *   Implementar o método estático `editar(id, status_registro, nome, telefone)`:
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `UPDATE cliente SET status_registro = :status_registro, nome = :nome, telefone = :telefone, alterado_por = :alterado_por, data_alteracao = CURRENT_TIMESTAMP WHERE id = :id`.
            *   Executar a query, tratando erros e retornado um indicador de sucesso/falha.
        *   Implementar o método estático `deletar(id)` (Exclusão Lógica):
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `UPDATE cliente SET status_registro = 0, alterado_por = :alterado_por, data_alteracao = CURRENT_TIMESTAMP WHERE id = :id`.
            *   Executar a query, tratando erros e retornado um indicador de sucesso/falha.

    *   **2.8.1.2. Endpoints AJAX (PHP):**
        *   **`src/cadastrar/cliente.php`:**
            *   Verificar se a requisição é POST.
            *   Validar e sanitizar os dados de entrada (`status_registro`, `nome`, `telefone`).
            *   Chamar `Cliente::cadastrar()`.
            *   Retornar uma resposta JSON com sucesso ou erro.
        *   **`src/consultar/cliente.php`:**
            *   Chamar `Cliente::listar()`.
            *   Retornar uma resposta JSON formatada para o DataTables.
        *   **`src/consultar/cliente_por_id.php`:**
            *   Verificar se a requisição é GET e se um `id` válido foi fornecido.
            *   Chamar `Cliente::buscarPorId(id)`.
            *   Retornar uma resposta JSON com os dados do cliente ou erro.
        *   **`src/editar/cliente.php`:**
            *   Verificar se a requisição é POST.
            *   Validar e sanitizar os dados de entrada (`id`, `status_registro`, `nome`, `telefone`).
            *   Chamar `Cliente::editar()`.
            *   Retornar uma resposta JSON com sucesso ou erro.
        *   **`src/deletar/cliente.php`:**
            *   Verificar se a requisição é POST e se um `id` válido foi fornecido.
            *   Chamar `Cliente::deletar()`.
            *   Retornar uma resposta JSON com sucesso ou erro.

*   **2.8.2. Front-end:**
    *   **2.8.2.1. Página `cliente.php`:**
        *   Criar o arquivo `cliente.php`.
        *   Incluir `templates/header.php` e `templates/footer.php`.
        *   Estruturar o HTML para:
            *   Um título.
            *   Um botão "Novo Cliente".
            *   A tabela HTML `<table id="tabela-clientes" class="display">` com `<thead>` (colunas: ID, Status, Nome, Telefone, Ações) e `<tbody>` vazio.
            *   O modal de cadastro/edição com campos de formulário para os respectivos campos, e um campo oculto para 'ID'.
            *   Um botão de 'Salvar' dentro do modal.
    *   **2.8.2.2. Estrutura JavaScript:**
        *   Criar a pasta `assets/js/cliente/`.
        *   **`assets/js/cliente/listar.js`:**
            *   Função `listarClientes()`: Faz requisição AJAX para `src/consultar/cliente.php`.
        *   **`assets/js/cliente/tabela.js`:**
            *   Função `inicializarDataTable(data)`: Inicializa o `$('#tabela-clientes').DataTable()` com as colunas e renderiza botões de 'Editar' e 'Deletar'.
        *   **`assets/js/cliente/cadastrar.js`:**
            *   Função `enviarCadastro()`: Captura dados do modal, envia via AJAX (POST) para `src/cadastrar/cliente.php`.
        *   **`assets/js/cliente/editar.js`:**
            *   Função `carregarParaEdicao(id)`: Faz requisição AJAX (GET) para `src/consultar/cliente_por_id.php`, preenche o modal.
            *   Função `enviarEdicao()`: Captura dados do modal, envia via AJAX (POST) para `src/editar/cliente.php`.
        *   **`assets/js/cliente/deletar.js`:**
            *   Função `confirmarDelecao(id)`: Exibe confirmação.
            *   Função `enviarDelecao(id)`: Envia requisição AJAX (POST) para `src/deletar/cliente.php`.
        *   **`assets/js/cliente/index.js`:**
            *   Arquivo principal. Chama `listarClientes()` e vincula eventos aos botões.

### 2.9. Módulo: Produto

**Visão Geral:** Implementação completa do CRUD (Criar, Ler, Atualizar, Deletar) para a entidade `produto`, com atenção às suas dependências.

*   **2.9.1. Back-end:**
    *   **2.9.1.1. Classe da Entidade `Produto`:**
        *   Criar o arquivo `src/entidades/Produto.php`.
        *   Definir a classe `Produto` com as propriedades privadas `id`, `status_registro`, `nome`, `descricao`, `preco_custo`, `preco_venda`, `quantidade_comprada`, `unidade_medida_id`, `fornecedor_id` e seus respectivos métodos `getter` e `setter`.
        *   Implementar o método estático `cadastrar(status_registro, nome, descricao, preco_custo, preco_venda, quantidade_comprada, unidade_medida_id, fornecedor_id)`:
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `INSERT INTO produto (status_registro, nome, descricao, preco_custo, preco_venda, quantidade_comprada, unidade_medida_id, fornecedor_id, criado_por, data_criacao)`.
            *   Executar a query, tratando possíveis erros e retornado um indicador de sucesso/falha.
        *   Implementar o método estático `listar()`:
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `SELECT p.id, p.status_registro, p.nome, p.descricao, p.preco_custo, p.preco_venda, p.quantidade_comprada, um.sigla as unidade_medida_sigla, f.nome as fornecedor_nome FROM produto p LEFT JOIN unidade_medida um ON p.unidade_medida_id = um.id LEFT JOIN fornecedor f ON p.fornecedor_id = f.id WHERE p.status_registro = 1`. (Usar JOINs para trazer nome da unidade de medida e fornecedor).
            *   Executar a query e retornar um array de objetos `Produto` ou um array associativo dos dados, adequado para consumo pelo DataTable.
        *   Implementar o método estático `buscarPorId(id)`:
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `SELECT id, status_registro, nome, descricao, preco_custo, preco_venda, quantidade_comprada, unidade_medida_id, fornecedor_id FROM produto WHERE id = :id AND status_registro = 1`.
            *   Executar a query e retornar um objeto `Produto` ou `null`.
        *   Implementar o método estático `editar(id, status_registro, nome, descricao, preco_custo, preco_venda, quantidade_comprada, unidade_medida_id, fornecedor_id)`:
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `UPDATE produto SET status_registro = :status_registro, nome = :nome, descricao = :descricao, preco_custo = :preco_custo, preco_venda = :preco_venda, quantidade_comprada = :quantidade_comprada, unidade_medida_id = :unidade_medida_id, fornecedor_id = :fornecedor_id, alterado_por = :alterado_por, data_alteracao = CURRENT_TIMESTAMP WHERE id = :id`.
            *   Executar a query, tratando erros e retornado um indicador de sucesso/falha.
        *   Implementar o método estático `deletar(id)` (Exclusão Lógica):
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `UPDATE produto SET status_registro = 0, alterado_por = :alterado_por, data_alteracao = CURRENT_TIMESTAMP WHERE id = :id`.
            *   Executar a query, tratando erros e retornado um indicador de sucesso/falha.

    *   **2.9.1.2. Endpoints AJAX (PHP):**
        *   **`src/cadastrar/produto.php`:**
            *   Verificar se a requisição é POST.
            *   Validar e sanitizar os dados de entrada.
            *   Chamar `Produto::cadastrar()`.
            *   Retornar uma resposta JSON com sucesso ou erro.
        *   **`src/consultar/produto.php`:**
            *   Chamar `Produto::listar()`.
            *   Retornar uma resposta JSON formatada para o DataTables.
        *   **`src/consultar/produto_por_id.php`:**
            *   Verificar se a requisição é GET e se um `id` válido foi fornecido.
            *   Chamar `Produto::buscarPorId(id)`.
            *   Retornar uma resposta JSON com os dados do produto ou erro.
        *   **`src/editar/produto.php`:**
            *   Verificar se a requisição é POST.
            *   Validar e sanitizar os dados de entrada.
            *   Chamar `Produto::editar()`.
            *   Retornar uma resposta JSON com sucesso ou erro.
        *   **`src/deletar/produto.php`:**
            *   Verificar se a requisição é POST e se um `id` válido foi fornecido.
            *   Chamar `Produto::deletar()`.
            *   Retornar uma resposta JSON com sucesso ou erro.
        *   **`src/consultar/unidades_medida_para_select.php`:** (NOVO)
            *   Chamar `UnidadeMedida::listar()` e retornar um JSON com `id` e `sigla/nome` para popular um `<select>`.
        *   **`src/consultar/fornecedores_para_select.php`:** (NOVO)
            *   Chamar `Fornecedor::listar()` e retornar um JSON com `id` e `nome` para popular um `<select>`.

*   **2.9.2. Front-end:**
    *   **2.9.2.1. Página `produto.php`:**
        *   Criar o arquivo `produto.php`.
        *   Incluir `templates/header.php` e `templates/footer.php`.
        *   Estruturar o HTML para:
            *   Um título.
            *   Um botão "Novo Produto".
            *   A tabela HTML `<table id="tabela-produtos" class="display">` com `<thead>` (colunas: ID, Status, Nome, Custo, Venda, Qtd, Unidade, Fornecedor, Ações) e `<tbody>` vazio.
            *   O modal de cadastro/edição com campos de formulário para os respectivos campos.
            *   **Campos Específicos:** `unidade_medida_id` e `fornecedor_id` deverão ser `<select>`s dinâmicos, populados via AJAX.
            *   Um campo oculto para 'ID'.
            *   Um botão de 'Salvar' dentro do modal.
    *   **2.9.2.2. Estrutura JavaScript:**
        *   Criar a pasta `assets/js/produto/`.
        *   **`assets/js/produto/listar.js`:**
            *   Função `listarProdutos()`: Faz requisição AJAX para `src/consultar/produto.php`.
        *   **`assets/js/produto/tabela.js`:**
            *   Função `inicializarDataTable(data)`: Inicializa o `$('#tabela-produtos').DataTable()` com as colunas e renderiza botões de 'Editar' e 'Deletar'.
        *   **`assets/js/produto/selects.js`:** (NOVO)
            *   Função `popularSelectUnidadesMedida()`: Faz AJAX para `src/consultar/unidades_medida_para_select.php` e popula o `<select>`.
            *   Função `popularSelectFornecedores()`: Faz AJAX para `src/consultar/fornecedores_para_select.php` e popula o `<select>`.
        *   **`assets/js/produto/cadastrar.js`:**
            *   Função `enviarCadastro()`: Captura dados do modal, envia via AJAX (POST) para `src/cadastrar/produto.php`.
        *   **`assets/js/produto/editar.js`:**
            *   Função `carregarParaEdicao(id)`: Faz requisição AJAX (GET) para `src/consultar/produto_por_id.php`, preenche o modal, e seleciona os valores corretos nos `<select>` de unidade de medida e fornecedor.
            *   Função `enviarEdicao()`: Captura dados do modal, envia via AJAX (POST) para `src/editar/produto.php`.
        *   **`assets/js/produto/deletar.js`:**
            *   Função `confirmarDelecao(id)`: Exibe confirmação.
            *   Função `enviarDelecao(id)`: Envia requisição AJAX (POST) para `src/deletar/produto.php`.
        *   **`assets/js/produto/index.js`:**
            *   Arquivo principal. Chama `listarProdutos()` e `popularSelects()` e vincula eventos aos botões.

### 2.10. Módulo: Receita

**Visão Geral:** Implementação completa do CRUD (Criar, Ler, Atualizar, Deletar) para a entidade `receita`, que representa a composição de um produto a partir de outros ingredientes.

*   **2.10.1. Back-end:**
    *   **2.10.1.1. Classe da Entidade `Receita`:**
        *   Criar o arquivo `src/entidades/Receita.php`.
        *   Definir a classe `Receita` com as propriedades privadas `id`, `status_registro`, `produto_principal_id`, `produto_ingrediente_id`, `quantidade_necessaria`, `unidade_medida_id` e seus respectivos métodos `getter` e `setter`.
        *   Implementar o método estático `cadastrar(status_registro, produto_principal_id, produto_ingrediente_id, quantidade_necessaria, unidade_medida_id)`:
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `INSERT INTO receita (status_registro, produto_principal_id, produto_ingrediente_id, quantidade_necessaria, unidade_medida_id, criado_por, data_criacao)`.
            *   Executar a query, tratando possíveis erros e retornado um indicador de sucesso/falha.
        *   Implementar o método estático `listar()`:
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `SELECT r.id, r.status_registro, pp.nome as produto_principal_nome, pi.nome as produto_ingrediente_nome, r.quantidade_necessaria, um.sigla as unidade_medida_sigla FROM receita r JOIN produto pp ON r.produto_principal_id = pp.id JOIN produto pi ON r.produto_ingrediente_id = pi.id JOIN unidade_medida um ON r.unidade_medida_id = um.id WHERE r.status_registro = 1`. (Usar JOINs para trazer os nomes dos produtos e unidade de medida).
            *   Executar a query e retornar um array de objetos `Receita` ou um array associativo dos dados, adequado para consumo pelo DataTable.
        *   Implementar o método estático `buscarPorId(id)`:
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `SELECT id, status_registro, produto_principal_id, produto_ingrediente_id, quantidade_necessaria, unidade_medida_id FROM receita WHERE id = :id AND status_registro = 1`.
            *   Executar a query e retornar um objeto `Receita` ou `null`.
        *   Implementar o método estático `editar(id, status_registro, produto_principal_id, produto_ingrediente_id, quantidade_necessaria, unidade_medida_id)`:
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `UPDATE receita SET status_registro = :status_registro, produto_principal_id = :produto_principal_id, produto_ingrediente_id = :produto_ingrediente_id, quantidade_necessaria = :quantidade_necessaria, unidade_medida_id = :unidade_medida_id, alterado_por = :alterado_por, data_alteracao = CURRENT_TIMESTAMP WHERE id = :id`.
            *   Executar a query, tratando erros e retornado um indicador de sucesso/falha.
        *   Implementar o método estático `deletar(id)` (Exclusão Lógica):
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `UPDATE receita SET status_registro = 0, alterado_por = :alterado_por, data_alteracao = CURRENT_TIMESTAMP WHERE id = :id`.
            *   Executar a query, tratando erros e retornado um indicador de sucesso/falha.

    *   **2.10.1.2. Endpoints AJAX (PHP):**
        *   **`src/cadastrar/receita.php`:**
            *   Verificar se a requisição é POST.
            *   Validar e sanitizar os dados de entrada.
            *   Chamar `Receita::cadastrar()`.
            *   Retornar uma resposta JSON com sucesso ou erro.
        *   **`src/consultar/receita.php`:**
            *   Chamar `Receita::listar()`.
            *   Retornar uma resposta JSON formatada para o DataTables.
        *   **`src/consultar/receita_por_id.php`:**
            *   Verificar se a requisição é GET e se um `id` válido foi fornecido.
            *   Chamar `Receita::buscarPorId(id)`.
            *   Retornar uma resposta JSON com os dados da receita ou erro.
        *   **`src/editar/receita.php`:**
            *   Verificar se a requisição é POST.
            *   Validar e sanitizar os dados de entrada.
            *   Chamar `Receita::editar()`.
            *   Retornar uma resposta JSON com sucesso ou erro.
        *   **`src/deletar/receita.php`:**
            *   Verificar se a requisição é POST e se um `id` válido foi fornecido.
            *   Chamar `Receita::deletar()`.
            *   Retornar uma resposta JSON com sucesso ou erro.
        *   **`src/consultar/produtos_para_select.php`:** (NOVO, para principal e ingrediente)
            *   Chamar `Produto::listar()` e retornar um JSON com `id` e `nome` para popular os `<select>`s de produtos.
        *   **`src/consultar/unidades_medida_para_select.php`:** (Reutilizar)
            *   Chamar `UnidadeMedida::listar()` e retornar um JSON com `id` e `sigla/nome`.

*   **2.10.2. Front-end:**
    *   **2.10.2.1. Página `receita.php`:**
        *   Criar o arquivo `receita.php`.
        *   Incluir `templates/header.php` e `templates/footer.php`.
        *   Estruturar o HTML para:
            *   Um título.
            *   Um botão "Nova Receita".
            *   A tabela HTML `<table id="tabela-receitas" class="display">` com `<thead>` (colunas: ID, Status, Produto Principal, Ingrediente, Quantidade Necessária, Unidade Medida, Ações) e `<tbody>` vazio.
            *   O modal de cadastro/edição com campos de formulário para os respectivos campos.
            *   **Campos Específicos:** `produto_principal_id`, `produto_ingrediente_id` e `unidade_medida_id` deverão ser `<select>`s dinâmicos.
            *   Um campo oculto para 'ID'.
            *   Um botão de 'Salvar' dentro do modal.
    *   **2.10.2.2. Estrutura JavaScript:**
        *   Criar a pasta `assets/js/receita/`.
        *   **`assets/js/receita/listar.js`:**
            *   Função `listarReceitas()`: Faz requisição AJAX para `src/consultar/receita.php`.
        *   **`assets/js/receita/tabela.js`:**
            *   Função `inicializarDataTable(data)`: Inicializa o `$('#tabela-receitas').DataTable()` com as colunas e renderiza botões de 'Editar' e 'Deletar'.
        *   **`assets/js/receita/selects.js`:** (NOVO)
            *   Função `popularSelectProdutos()`: Faz AJAX para `src/consultar/produtos_para_select.php` e popula os `<select>` de produtos.
            *   Função `popularSelectUnidadesMedida()`: Faz AJAX para `src/consultar/unidades_medida_para_select.php` e popula o `<select>`.
        *   **`assets/js/receita/cadastrar.js`:**
            *   Função `enviarCadastro()`: Captura dados do modal, envia via AJAX (POST) para `src/cadastrar/receita.php`.
        *   **`assets/js/receita/editar.js`:**
            *   Função `carregarParaEdicao(id)`: Faz requisição AJAX (GET) para `src/consultar/receita_por_id.php`, preenche o modal, e seleciona os valores corretos nos `<select>`.
            *   Função `enviarEdicao()`: Captura dados do modal, envia via AJAX (POST) para `src/editar/receita.php`.
        *   **`assets/js/receita/deletar.js`:**
            *   Função `confirmarDelecao(id)`: Exibe confirmação.
            *   Função `enviarDelecao(id)`: Envia requisição AJAX (POST) para `src/deletar/receita.php`.
        *   **`assets/js/receita/index.js`:**
            *   Arquivo principal. Chama `listarReceitas()` e `popularSelects()` e vincula eventos aos botões.

### 2.11. Módulo: Venda

**Visão Geral:** Implementação completa do CRUD (Criar, Ler, Atualizar, Deletar) para a entidade `venda`. Esta é uma entidade central que depende de `cliente`, `usuario` (vendedor), `metodo_pagamento`, `ambiente_venda` e está ligada a `item_venda`.

*   **2.11.1. Back-end:**
    *   **2.11.1.1. Classe da Entidade `Venda`:**
        *   Criar o arquivo `src/entidades/Venda.php`.
        *   Definir a classe `Venda` com as propriedades privadas `id`, `cliente_id`, `vendedor_id`, `data_venda`, `valor_total`, `metodo_pagamento_id`, `ambiente_venda_id` e seus respectivos métodos `getter` e `setter`.
        *   Implementar o método estático `cadastrar(cliente_id, vendedor_id, data_venda, valor_total, metodo_pagamento_id, ambiente_venda_id)`:
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `INSERT INTO venda (cliente_id, vendedor_id, data_venda, valor_total, metodo_pagamento_id, ambiente_venda_id, criado_por, data_criacao)`.
            *   Executar a query, tratando possíveis erros e retornado o ID da venda inserida.
        *   Implementar o método estático `listar()`:
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL com JOINs para trazer os nomes de `cliente`, `usuario` (vendedor), `metodo_pagamento` e `ambiente_venda`.
            *   `SELECT v.id, c.nome AS cliente_nome, u.nome AS vendedor_nome, v.data_venda, v.valor_total, mp.nome AS metodo_pagamento_nome, av.nome AS ambiente_venda_nome FROM venda v JOIN cliente c ON v.cliente_id = c.id JOIN usuario u ON v.vendedor_id = u.id JOIN metodo_pagamento mp ON v.metodo_pagamento_id = mp.id JOIN ambiente_venda av ON v.ambiente_venda_id = av.id`.
            *   Executar a query e retornar um array de objetos `Venda` ou um array associativo dos dados, adequado para consumo pelo DataTable.
        *   Implementar o método estático `buscarPorId(id)`:
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `SELECT id, cliente_id, vendedor_id, data_venda, valor_total, metodo_pagamento_id, ambiente_venda_id FROM venda WHERE id = :id`.
            *   Executar a query e retornar um objeto `Venda` ou `null`.
        *   Implementar o método estático `editar(id, cliente_id, vendedor_id, data_venda, valor_total, metodo_pagamento_id, ambiente_venda_id)`:
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `UPDATE venda SET cliente_id = :cliente_id, vendedor_id = :vendedor_id, data_venda = :data_venda, valor_total = :valor_total, metodo_pagamento_id = :metodo_pagamento_id, ambiente_venda_id = :ambiente_venda_id, alterado_por = :alterado_por, data_alteracao = CURRENT_TIMESTAMP WHERE id = :id`.
            *   Executar a query, tratando erros e retornado um indicador de sucesso/falha.
        *   Implementar o método estático `deletar(id)`:
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `DELETE FROM venda WHERE id = :id`. (Atenção: A deleção de uma venda pode ter implicações no estoque e em `item_venda`. Considerar se é exclusão física ou lógica para `venda` e como tratar `item_venda`).
            *   Executar a query, tratando erros e retornado um indicador de sucesso/falha.

    *   **2.11.1.2. Endpoints AJAX (PHP):**
        *   **`src/cadastrar/venda.php`:**
            *   Verificar se a requisição é POST.
            *   Validar e sanitizar os dados de entrada (`cliente_id`, `vendedor_id`, `data_venda`, `valor_total`, `metodo_pagamento_id`, `ambiente_venda_id`).
            *   Chamar `Venda::cadastrar()`.
            *   Retornar uma resposta JSON com sucesso ou erro (incluindo o ID da nova venda para `item_venda`).
        *   **`src/consultar/venda.php`:**
            *   Chamar `Venda::listar()`.
            *   Retornar uma resposta JSON formatada para o DataTables.
        *   **`src/consultar/venda_por_id.php`:**
            *   Verificar se a requisição é GET e se um `id` válido foi fornecido.
            *   Chamar `Venda::buscarPorId(id)`.
            *   Retornar uma resposta JSON com os dados da venda ou erro.
        *   **`src/editar/venda.php`:**
            *   Verificar se a requisição é POST.
            *   Validar e sanitizar os dados de entrada (`id`, `cliente_id`, `vendedor_id`, `data_venda`, `valor_total`, `metodo_pagamento_id`, `ambiente_venda_id`).
            *   Chamar `Venda::editar()`.
            *   Retornar uma resposta JSON com sucesso ou erro.
        *   **`src/deletar/venda.php`:**
            *   Verificar se a requisição é POST e se um `id` válido foi fornecido.
            *   Chamar `Venda::deletar()`.
            *   Retornar uma resposta JSON com sucesso ou erro.
        *   **`src/consultar/clientes_para_select.php`:** (NOVO/Reutilizar)
            *   Chamar `Cliente::listar()` e retornar um JSON com `id` e `nome`.
        *   **`src/consultar/vendedores_para_select.php`:** (NOVO/Reutilizar)
            *   Chamar `Usuario::listar()` (filtrando por tipo 'vendedor' se houver) e retornar um JSON com `id` e `nome`.
        *   **`src/consultar/metodos_pagamento_para_select.php`:** (Reutilizar)
            *   Chamar `MetodoPagamento::listar()` e retornar um JSON com `id` e `nome`.
        *   **`src/consultar/ambientes_venda_para_select.php`:** (Reutilizar)
            *   Chamar `AmbienteVenda::listar()` e retornar um JSON com `id` e `nome`.

*   **2.11.2. Front-end:**
    *   **2.11.2.1. Página `venda.php`:**
        *   Criar o arquivo `venda.php`.
        *   Incluir `templates/header.php` e `templates/footer.php`.
        *   Estruturar o HTML para:
            *   Um título.
            *   Um botão "Nova Venda".
            *   A tabela HTML `<table id="tabela-vendas" class="display">` com `<thead>` (colunas: ID, Cliente, Vendedor, Data Venda, Valor Total, Método Pgto., Ambiente Venda, Ações) e `<tbody>` vazio.
            *   O modal de cadastro/edição com campos de formulário para os respectivos campos.
            *   **Campos Específicos:** `cliente_id`, `vendedor_id`, `metodo_pagamento_id`, `ambiente_venda_id` deverão ser `<select>`s dinâmicos. `data_venda` com um datepicker. `valor_total` pode ser calculado ou inserido.
            *   Um campo oculto para 'ID'.
            *   Um botão de 'Salvar' dentro do modal.
    *   **2.11.2.2. Estrutura JavaScript:**
        *   Criar a pasta `assets/js/venda/`.
        *   **`assets/js/venda/listar.js`:**
            *   Função `listarVendas()`: Faz requisição AJAX para `src/consultar/venda.php`.
        *   **`assets/js/venda/tabela.js`:**
            *   Função `inicializarDataTable(data)`: Inicializa o `$('#tabela-vendas').DataTable()` com as colunas e renderiza botões de 'Editar' e 'Deletar'.
        *   **`assets/js/venda/selects.js`:**
            *   Função `popularSelectClientes()`: Faz AJAX para `src/consultar/clientes_para_select.php`.
            *   Função `popularSelectVendedores()`: Faz AJAX para `src/consultar/vendedores_para_select.php`.
            *   Função `popularSelectMetodosPagamento()`: Faz AJAX para `src/consultar/metodos_pagamento_para_select.php`.
            *   Função `popularSelectAmbientesVenda()`: Faz AJAX para `src/consultar/ambientes_venda_para_select.php`.
        *   **`assets/js/venda/cadastrar.js`:**
            *   Função `enviarCadastro()`: Captura dados do modal, envia via AJAX (POST) para `src/cadastrar/venda.php`.
        *   **`assets/js/venda/editar.js`:**
            *   Função `carregarParaEdicao(id)`: Faz requisição AJAX (GET) para `src/consultar/venda_por_id.php`, preenche o modal, e seleciona os valores corretos nos `<select>`.
            *   Função `enviarEdicao()`: Captura dados do modal, envia via AJAX (POST) para `src/editar/venda.php`.
        *   **`assets/js/venda/deletar.js`:**
            *   Função `confirmarDelecao(id)`: Exibe confirmação.
            *   Função `enviarDelecao(id)`: Envia requisição AJAX (POST) para `src/deletar/venda.php`.
        *   **`assets/js/venda/index.js`:**
            *   Arquivo principal. Chama `listarVendas()` e `popularSelects()` e vincula eventos aos botões.

### 2.12. Módulo: Item de Venda

**Visão Geral:** Implementação do CRUD para a entidade `item_venda`, que registra os produtos vendidos em uma `venda`.

*   **2.12.1. Back-end:**
    *   **2.12.1.1. Classe da Entidade `ItemVenda`:**
        *   Criar o arquivo `src/entidades/ItemVenda.php`.
        *   Definir a classe `ItemVenda` com as propriedades privadas `id`, `status_registro`, `venda_id`, `produto_id`, `quantidade`, `preco_venda` e seus respectivos métodos `getter` e `setter`.
        *   Implementar o método estático `cadastrar(venda_id, produto_id, quantidade, preco_venda)`:
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `INSERT INTO item_venda (venda_id, produto_id, quantidade, preco_venda, criado_por, data_criacao)`.
            *   Executar a query, tratando possíveis erros e retornado um indicador de sucesso/falha.
            *   **Considerar:** Diminuir a `quantidade_comprada` do `produto` correspondente, ajustando o estoque.
        *   Implementar o método estático `listarPorVenda(venda_id)`:
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL com JOIN para trazer o nome do produto: `SELECT iv.id, iv.venda_id, p.nome as produto_nome, iv.quantidade, iv.preco_venda FROM item_venda iv JOIN produto p ON iv.produto_id = p.id WHERE iv.venda_id = :venda_id AND iv.status_registro = 1`.
            *   Executar a query e retornar um array de objetos `ItemVenda` ou um array associativo dos dados.
        *   Implementar o método estático `buscarPorId(id)`:
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `SELECT id, venda_id, produto_id, quantidade, preco_venda FROM item_venda WHERE id = :id AND status_registro = 1`.
            *   Executar a query e retornar um objeto `ItemVenda` ou `null`.
        *   Implementar o método estático `editar(id, venda_id, produto_id, quantidade, preco_venda)`:
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `UPDATE item_venda SET venda_id = :venda_id, produto_id = :produto_id, quantidade = :quantidade, preco_venda = :preco_venda, alterado_por = :alterado_por, data_alteracao = CURRENT_TIMESTAMP WHERE id = :id`.
            *   Executar a query, tratando erros e retornado um indicador de sucesso/falha.
            *   **Considerar:** Ajustar o estoque do produto original e do novo produto (se houver mudança).
        *   Implementar o método estático `deletar(id)` (Exclusão Lógica):
            *   Realizar a conexão via `database.php`.
            *   Preparar a query SQL `UPDATE item_venda SET status_registro = 0, alterado_por = :alterado_por, data_alteracao = CURRENT_TIMESTAMP WHERE id = :id`.
            *   Executar a query, tratando erros e retornado um indicador de sucesso/falha.
            *   **Considerar:** Reverter a quantidade do produto para o estoque.

    *   **2.12.1.2. Endpoints AJAX (PHP):**
        *   **`src/cadastrar/item_venda.php`:**
            *   Verificar se a requisição é POST.
            *   Validar e sanitizar os dados de entrada (`venda_id`, `produto_id`, `quantidade`, `preco_venda`).
            *   Chamar `ItemVenda::cadastrar()`.
            *   Retornar uma resposta JSON com sucesso ou erro.
        *   **`src/consultar/itens_venda_por_venda.php`:**
            *   Verificar se um `venda_id` válido foi fornecido.
            *   Chamar `ItemVenda::listarPorVenda(venda_id)`.
            *   Retornar uma resposta JSON formatada para o DataTables (para uma sub-tabela ou modal de detalhes da venda).
        *   **`src/consultar/item_venda_por_id.php`:**
            *   Verificar se a requisição é GET e se um `id` válido foi fornecido.
            *   Chamar `ItemVenda::buscarPorId(id)`.
            *   Retornar uma resposta JSON com os dados do item de venda ou erro.
        *   **`src/editar/item_venda.php`:**
            *   Verificar se a requisição é POST.
            *   Validar e sanitizar os dados de entrada (`id`, `venda_id`, `produto_id`, `quantidade`, `preco_venda`).
            *   Chamar `ItemVenda::editar()`.
            *   Retornar uma resposta JSON com sucesso ou erro.
        *   **`src/deletar/item_venda.php`:**
            *   Verificar se a requisição é POST e se um `id` válido foi fornecido.
            *   Chamar `ItemVenda::deletar()`.
            *   Retornar uma resposta JSON com sucesso ou erro.
        *   **`src/consultar/produtos_para_select.php`:** (Reutilizar)
            *   Chamar `Produto::listar()` e retornar um JSON com `id` e `nome` para popular um `<select>`.

*   **2.12.2. Front-end:**
    *   **2.12.2.1. Integração na Página `venda.php` (ou em uma nova `item_venda.php` se houver necessidade de gerenciar itens separadamente):**
        *   **Abordagem 1 (Integrado na Venda):**
            *   No modal de cadastro/edição de `venda`, adicionar uma seção para 'Itens da Venda'.
            *   Essa seção pode conter uma tabela menor para listar os itens já adicionados e um formulário para adicionar novos itens.
            *   Botões para "Adicionar Item", "Editar Item", "Remover Item".
            *   **`assets/js/item_venda/` (Nesta abordagem, os JS podem ser integrados ao `assets/js/venda/`):**
                *   Funções para `listarItensPorVenda(venda_id)`, `adicionarItem(venda_id, produto_id, quantidade, preco_venda)`, `editarItem(id, ...)` e `deletarItem(id)`.
                *   População dinâmica de `<select>` de produtos.
                *   Lógica para calcular o `valor_total` da venda dinamicamente com base nos itens.
        *   **Abordagem 2 (Página Separada):**
            *   Criar o arquivo `item_venda.php` para gerenciar itens de venda individualmente, com uma tabela e modal próprios.
            *   Incluir `templates/header.php` e `templates/footer.php`.
            *   Estruturar o HTML para:
                *   Um título.
                *   Um botão "Novo Item de Venda".
                *   Tabela HTML `<table id="tabela-itens-venda" class="display">` (colunas: ID, Venda, Produto, Quantidade, Preço Venda, Ações).
                *   Modal de cadastro/edição com campos para 'Venda (Select ou input oculto)', 'Produto (Select)', 'Quantidade', 'Preço Venda'.
            *   **`assets/js/item_venda/`:**
                *   Arquivos `listar.js`, `tabela.js`, `cadastrar.js`, `editar.js`, `deletar.js`, `index.js` seguindo o padrão das outras entidades, com funções específicas para `item_venda`.
                *   Funções para popular `<select>` de produtos e vendas.

    *   **Decisão:** Para simplificar e manter o foco no fluxo de venda, a **Abordagem 1 (Integrado na Venda)** é mais recomendada. Os arquivos JS podem ser incorporados ao `assets/js/venda/` ou ter um `index.js` separado dentro de `assets/js/item_venda/` que é carregado apenas quando o modal de itens de venda é aberto ou na página de vendas.

---

## 3. Módulos (Painéis)

### 3.1. Painel: Simulação de Preço de Custo x Preço de Venda

**Visão Geral:** Um painel interativo para simular margem de lucro e valor de lucro com base nos preços de custo e venda dos produtos, considerando taxas de ambiente de venda e método de pagamento.

*   **3.1.1. Back-end:**
    *   **3.1.1.1. Classe de Utilitário/Lógica de Negócio `SimuladorPreco`:**
        *   Criar o arquivo `src/core/SimuladorPreco.php`.
        *   Implementar o método estático `calcularMargem(preco_custo, preco_venda, taxa_ambiente = 0, taxa_metodo_pagamento = 0)`:
            *   Recebe preço de custo, preço de venda e taxas.
            *   Calcula o custo final (custo + taxas).
            *   Calcula a margem de lucro (percentual e valor absoluto).
            *   Retorna um array com todos os resultados.
        *   Implementar métodos auxiliares para buscar taxas por ID (ambiente de venda, método de pagamento).
    *   **3.1.1.2. Endpoints AJAX (PHP):**
        *   **`src/consultar/produtos_para_simulacao.php`:**
            *   Chamar `Produto::listar()` para retornar `id`, `nome`, `preco_custo`.
        *   **`src/consultar/taxas_ambiente_venda.php`:**
            *   Chamar `AmbienteVenda::listar()` para retornar `id`, `nome`, `taxa`.
        *   **`src/consultar/taxas_metodo_pagamento.php`:**
            *   Chamar `MetodoPagamento::listar()` para retornar `id`, `nome`, `taxa`.
        *   **`src/consultar/simular_preco.php`:**
            *   Recebe `produto_id`, `preco_venda_sugerido`, `ambiente_venda_id`, `metodo_pagamento_id`.
            *   Busca o `preco_custo` do produto, as taxas.
            *   Chama `SimuladorPreco::calcularMargem()`.
            *   Retorna um JSON com os resultados da simulação.

*   **3.1.2. Front-end:**
    *   **3.1.2.1. Página `simulador_preco.php`:**
        *   Criar o arquivo `simulador_preco.php`.
        *   Incluir `templates/header.php` e `templates/footer.php`.
        *   Estruturar o HTML para:
            *   Um formulário interativo com:
                *   `<select>` para seleção do Produto (populado via AJAX).
                *   Campo `input` para `Preço de Venda Sugerido`.
                *   `<select>` para seleção do Ambiente de Venda (populado via AJAX).
                *   `<select>` para seleção do Método de Pagamento (populado via AJAX).
                *   Área para exibir resultados: `Preço de Custo`, `Margem de Lucro (%)`, `Valor de Lucro (R$)`, `Preço Sugerido Final`.
    *   **3.1.2.2. Estrutura JavaScript:**
        *   Criar a pasta `assets/js/simulador_preco/`.
        *   **`assets/js/simulador_preco/index.js`:**
            *   Função `popularSelects()`: Chama AJAX para popular produtos, ambientes de venda e métodos de pagamento.
            *   Função `simularPreco()`:
                *   Captura os valores dos campos do formulário.
                *   Faz requisição AJAX (POST) para `src/consultar/simular_preco.php`.
                *   Atualiza a área de resultados na página com os dados recebidos.
            *   Event listeners para mudanças nos `<select>` ou `input` do preço, acionando `simularPreco()`.

### 3.2. Painel: Dashboard de Vendas

**Visão Geral:** Um dashboard para acompanhamento diário, mensal e anual das vendas, com opções de filtragem.

*   **3.2.1. Back-end:**
    *   **3.2.1.1. Classe de Lógica de Negócio/Relatórios `DashboardVendas`:**
        *   Criar o arquivo `src/core/DashboardVendas.php`.
        *   Implementar o método estático `getVendasPorPeriodo(tipo_periodo, data_inicio = null, data_fim = null)`:
            *   Recebe o tipo de período ('dia', 'mes', 'ano') e datas opcionais.
            *   Realiza consultas SQL para agregar dados de vendas por esse período.
            *   Pode incluir `SUM(valor_total)`, `COUNT(id)`, etc.
            *   Retorna um array com os dados para o gráfico/tabela.
        *   Implementar o método estático `getVendasPorMetodoPagamento(data_inicio, data_fim)`:
            *   Agrega vendas por método de pagamento.
        *   Implementar o método estático `getVendasPorAmbienteVenda(data_inicio, data_fim)`:
            *   Agrega vendas por ambiente de venda.
        *   Implementar o método estático `getTopProdutosVendidos(data_inicio, data_fim, limite)`:
            *   Retorna os produtos mais vendidos em um período.
    *   **3.2.1.2. Endpoints AJAX (PHP):**
        *   **`src/consultar/dashboard/vendas_por_periodo.php`:**
            *   Recebe `tipo_periodo`, `data_inicio`, `data_fim`.
            *   Chama `DashboardVendas::getVendasPorPeriodo()`.
            *   Retorna JSON.
        *   **`src/consultar/dashboard/vendas_por_metodo.php`:**
            *   Recebe `data_inicio`, `data_fim`.
            *   Chama `DashboardVendas::getVendasPorMetodoPagamento()`.
            *   Retorna JSON.
        *   **`src/consultar/dashboard/vendas_por_ambiente.php`:**
            *   Recebe `data_inicio`, `data_fim`.
            *   Chama `DashboardVendas::getVendasPorAmbienteVenda()`.
            *   Retorna JSON.
        *   **`src/consultar/dashboard/top_produtos.php`:**
            *   Recebe `data_inicio`, `data_fim`, `limite`.
            *   Chama `DashboardVendas::getTopProdutosVendidos()`.
            *   Retorna JSON.

*   **3.2.2. Front-end:**
    *   **3.2.2.1. Página `dashboard_vendas.php`:**
        *   Criar o arquivo `dashboard_vendas.php`.
        *   Incluir `templates/header.php` e `templates/footer.php`.
        *   Estruturar o HTML para:
            *   Filtros de Período (Diário, Mensal, Anual, Data Início/Fim).
            *   Áreas para exibir gráficos (usar Chart.js ou similar, a ser incluído no `header.php`).
            *   Áreas para tabelas de resumo (ex: Top Produtos Vendidos).
    *   **3.2.2.2. Estrutura JavaScript:**
        *   Criar a pasta `assets/js/dashboard_vendas/`.
        *   **`assets/js/dashboard_vendas/index.js`:**
            *   Função `carregarDashboard(filtros)`:
                *   Chama múltiplas requisições AJAX para os endpoints do dashboard.
                *   Renderiza os dados em gráficos e tabelas usando Chart.js.
            *   Event listeners para os filtros, acionando `carregarDashboard()`.

---

## 3. Módulos (Outros)

### 3.3. Módulo: Autenticação e Autorização

**Visão Geral:** Implementação do sistema de login e controle de acesso básico.

*   **3.3.1. Back-end:**
    *   **3.3.1.1. Lógica de Autenticação:**
        *   Criar o arquivo `auth.php` (mencionado no `index.php`).
        *   Implementar a verificação de credenciais (`email` e `senha`) contra a tabela `usuario`.
        *   Utilizar `password_verify()` para senhas com hash.
        *   Iniciar uma sessão PHP em caso de sucesso (`$_SESSION['user_id']`, `$_SESSION['user_name']`, etc.).
        *   Redirecionar para uma página principal (ex: `dashboard_vendas.php`) em caso de sucesso.
        *   Redirecionar de volta para `index.php` com mensagem de erro em caso de falha.
    *   **3.3.1.2. Controle de Acesso (Middlewares/Funções de Verificação):**
        *   Criar uma função/classe que verifica se o usuário está logado em todas as páginas restritas.
        *   Redirecionar para a página de login se não estiver autenticado.
        *   (Opcional) Implementar verificação de permissões se houver diferentes níveis de usuário.

*   **3.3.2. Front-end:**
    *   **3.3.2.1. Página de Login (`index.php`):**
        *   Garantir que o formulário POST para `auth.php`.
        *   Exibir mensagens de erro/sucesso (se houver).
    *   **3.3.2.2. Logout:**
        *   Criar um endpoint `logout.php` que destrói a sessão e redireciona para `index.php`.
        *   Adicionar um botão/link de logout no `templates/header.php` ou em uma barra de navegação.