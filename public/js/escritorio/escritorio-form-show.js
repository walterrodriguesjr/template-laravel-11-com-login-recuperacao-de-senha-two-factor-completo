$(document).ready(function () {
    const $cidadeEscritorio = $("#cidadeEscritorio");

    // Função para carregar as cidades do estado selecionado
    function carregarCidades(estadoSigla, cidadeSelecionada = null) {
        if (!estadoSigla) {
            $cidadeEscritorio.prop("disabled", true).empty().append('<option value="">Selecione uma cidade</option>');
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
            },
            error: function () {
                toastr.error("Erro ao carregar cidades. Por favor, tente novamente.");
            },
        });
    }

    // Função para carregar os dados do escritório e preencher os campos
    function carregarDadosEscritorio() {
        if (!escritorioShowUrl) {
            toastr.warning("Nenhum escritório cadastrado.");
            return;
        }

        $.ajax({
            type: "GET",
            url: escritorioShowUrl, // URL definida no Blade
            dataType: "json",
            headers: { "X-CSRF-TOKEN": csrfToken },
            success: function (response) {
                if (response.success) {
                    const dados = response.dados;

                    $("#nomeEscritorio").val(dados.nome_escritorio);
                    $("#cnpjEscritorio").val(dados.cnpj_escritorio);
                    $("#telefoneEscritorio").val(dados.telefone_escritorio);
                    $("#celularEscritorio").val(dados.celular_escritorio);
                    $("#emailEscritorio").val(dados.email_escritorio);
                    $("#cepEscritorio").val(dados.cep_escritorio);
                    $("#logradouroEscritorio").val(dados.logradouro_escritorio);
                    $("#numeroEscritorio").val(dados.numero_escritorio);
                    $("#bairroEscritorio").val(dados.bairro_escritorio);

                    $("#estadoEscritorio").val(dados.estado_escritorio).trigger("change");

                    if (dados.estado_escritorio) {
                        setTimeout(() => {
                            carregarCidades(dados.estado_escritorio, dados.cidade_escritorio);
                        }, 500);
                    }

                } else {
                    toastr.warning("Nenhum escritório cadastrado.");
                }
            },
            error: function () {
                toastr.error("Erro ao carregar os dados do escritório.");
            }
        });
    }

    // Chama o carregamento de dados do escritório ao iniciar
    carregarDadosEscritorio();
});
