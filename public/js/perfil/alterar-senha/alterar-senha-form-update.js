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

    // Validação do formulário com jQuery Validate
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
});
