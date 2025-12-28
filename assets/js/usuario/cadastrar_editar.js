$(document).ready(function() {
    // Resetar o formulário ao clicar no botão "Novo Usuário"
    $('button[data-bs-target="#modalUsuario"]').on('click', function() {
        $('#form-usuario')[0].reset();
        $('#usuario_id').val('');
        $('#modalUsuarioLabel').text('Cadastrar Usuário');
        // Senha é obrigatória no cadastro, então remove o atributo readonly se houver
        $('#senha').prop('readonly', false).attr('required', 'required');
        $('#confirmar_senha').prop('readonly', false).attr('required', 'required');
    });

    // Manipulador para o botão de EDITAR
    $('#tabela-usuarios tbody').on('click', '.btn-edit', function () {
        var data = table.row($(this).parents('tr')).data();
        
        $('#modalUsuarioLabel').text('Editar Usuário');
        
        $.ajax({
            url: '../src/consultar/usuario_por_id.php',
            type: 'GET',
            data: { id: data.id },
            dataType: 'json',
            success: function(response) {
                $('#usuario_id').val(response.id);
                $('#nome').val(response.nome);
                $('#email').val(response.email);
                $('#status_registro').val(response.status_registro);
                
                // Senha não é retornada pelo endpoint, então limpa e torna opcional na edição
                $('#senha').val('').prop('readonly', false).removeAttr('required');
                $('#confirmar_senha').val('').prop('readonly', false).removeAttr('required');

                $('#modalUsuario').modal('show');
            },
            error: function(xhr) {
                alert('Erro ao buscar dados: ' + (xhr.responseJSON ? xhr.responseJSON.message : "Erro desconhecido."));
            }
        });
    });

    // Manipulador para o envio do formulário (Cadastro e Edição)
    $('#form-usuario').on('submit', function(e) {
        e.preventDefault();

        var formData = $(this).serialize();
        var id = $('#usuario_id').val();
        var senha = $('#senha').val();
        var confirmarSenha = $('#confirmar_senha').val();

        if (senha && senha !== confirmarSenha) {
            alert('A senha e a confirmação de senha não coincidem.');
            return;
        }
        
        var url = id ? '../src/editar/usuario.php' : '../src/cadastrar/usuario.php';

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                $('#modalUsuario').modal('hide');
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
