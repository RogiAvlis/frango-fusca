$(document).ready(function() {
    // Resetar o formulário ao clicar no botão "Novo Produto"
    $('button[data-bs-target="#modalProduto"]').on('click', function() {
        $('#form-produto')[0].reset();
        $('#produto_id').val('');
        $('#modalProdutoLabel').text('Cadastrar Produto');
        $('#status_registro').val('1'); // Define o status padrão como ativo
        // Repopular selects, caso as opções tenham mudado ou não carregado na primeira vez
        if (window.popularSelectUnidadesMedida) window.popularSelectUnidadesMedida();
        if (window.popularSelectFornecedores) window.popularSelectFornecedores();
    });

    // Manipulador para o botão de EDITAR
    $('#tabela-produtos tbody').on('click', '.btn-edit', function () {
        var data = table.row($(this).parents('tr')).data();
        
        $('#modalProdutoLabel').text('Editar Produto');
        
        $.ajax({
            url: '../src/consultar/produto_por_id.php',
            type: 'GET',
            data: { id: data.id },
            dataType: 'json',
            success: function(response) {
                $('#produto_id').val(response.id);
                $('#status_registro').val(response.status_registro);
                $('#nome').val(response.nome);
                $('#descricao').val(response.descricao);
                $('#preco_custo').val(response.preco_custo);
                $('#preco_venda').val(response.preco_venda);
                $('#quantidade_comprada').val(response.quantidade_comprada);
                // Seleciona os valores corretos nos selects
                if (window.popularSelectUnidadesMedida) {
                    window.popularSelectUnidadesMedida(); // Garante que as opções estão carregadas
                    $('#unidade_medida_id').val(response.unidade_medida_id);
                }
                if (window.popularSelectFornecedores) {
                    window.popularSelectFornecedores(); // Garante que as opções estão carregadas
                    $('#fornecedor_id').val(response.fornecedor_id);
                }
                
                $('#modalProduto').modal('show');
            },
            error: function(xhr) {
                alert('Erro ao buscar dados: ' + (xhr.responseJSON ? xhr.responseJSON.message : "Erro desconhecido."));
            }
        });
    });

    // Manipulador para o envio do formulário (Cadastro e Edição)
    $('#form-produto').on('submit', function(e) {
        e.preventDefault();

        var formData = $(this).serialize();
        var id = $('#produto_id').val();
        
        var url = id ? '../src/editar/produto.php' : '../src/cadastrar/produto.php';

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                $('#modalProduto').modal('hide');
                table.ajax.reload();
                alert(response.message);
            },
            error: function(xhr) {
                var errorMessage = xhr.responseJSON ? xhr.responseJSON.message : "Ocorreu um erro.";
                alert('Erro ao salvar: ' + errorMessage);
            }
        });
    });
});
