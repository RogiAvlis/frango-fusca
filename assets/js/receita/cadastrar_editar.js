$(document).ready(function() {
    // Resetar o formulário ao clicar no botão "Nova Receita"
    $('button[data-bs-target="#modalReceita"]').on('click', function() {
        $('#form-receita')[0].reset();
        $('#receita_id').val('');
        $('#modalReceitaLabel').text('Cadastrar Receita');
        $('#status_registro').val('1'); // Define o status padrão como ativo
        // Repopular selects, caso as opções tenham mudado ou não carregado na primeira vez
        if (window.popularSelectProdutos) {
            window.popularSelectProdutos('#produto_principal_id');
            window.popularSelectProdutos('#produto_ingrediente_id');
        }
        if (window.popularSelectUnidadesMedida) window.popularSelectUnidadesMedida();
    });

    // Manipulador para o botão de EDITAR
    $('#tabela-receitas tbody').on('click', '.btn-edit', function () {
        var data = table.row($(this).parents('tr')).data();
        
        $('#modalReceitaLabel').text('Editar Receita');
        
        $.ajax({
            url: '../src/consultar/receita_por_id.php',
            type: 'GET',
            data: { id: data.id },
            dataType: 'json',
            success: function(response) {
                $('#receita_id').val(response.id);
                $('#status_registro').val(response.status_registro);
                $('#quantidade_necessaria').val(response.quantidade_necessaria);
                
                // Seleciona os valores corretos nos selects
                if (window.popularSelectProdutos) {
                    window.popularSelectProdutos('#produto_principal_id'); // Garante que as opções estão carregadas
                    $('#produto_principal_id').val(response.produto_principal_id);
                    window.popularSelectProdutos('#produto_ingrediente_id'); // Garante que as opções estão carregadas
                    $('#produto_ingrediente_id').val(response.produto_ingrediente_id);
                }
                if (window.popularSelectUnidadesMedida) {
                    window.popularSelectUnidadesMedida(); // Garante que as opções estão carregadas
                    $('#unidade_medida_id').val(response.unidade_medida_id);
                }
                
                $('#modalReceita').modal('show');
            },
            error: function(xhr) {
                alert('Erro ao buscar dados: ' + (xhr.responseJSON ? xhr.responseJSON.message : "Erro desconhecido."));
            }
        });
    });

    // Manipulador para o envio do formulário (Cadastro e Edição)
    $('#form-receita').on('submit', function(e) {
        e.preventDefault();

        var formData = $(this).serialize();
        var id = $('#receita_id').val();
        
        var url = id ? '../src/editar/receita.php' : '../src/cadastrar/receita.php';

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                $('#modalReceita').modal('hide');
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
