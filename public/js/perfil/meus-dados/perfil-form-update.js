$(document).ready(function () {
    // Inicializa o Select2 nos selects
    const initializeSelect2 = ($select, placeholder) => {
        $select.select2({
            placeholder: placeholder,
            allowClear: true,
            width: "100%", // Ajusta a largura
            language: {
                noResults: function () {
                    return "Nenhum resultado encontrado";
                }
            },
        });
    };

    // Carrega os dados do usuário logado ao iniciar
    $.ajax({
        type: "GET",
        url: perfilShowUrl, // URL definida no Blade
        dataType: "json",
        headers: {
            'X-CSRF-TOKEN': csrfToken // Adiciona o token CSRF no cabeçalho
        },
        success: function (response) {
            if (response.success) {
                $("#nomeUsuario").val(response.dados.nome_usuario);
                $("#emailUsuario").val(response.dados.email_usuario);
                $("#cpfUsuario").val(response.dados.cpf_usuario);
                $("#celularUsuario").val(response.dados.celular_usuario);
                $("#dataNascimentoUsuario").val(response.dados.data_nascimento_usuario);

                $("#estadoUsuario").val(response.dados.estado_usuario).trigger("change");
                carregarCidades(response.dados.estado_usuario, response.dados.cidade_usuario);

                $("#oabUsuario").val(response.dados.oab_usuario);
                if (response.dados.estado_oab_usuario) {
                    $("#estadoOabUsuario").val(response.dados.estado_oab_usuario).trigger("change");
                }

                // Exibe a foto do usuário ou a imagem padrão
                $("#fotoPreview").attr("src", response.dados.foto_usuario);
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Erro",
                    text: response.message || "Erro ao carregar os dados do usuário.",
                    confirmButtonText: "<i class='fas fa-check'></i> OK"
                });
            }
        },
        error: function () {
            Swal.fire({
                icon: "error",
                title: "Erro",
                text: "Erro ao carregar os dados. Tente novamente.",
                confirmButtonText: "<i class='fas fa-check'></i> OK"
            });
        }
    });

    // Atualiza a pré-visualização da foto antes do upload
    $("#fotoUsuario").change(function (event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                $("#fotoPreview").attr("src", e.target.result);
            };
            reader.readAsDataURL(file);
        }
    });

    const $estadoUsuario = $("#estadoUsuario");
    const $cidadeUsuario = $("#cidadeUsuario");
    const $estadoOabUsuario = $("#estadoOabUsuario");

    // Desabilita o select de cidade inicialmente
    $cidadeUsuario.prop("disabled", true);

    // Função para carregar os estados em ambos os selects
    function carregarEstados() {
        $.ajax({
            url: "https://servicodados.ibge.gov.br/api/v1/localidades/estados",
            type: "GET",
            dataType: "json",
            success: function (data) {
                $estadoUsuario.empty().append('<option value="">Selecione um estado</option>');
                $estadoOabUsuario.empty().append('<option value="">Selecione um estado da OAB</option>');
                data.forEach(function (estado) {
                    const option = `<option value="${estado.sigla}">${estado.nome}</option>`;
                    $estadoUsuario.append(option);
                    $estadoOabUsuario.append(option);
                });
                initializeSelect2($estadoUsuario, "Selecione um estado");
                initializeSelect2($estadoOabUsuario, "Selecione um estado da OAB");
            },
            error: function () {
                Swal.fire({
                    icon: "error",
                    title: "Erro",
                    text: "Erro ao carregar estados. Por favor, tente novamente.",
                    confirmButtonText: "<i class='fas fa-check'></i> OK"
                });
            }
        });
    }

    // Função para carregar as cidades com tratamento de erro
    function carregarCidades(estadoSigla, cidadeSelecionada = null) {
        if (!estadoSigla) {
            $cidadeUsuario.prop("disabled", true).empty().append('<option value="">Selecione uma cidade</option>');
            initializeSelect2($cidadeUsuario, "Selecione uma cidade");
            return;
        }

        $.ajax({
            url: `https://servicodados.ibge.gov.br/api/v1/localidades/estados/${estadoSigla}/municipios`,
            type: "GET",
            dataType: "json",
            success: function (data) {
                $cidadeUsuario.empty().append('<option value="">Selecione uma cidade</option>');
                $cidadeUsuario.prop("disabled", false);
                data.forEach(function (cidade) {
                    $cidadeUsuario.append(
                        `<option value="${cidade.nome}" ${cidade.nome === cidadeSelecionada ? 'selected' : ''}>${cidade.nome}</option>`
                    );
                });
                initializeSelect2($cidadeUsuario, "Selecione uma cidade");
            },
            error: function () {
                Swal.fire({
                    icon: "error",
                    title: "Erro",
                    text: "Erro ao carregar cidades. Por favor, tente novamente.",
                    confirmButtonText: "<i class='fas fa-check'></i> OK"
                });
            }
        });
    }

    // Evento de mudança no select de estados
    $estadoUsuario.on("change", function () {
        const estadoSelecionado = $(this).val();
        carregarCidades(estadoSelecionado);
    });

    carregarEstados();

    // Máscaras e formatações
    $("#cpfUsuario").mask("000.000.000-00");
    $("#celularUsuario").mask("(00) 00000-0000");
    $("#oabUsuario").mask("00000000");

    // Evento de salvar os dados do formulário
    $("#buttonSalvarDadosUsuarios").click(function (e) {
        e.preventDefault();

        const formData = new FormData();
        formData.append("_method", "PUT"); // Define explicitamente o método PUT
        formData.append("nome_usuario", $("#nomeUsuario").val());
        formData.append("email_usuario", $("#emailUsuario").val());
        formData.append("cpf_usuario", $("#cpfUsuario").val());
        formData.append("celular_usuario", $("#celularUsuario").val());
        formData.append("data_nascimento_usuario", $("#dataNascimentoUsuario").val());
        formData.append("estado_usuario", $("#estadoUsuario").val());
        formData.append("cidade_usuario", $("#cidadeUsuario").val());
        formData.append("oab_usuario", $("#oabUsuario").val());
        formData.append("estado_oab_usuario", $("#estadoOabUsuario").val());

        // Adiciona a foto ao FormData se o usuário selecionou uma
        const fotoFile = $("#fotoUsuario")[0].files[0];
        if (fotoFile) {
            formData.append("foto_usuario", fotoFile);
        }

        let loadingSwal = Swal.fire({
            title: "Salvando...",
            text: "Aguarde enquanto seus dados estão sendo atualizados.",
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        let requestStartTime = new Date().getTime(); // Marca o tempo de início da requisição
        let minWaitTime = 1500; // Tempo mínimo de exibição do spinner (1,5 segundos)
        let maxWaitTime = 10000; // Tempo máximo de espera (10 segundos)
        let timeoutReached = false; // Controle do timeout

        // **Define um timeout para forçar um erro após 10 segundos**
        let timeout = setTimeout(() => {
            timeoutReached = true;
            Swal.close();
            Swal.fire({
                icon: "error",
                title: "Erro",
                text: "A requisição demorou muito para responder. Tente novamente.",
                confirmButtonText: "<i class='fas fa-check'></i> OK"
            });
        }, maxWaitTime);

        $.ajax({
            url: perfilUpdateUrl, // URL gerada dinamicamente no Blade
            type: "POST", // Enviar como POST e forçar PUT via _method
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                "X-CSRF-TOKEN": csrfToken
            },
            success: function (response) {
                clearTimeout(timeout); // Cancela o timeout caso a requisição seja bem-sucedida

                if (timeoutReached) return; // Se o timeout foi atingido, ignora a resposta

                let requestEndTime = new Date().getTime(); // Marca o tempo de finalização da requisição
                let elapsedTime = requestEndTime - requestStartTime; // Calcula o tempo de execução

                setTimeout(() => {
                    Swal.close(); // Fecha o alerta de carregamento após o tempo mínimo

                    if (response.success) {
                        Swal.fire({
                            icon: "success",
                            title: "Sucesso!",
                            text: "Dados atualizados com sucesso.",
                            confirmButtonText: "<i class='fas fa-check'></i> OK"
                        });

                        // Obtém a nova URL da foto do usuário
                        let novaFoto = response.dados.foto_usuario;

                        // Atualiza a imagem no perfil (onde o usuário editou)
                        $("#fotoPreview").attr("src", novaFoto);

                        // Atualiza a imagem do usuário no sidebar sem precisar recarregar a página
                        $(".sidebar .image img").attr("src", novaFoto);

                        atualizarHistoricoAlteracoes();
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Erro",
                            text: response.message || "Houve um erro ao atualizar os dados.",
                            confirmButtonText: "<i class='fas fa-check'></i> OK"
                        });
                    }
                }, Math.max(minWaitTime - elapsedTime, 0)); // Garante tempo mínimo de 1,5 segundos
            },
            error: function (xhr) {
                clearTimeout(timeout); // Cancela o timeout caso a requisição falhe

                if (timeoutReached) return; // Se o timeout foi atingido, ignora a resposta

                let requestEndTime = new Date().getTime();
                let elapsedTime = requestEndTime - requestStartTime;

                setTimeout(() => {
                    Swal.close(); // Fecha o alerta de carregamento após tempo mínimo

                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        let errorMessage = "Ocorreram os seguintes erros:\n";
                        Object.keys(errors).forEach(function (key) {
                            errorMessage += `• ${errors[key]}\n`;
                        });

                        Swal.fire({
                            icon: "error",
                            title: "Erro na validação",
                            text: errorMessage,
                            confirmButtonText: "<i class='fas fa-check'></i> OK"
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Erro",
                            text: "Erro ao salvar os dados. Tente novamente.",
                            confirmButtonText: "<i class='fas fa-check'></i> OK"
                        });
                    }
                }, Math.max(minWaitTime - elapsedTime, 0)); // Garante tempo mínimo de 1,5 segundos
            }
        });
    });
});
