$(document).ready(function () {
    $("#buttonExcluirConta").prop("disabled", true);

    // Habilitar botão apenas se a senha for inserida e o checkbox marcado
    $("#senhaConfirmacao, #confirmarExclusao").on("input change", function () {
        let senha = $("#senhaConfirmacao").val();
        let check = $("#confirmarExclusao").prop("checked");

        if (senha !== "" && check) {
            $("#buttonExcluirConta").prop("disabled", false);
        } else {
            $("#buttonExcluirConta").prop("disabled", true);
        }
    });

    // Valida a senha antes de abrir o modal e envia o código de confirmação
    $("#buttonExcluirConta").click(function () {
        let senha = $("#senhaConfirmacao").val();

        $.ajax({
            url: "/validar-senha-exclusao",
            type: "POST",
            data: {
                senha_confirmacao: senha,
                _token: $("input[name=_token]").val()
            },
            beforeSend: function () {
                $("#buttonExcluirConta").prop("disabled", true).html('<i class="fas fa-spinner fa-spin"></i> Enviando código...');
            },
            success: function (response) {
                toastr.success(response.message);
                $("#modalConfirmacaoExcluirConta").modal("show"); // Exibe o modal somente se a senha for válida
                $("#buttonExcluirConta").prop("disabled", false).html('<i class="fas fa-trash"></i> Excluir Minha Conta');
            },
            error: function (xhr) {
                $("#buttonExcluirConta").prop("disabled", false).html('<i class="fas fa-trash"></i> Excluir Minha Conta');

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    toastr.error(xhr.responseJSON.message);
                } else {
                    toastr.error("Erro ao validar a senha. Tente novamente.");
                }
            }
        });
    });

    // Confirmar exclusão com código do email
    $("#buttonConfirmarExclusao").click(function (e) {
        e.preventDefault();

        let codigo = $("#codigoConfirmacaoModal").val();
        let senha = $("#senhaConfirmacao").val();

        if (codigo === "") {
            toastr.warning("Digite o código de exclusão.");
            return;
        }

        $.ajax({
            url: "/excluir-conta",
            type: "POST",
            data: {
                senha_confirmacao: senha,
                codigo_exclusao: codigo,
                _token: $("input[name=_token]").val()
            },
            beforeSend: function () {
                $("#buttonConfirmarExclusao").prop("disabled", true).html('<i class="fas fa-spinner fa-spin"></i> Excluindo...');
            },
            success: function (response) {
                toastr.success(response.message);
                setTimeout(function () {
                    window.location.href = "/logout"; // Redireciona após sucesso
                }, 2000);
            },
            error: function (xhr) {
                $("#buttonConfirmarExclusao").prop("disabled", false).html('<i class="fas fa-trash"></i> Confirmar Exclusão');

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    toastr.error(xhr.responseJSON.message);
                } else {
                    toastr.error("Erro ao excluir a conta. Tente novamente.");
                }
            }
        });
    });
});
