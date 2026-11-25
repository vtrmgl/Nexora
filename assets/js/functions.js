/* Modal dashboard */

function abrirModalExclusao(id, nome, email) {
    const inputId = document.getElementById('modal-usuario-id');
    const infoUsuario = document.getElementById('modal-info-usuario');
    const modal = document.getElementById('modal-exclusao');

    if (inputId) {
        inputId.value = id;
    }

    if (infoUsuario) {
        infoUsuario.innerHTML =
            '<strong>ID:</strong> #' + id + '<br>' +
            '<strong>Nome:</strong> ' + nome + '<br>' +
            '<strong>E-mail:</strong> ' + email;
    }

    if (modal) {
        modal.style.display = 'flex';
    }
}

function fecharModalExclusao() {
    const modal = document.getElementById('modal-exclusao');
    const inputId = document.getElementById('modal-usuario-id');

    if (modal) {
        modal.style.display = 'none';
    }

    if (inputId) {
        inputId.value = '';
    }
}