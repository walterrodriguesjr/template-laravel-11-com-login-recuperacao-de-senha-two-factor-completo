$(document).ready(function () {
    // Inicializa o Select2 para o método de autenticação
    $('#tipoAutenticacao').select2({
        placeholder: "Selecione um método",
        allowClear: true,
        width: "100%"
    });

    // Atualiza mensagem e exibição do select de métodos
    function atualizarMensagem() {
        if ($("#switch2FA").is(":checked")) {
            $("#mensagem2FA").text("Sua autenticação de dois fatores já está ativa. Caso queira desabilitar, clique no botão abaixo.")
                .removeClass("alert-danger").addClass("alert-info");
            $("#metodoAutenticacao").slideDown();
        } else {
            $("#mensagem2FA").text("Clique para habilitar sua autenticação de dois fatores.")
                .removeClass("alert-info").addClass("alert-danger");
            $("#metodoAutenticacao").slideUp();
        }
    }

    atualizarMensagem(); // Aplica no carregamento da página

    // Atualiza dinamicamente ao clicar no toggle switch
    $("#switch2FA").change(function () {
        atualizarMensagem();
    });

    // AJAX para atualizar as configurações de 2FA
    $("#buttonAlterarLaterarAutenticaoDoisFatores").click(function (e) {
        e.preventDefault();

        const doisFatoresAtivo = $("#switch2FA").is(":checked") ? "sim" : "nao";
        const metodoAutenticacao = $("#tipoAutenticacao").val();

        const formData = {
            dois_fatores: doisFatoresAtivo,
            tipo_autenticacao: metodoAutenticacao,
            _token: csrfToken
        };

        $.ajax({
            url: "/atualizar-2fa",
            type: "POST",
            data: formData,
            headers: { "X-CSRF-TOKEN": csrfToken },
            success: function (response) {
                toastr.success(response.message);
                atualizarMensagem();
            },
            error: function (xhr) {
                toastr.error("Erro ao atualizar configuração de segurança.");
            }
        });
    });
});
