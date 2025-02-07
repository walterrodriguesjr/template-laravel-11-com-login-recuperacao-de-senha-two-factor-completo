$(document).ready(function () {
    $("#buttonAlterarSenha").click(function (e) {
        e.preventDefault();

        // Verifica se o formulário está válido
        if ($("#alterar-senha-form").valid()) {
            toastr.info("Alterando senha...");

            const formData = {
                senha_atual: $("#senhaAtual").val(),
                nova_senha: $("#novaSenha").val(),
                nova_senha_confirmation: $("#confirmarSenha").val(),
                _token: csrfToken, // Token CSRF
            };

            // Faz a requisição AJAX
            $.ajax({
                url: "/alterar-senha",
                type: "POST",
                data: formData,
                headers: { "X-CSRF-TOKEN": csrfToken },
                success: function (response) {
                    toastr.success(response.success);
                    $("#alterar-senha-form")[0].reset(); // Reseta os campos
                    atualizarIndicadoresSenha(""); // Reseta os indicadores
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        Object.keys(errors).forEach(function (key) {
                            toastr.error(errors[key]);
                        });
                    } else {
                        toastr.error("Erro ao alterar a senha. Tente novamente.");
                    }
                }
            });
        } else {
            toastr.warning("Preencha todos os campos corretamente.");
        }
    });

    // Adiciona o método 'regex' para validação personalizada
    $.validator.addMethod("regex", function (value, element, param) {
        return this.optional(element) || param.test(value);
    }, "A senha deve conter pelo menos 8 caracteres, incluindo uma letra maiúscula, uma minúscula, um número e um caractere especial.");

    // Validação do formulário
    $("#alterar-senha-form").validate({
        rules: {
            senha_atual: { required: true },
            nova_senha: {
                required: true,
                minlength: 8,
                regex: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/,
            },
            nova_senha_confirmation: {
                required: true,
                equalTo: "#novaSenha"
            },
        },
        messages: {
            senha_atual: { required: "Digite sua senha atual." },
            nova_senha: {
                required: "Digite uma nova senha.",
                minlength: "A senha deve ter pelo menos 8 caracteres.",
                regex: "A senha deve conter pelo menos uma letra maiúscula, uma minúscula, um número e um caractere especial.",
            },
            nova_senha_confirmation: {
                required: "Confirme a nova senha.",
                equalTo: "As senhas não coincidem.",
            },
        },
    });

    // Evento para monitorar a digitação da senha e atualizar os indicadores
    $("#novaSenha").on("input", function () {
        atualizarIndicadoresSenha($(this).val());
    });

    function atualizarIndicadoresSenha(senha) {
        let requisitos = {
            comprimento: senha.length >= 8,
            maiuscula: /[A-Z]/.test(senha),
            minuscula: /[a-z]/.test(senha),
            numero: /\d/.test(senha),
            especial: /[@$!%*?&]/.test(senha),
        };

        // Atualiza os indicadores de cada requisito
        $("#requisito-comprimento").html(requisitos.comprimento ? `<i class="fas fa-check-circle text-success"></i> Pelo menos <strong>8 caracteres</strong>` : `<i class="fas fa-times-circle text-danger"></i> Pelo menos <strong>8 caracteres</strong>`);
        $("#requisito-maiuscula").html(requisitos.maiuscula ? `<i class="fas fa-check-circle text-success"></i> Uma <strong>letra maiúscula</strong>` : `<i class="fas fa-times-circle text-danger"></i> Uma <strong>letra maiúscula</strong>`);
        $("#requisito-minuscula").html(requisitos.minuscula ? `<i class="fas fa-check-circle text-success"></i> Uma <strong>letra minúscula</strong>` : `<i class="fas fa-times-circle text-danger"></i> Uma <strong>letra minúscula</strong>`);
        $("#requisito-numero").html(requisitos.numero ? `<i class="fas fa-check-circle text-success"></i> Um <strong>número</strong>` : `<i class="fas fa-times-circle text-danger"></i> Um <strong>número</strong>`);
        $("#requisito-especial").html(requisitos.especial ? `<i class="fas fa-check-circle text-success"></i> Um <strong>caractere especial</strong>` : `<i class="fas fa-times-circle text-danger"></i> Um <strong>caractere especial</strong>`);

        // Se todos os requisitos forem atendidos, muda a cor do alerta para verde
        if (Object.values(requisitos).every(Boolean)) {
            $("#mensagemSenha").removeClass("alert-warning").addClass("alert-success");
        } else {
            $("#mensagemSenha").removeClass("alert-success").addClass("alert-warning");
        }
    }
});
