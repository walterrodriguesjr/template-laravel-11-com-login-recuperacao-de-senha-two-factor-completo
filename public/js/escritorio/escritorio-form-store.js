$(document).ready(function () {

    const $estadoEscritorio = $("#estadoEscritorio");
    const $cidadeEscritorio = $("#cidadeEscritorio");
    const $cepEscritorio = $("#cepEscritorio");
    const $logradouroEscritorio = $("#logradouroEscritorio");
    const $bairroEscritorio = $("#bairroEscritorio");
    const $numeroEscritorio = $("#numeroEscritorio");

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

    // Máscaras
    $("#cnpjEscritorio").mask("00.000.000/0000-00");
    $("#telefoneEscritorio").mask("(00) 0000-0000");
    $("#celularEscritorio").mask("(00) 00000-0000");
    $("#cepEscritorio").mask("00000-000");

    // Desabilita o select de cidade inicialmente
    $cidadeEscritorio.prop("disabled", true);

    // Função para limpar os campos de endereço
    function limparEndereco() {
        $logradouroEscritorio.val("");
        $bairroEscritorio.val("");
        $numeroEscritorio.val("");
        $estadoEscritorio.val("").trigger("change");
        $cidadeEscritorio.prop("disabled", true).empty().append('<option value="">Selecione uma cidade</option>');
        initializeSelect2($cidadeEscritorio, "Selecione uma cidade");
    }

    // Função para carregar os estados
    function carregarEstados() {
        $.ajax({
            url: "https://servicodados.ibge.gov.br/api/v1/localidades/estados",
            type: "GET",
            dataType: "json",
            success: function (data) {
                $estadoEscritorio.empty().append('<option value="">Selecione um estado</option>');
                data.forEach(function (estado) {
                    $estadoEscritorio.append(`<option value="${estado.sigla}">${estado.nome}</option>`);
                });
                initializeSelect2($estadoEscritorio, "Selecione um estado");
            },
            error: function () {
                toastr.error("Erro ao carregar estados. Por favor, tente novamente.");
            },
        });
    }

    // Função para carregar as cidades do estado selecionado
    function carregarCidades(estadoSigla, cidadeSelecionada = null) {
        if (!estadoSigla) {
            $cidadeEscritorio.prop("disabled", true).empty().append('<option value="">Selecione uma cidade</option>');
            initializeSelect2($cidadeEscritorio, "Selecione uma cidade");
            return;
        }

        $.ajax({
            url: `https://servicodados.ibge.gov.br/api/v1/localidades/estados/${estadoSigla}/municipios`,
            type: "GET",
            dataType: "json",
            success: function (data) {
                $cidadeEscritorio.empty().append('<option value="">Selecione uma cidade</option>');
                data.forEach(function (cidade) {
                    $cidadeEscritorio.append(
                        `<option value="${cidade.nome}" ${cidade.nome === cidadeSelecionada ? 'selected' : ''}>${cidade.nome}</option>`
                    );
                });
                $cidadeEscritorio.prop("disabled", false);
                initializeSelect2($cidadeEscritorio, "Selecione uma cidade");
            },
            error: function () {
                toastr.error("Erro ao carregar cidades. Por favor, tente novamente.");
            },
        });
    }

    // Busca de endereço pelo CEP
    $cepEscritorio.on("input", function () {
        const cep = $(this).val().replace(/\D/g, ""); // Remove caracteres não numéricos

        if (cep.length === 8) {
            $.ajax({
                url: `https://viacep.com.br/ws/${cep}/json/`,
                type: "GET",
                dataType: "json",
                success: function (data) {
                    if (data.erro) {
                        toastr.warning("CEP não localizado. Digite um CEP válido.");
                        limparEndereco();
                        return;
                    }

                    $logradouroEscritorio.val(data.logradouro);
                    $bairroEscritorio.val(data.bairro);
                    $estadoEscritorio.val(data.uf).trigger("change");
                    carregarCidades(data.uf, data.localidade);

                    toastr.info("CEP localizado com sucesso!");
                },
                error: function () {
                    toastr.error("Erro ao buscar o CEP. Por favor, tente novamente.");
                    limparEndereco();
                },
            });
        }
    });

    // Limpa o endereço quando o CEP é apagado
    $cepEscritorio.on("input", function () {
        if ($(this).val().replace(/\D/g, "").length < 8) {
            limparEndereco();
        }
    });

    // Evento de mudança no select de estados
    $estadoEscritorio.on("change", function () {
        const estadoSelecionado = $(this).val();
        carregarCidades(estadoSelecionado);
    });

    // Carrega os estados ao iniciar
    carregarEstados();

    // Desativa a validação padrão em campos não obrigatórios
    $("#cnpjEscritorio, #telefoneEscritorio, #cepEscritorio, #logradouroEscritorio, #numeroEscritorio, #bairroEscritorio").removeAttr("required");

    // Validação do formulário
    $("#dados-escritorio-form").validate({
        rules: {
            nome_escritorio: { required: true },
            email_escritorio: { required: true, email: true },
            celular_escritorio: { required: true, minlength: 14, maxlength: 15 },
        },
        messages: {
            nome_escritorio: { required: "O nome do escritório é obrigatório." },
            email_escritorio: {
                required: "O email é obrigatório.",
                email: "Digite um email válido.",
            },
            celular_escritorio: {
                required: "O celular é obrigatório.",
                minlength: "Digite um celular válido.",
                maxlength: "Digite um celular válido.",
            },
        },
        submitHandler: function (form) {
            alert("Formulário validado com sucesso!");
            form.submit();
        },
    });

    $(document).ready(function () {
        $(document).ready(function () {
            $("#buttonSalvarDadosEscritorio").click(function (e) {
                e.preventDefault();

                if ($("#dados-escritorio-form").valid()) {
                    toastr.info("Salvando dados...");

                    const formData = {
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
                        _token: csrfToken
                    };

                    $.ajax({
                        url: escritorioStoreUrl, // Usa a variável definida no Blade
                        type: "POST",
                        data: formData,
                        headers: { "X-CSRF-TOKEN": csrfToken },
                        success: function (response) {
                            toastr.success(response.message);
                        },
                        error: function (xhr) {
                            if (xhr.status === 422) {
                                const errors = xhr.responseJSON.errors;
                                Object.keys(errors).forEach(function (key) {
                                    toastr.error(errors[key]);
                                });
                            } else if (xhr.status === 400) {
                                toastr.warning(xhr.responseJSON.message); // Mensagem de usuário já tem escritório
                            } else {
                                toastr.error("Erro ao salvar os dados. Por favor, tente novamente.");
                            }
                        }
                    });
                } else {
                    toastr.warning("Preencha todos os campos obrigatórios.");
                }
            });
        });
    });

});
