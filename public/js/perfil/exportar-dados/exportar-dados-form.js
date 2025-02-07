$(document).ready(function () {
    $("#baixarDadosJSON").click(async function () {
        try {
            toastr.info("Gerando seu arquivo JSON...");

            const response = await fetch("/perfil/exportar-dados?formato=json", {
                method: "GET",
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                }
            });

            if (!response.ok) {
                throw new Error(`Erro ao gerar JSON: ${response.statusText}`);
            }

            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement("a");
            a.href = url;
            a.download = "meus-dados.json";
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);

            toastr.success("Download do JSON concluído!");
        } catch (error) {
            console.error("Erro ao baixar JSON:", error);
            toastr.error("Erro ao baixar JSON. Tente novamente.");
        }
    });

    $("#baixarDadosCSV").click(async function () {
        try {
            toastr.info("Gerando seu arquivo CSV...");

            const response = await fetch("/perfil/exportar-dados?formato=csv", {
                method: "GET",
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                }
            });

            if (!response.ok) {
                throw new Error(`Erro ao gerar CSV: ${response.statusText}`);
            }

            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement("a");
            a.href = url;
            a.download = "meus-dados.csv";
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);

            toastr.success("Download do CSV concluído!");
        } catch (error) {
            console.error("Erro ao baixar CSV:", error);
            toastr.error("Erro ao baixar CSV. Tente novamente.");
        }
    });
});
