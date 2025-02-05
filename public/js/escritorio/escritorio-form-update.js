$(document).ready(function () {
    $("#buttonAtualizarDadosEscritorio").click(function (e) {
        e.preventDefault();

        // Verifica se a URL de atualização está definida corretamente
        if (!escritorioUpdateUrl) {
            toastr.error("Erro: Nenhum escritório foi encontrado para atualização.");
            return;
        }

        // Valida o formulário antes de enviar
        if ($("#dados-escritorio-form").valid()) {
            toastr.info("Atualizando dados...");

            // Captura os dados do formulário
            const formData = {
                _method: "PUT", // Laravel aceita PUT via POST
                nome_escritorio: $("#nomeEscritorio").val(),
                cnpj_escritorio: $("#cnpjEscritorio").val(),
                telefone_escritorio: $("#telefoneEscritorio").val(),
                celular_escritorio: $("#celularEscritorio").val(),
                email_escritorio: $("#emailEscritorio").val(),
                cep_escritorio: $("#cepEscritorio").val(),
                logradouro_escritorio: $("#logradouroEscritorio").val(),
                numero_escritorio: $("#numeroEscritorio").val(),
                bairro_escritorio: $("#bairroEscritorio").val(),
                estado_escritorio: $("#estadoEscritorio").val(),
                cidade_escritorio: $("#cidadeEscritorio").val(),
                _token: csrfToken // Token CSRF
            };

            // Faz a requisição AJAX para atualizar os dados
            $.ajax({
                url: escritorioUpdateUrl, // URL de atualização
                type: "POST", // Laravel aceita método POST com "_method: PUT"
                data: formData,
                headers: { "X-CSRF-TOKEN": csrfToken },
                success: function (response) {
                    toastr.success("Dados do escritório atualizados com sucesso!");
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        // Erros de validação
                        const errors = xhr.responseJSON.errors;
                        Object.keys(errors).forEach(function (key) {
                            toastr.error(errors[key]);
                        });
                    } else {
                        toastr.error("Erro ao atualizar os dados. Por favor, tente novamente.");
                    }
                }
            });
        } else {
            toastr.warning("Preencha todos os campos obrigatórios.");
        }
    });
});
