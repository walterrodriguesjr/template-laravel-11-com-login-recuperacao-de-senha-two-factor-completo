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

                $('#oabUsuario').val(response.dados.oab_usuario);
                if (response.dados.estado_oab_usuario) {
                    $("#estadoOabUsuario").val(response.dados.estado_oab_usuario).trigger("change");
                }
            } else {
                toastr.error(response.message || "Erro ao carregar os dados do usuário.");
            }
        },
        error: function () {
            toastr.error("Erro ao carregar os dados. Tente novamente.");
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
                // Inicializa ou atualiza o Select2 após carregar os estados
                initializeSelect2($estadoUsuario, "Selecione um estado");
                initializeSelect2($estadoOabUsuario, "Selecione um estado da OAB");
            },
            error: function () {
                toastr.error("Erro ao carregar estados. Por favor, tente novamente.");
                $estadoUsuario.empty().append('<option value="">Estado não disponível</option>');
                $estadoOabUsuario.empty().append('<option value="">Estado não disponível</option>');
                initializeSelect2($estadoUsuario, "Estado não disponível");
                initializeSelect2($estadoOabUsuario, "Estado não disponível");
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
                // Atualiza o Select2 após carregar as cidades
                initializeSelect2($cidadeUsuario, "Selecione uma cidade");
            },
            error: function () {
                toastr.error("Erro ao carregar cidades. Por favor, tente novamente.");
                $cidadeUsuario.prop("disabled", true).empty().append('<option value="">Cidades não disponíveis</option>');
                initializeSelect2($cidadeUsuario, "Cidades não disponíveis");
            }
        });
    }

    // Evento de mudança no select de estados
    $estadoUsuario.on("change", function () {
        const estadoSelecionado = $(this).val();
        carregarCidades(estadoSelecionado);
    });

    // Inicializa carregando os estados
    carregarEstados();

    // Máscaras e formatações
    $("#nomeUsuario").on("input", function () {
        const valorFormatado = $(this).val()
            .toLowerCase()
            .replace(/\b\w/g, (char) => char.toUpperCase());
        $(this).val(valorFormatado);
    });

    $("#cpfUsuario").mask("000.000.000-00");
    $("#celularUsuario").mask("(00)00000-0000");
    $("#oabUsuario").mask("00000000");

    // Validação do formulário
    $("#meus-dados-form").validate({
        rules: {
            nome_usuario: { required: true, minlength: 3 },
            email_usuario: { required: true, email: true },
            cpf_usuario: { required: true, minlength: 14, maxlength: 14 },
            celular_usuario: { required: true, minlength: 14, maxlength: 14 },
            data_nascimento_usuario: { required: true, date: true },
            estado_usuario: { required: true },
            cidade_usuario: { required: true },
        },
        messages: {
            nome_usuario: { required: "O nome é obrigatório.", minlength: "O nome deve ter pelo menos 3 caracteres." },
            email_usuario: { required: "O email é obrigatório.", email: "Insira um email válido." },
            cpf_usuario: { required: "O CPF é obrigatório.", minlength: "O CPF deve ter 14 caracteres no formato correto." },
            celular_usuario: { required: "O celular é obrigatório.", minlength: "O celular deve ter 14 caracteres no formato correto." },
            data_nascimento_usuario: { required: "A data de nascimento é obrigatória.", date: "Insira uma data válida." },
            estado_usuario: { required: "O estado é obrigatório." },
            cidade_usuario: { required: "A cidade é obrigatória." },
        },
        submitHandler: function (form) {
            alert("Formulário validado com sucesso!");
            form.submit();
        },
    });

    // Evento de salvar os dados do formulário
    $("#buttonSalvarDadosUsuarios").click(function (e) {
        e.preventDefault();
        if ($("#meus-dados-form").valid()) {
            toastr.info("Salvando dados...");
            const formData = {
                nome_usuario: $("#nomeUsuario").val(),
                email_usuario: $("#emailUsuario").val(),
                cpf_usuario: $("#cpfUsuario").val(),
                celular_usuario: $("#celularUsuario").val(),
                data_nascimento_usuario: $("#dataNascimentoUsuario").val(),
                estado_usuario: $("#estadoUsuario").val(),
                cidade_usuario: $("#cidadeUsuario").val(),
                oab_usuario: $("#oabUsuario").val(),
                estado_oab_usuario: $("#estadoOabUsuario").val(),
            };

            $.ajax({
                url: perfilUpdateUrl, // URL gerada dinamicamente no Blade
                type: "PUT",
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken // Token CSRF gerado no Blade
                },
                success: function () {
                    toastr.success("Dados atualizados com sucesso!");
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        Object.keys(errors).forEach(function (key) {
                            toastr.error(errors[key]);
                        });
                    } else {
                        toastr.error("Erro ao salvar os dados. Tente novamente.");
                    }
                }
            });
        } else {
            toastr.warning("Preencha todos os campos obrigatórios.");
        }
    });
});
