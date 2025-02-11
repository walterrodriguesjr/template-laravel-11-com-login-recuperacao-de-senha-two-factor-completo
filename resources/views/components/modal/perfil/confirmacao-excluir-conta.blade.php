<!-- Modal de Confirmação de Exclusão de Conta -->
<div class="modal" id="modalConfirmacaoExcluirConta" tabindex="-1" role="dialog" aria-labelledby="modalExcluirContaLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title" id="modalExcluirContaLabel"><i class="fas fa-exclamation-triangle"></i> Atenção!
                    Confirmação de Exclusão</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Digite o código de confirmação enviado ao seu e-mail:</p>

                <!-- Campo para Código de Verificação -->
                <div class="form-group">
                    <label for="codigoConfirmacaoModal">Código de Confirmação:</label>
                    <input type="text" class="form-control" id="codigoConfirmacaoModal"
                        placeholder="Digite o código">
                </div>
            </div>
            <div class="modal-footer justify-content-end">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i>
                    Cancelar</button>
                <button type="button" class="btn btn-danger" id="buttonConfirmarExclusao"><i class="fas fa-trash"></i>
                    Confirmar Exclusão</button>
            </div>
        </div>
    </div>
</div>
