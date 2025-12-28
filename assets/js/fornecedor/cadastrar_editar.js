$(document).ready(function() {
    // Resetar o formulário ao clicar no botão "Novo Fornecedor"
    $('button[data-bs-target="#modalFornecedor"]').on('click', function() {
        $('#form-fornecedor')[0].reset();
        $('#fornecedor_id').val('');
        $('#modalFornecedorLabel').text('Cadastrar Fornecedor');
        $('#status_registro').val('1'); // Define o status padrão como ativo
    });

    // Manipulador para o botão de EDITAR
    $('#tabela-fornecedores tbody').on('click', '.btn-edit', function () {
        var data = table.row($(this).parents('tr')).data();
        
        $('#modalFornecedorLabel').text('Editar Fornecedor');
        
        $.ajax({
            url: '../src/consultar/fornecedor_por_id.php',
            type: 'GET',
            data: { id: data.id },
            dataType: 'json',
            success: function(response) {
                $('#fornecedor_id').val(response.id);
                $('#status_registro').val(response.status_registro);
                $('#nome').val(response.nome);
                $('#cnpj_cpf').val(response.cnpj_cpf);
                $('#email').val(response.email);
                $('#telefone').val(response.telefone);
                $('#endereco').val(response.endereco);
                
                $('#modalFornecedor').modal('show');
            },
            error: function(xhr) {
                alert('Erro ao buscar dados: ' + (xhr.responseJSON ? xhr.responseJSON.message : "Erro desconhecido."));
            }
        });
    });

    // Manipulador para o envio do formulário (Cadastro e Edição)
    $('#form-fornecedor').on('submit', function(e) {
        e.preventDefault();

        var formData = $(this).serialize();
        var id = $('#fornecedor_id').val();
        
        var url = id ? '../src/editar/fornecedor.php' : '../src/cadastrar/fornecedor.php';

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                $('#modalFornecedor').modal('hide');
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
