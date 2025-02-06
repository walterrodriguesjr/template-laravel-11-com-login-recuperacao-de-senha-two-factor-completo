$(document).ready(function () {
    function carregarSessoesAtivas() {
        $.ajax({
            url: "/sessoes-ativas",
            type: "GET",
            success: function (sessoes) {
                const $tbody = $("#listaSessoes");
                $tbody.empty();

                if (sessoes.length === 0) {
                    $tbody.append('<tr><td colspan="4" class="text-center">Nenhuma sessão ativa encontrada.</td></tr>');
                    return;
                }

                sessoes.forEach((sessao) => {
                    const linha = `
                        <tr>
                            <td>${sessao.ip_address}</td>
                            <td>${sessao.user_agent}</td>
                            <td>${sessao.ultima_atividade}</td>
                            <td>
                                <button class="btn btn-danger btn-sm encerrarSessao" data-id="${sessao.id}">
                                    <i class="fas fa-times"></i> Encerrar
                                </button>
                            </td>
                        </tr>
                    `;
                    $tbody.append(linha);
                });
            },
            error: function () {
                toastr.error("Erro ao carregar sessões ativas.");
            },
        });
    }

    // Encerrar sessão específica
    $(document).on("click", ".encerrarSessao", function () {
        const sessaoId = $(this).data("id");

        $.ajax({
            url: `/sessoes-ativas/logout/${sessaoId}`,
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.logout) {
                    toastr.success(response.message);
                    setTimeout(() => {
                        window.location.href = "/logout";
                    }, 2000); // Redireciona para o login após 2 segundos
                } else {
                    toastr.success(response.message);
                    carregarSessoesAtivas();
                }
            },
            error: function () {
                toastr.error("Erro ao encerrar sessão.");
            },
        });
    });

    // Encerrar todas as sessões
    $("#encerrarTodasSessoes").click(function () {
        $.ajax({
            url: "/sessoes-ativas/logout-all",
            type: "POST",
            headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
            success: function (response) {
                if (response.logout) {
                    toastr.success(response.message);
                    setTimeout(() => {
                        window.location.href = "/login"; // Redireciona para login
                    }, 2000);
                } else {
                    toastr.success(response.message);
                    carregarSessoesAtivas(); // Atualiza a lista de sessões
                }
            },
            error: function () {
                toastr.error("Erro ao encerrar todas as sessões.");
            },
        });
    });

    // Carregar sessões ativas ao iniciar a página
    carregarSessoesAtivas();
});
