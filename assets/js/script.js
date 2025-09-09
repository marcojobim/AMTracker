document.addEventListener('DOMContentLoaded', function () {

    // Procura pelo elemento de alerta na página
    const alertBox = document.querySelector('.alert-success', '.alert-danger');

    // Verifica se a caixa de alerta realmente existe na página atual
    if (alertBox) {
        // Agenda o desaparecimento da mensagem
        setTimeout(function () {
            alertBox.classList.add('fade-out');
            setTimeout(function () {
                if (alertBox) alertBox.remove();
            }, 500);
        }, 3000);
    }


    // --- Seleção de todos os elementos do formulário ---
    const tipoSelect = document.getElementById('tipo');
    const statusSelect = document.getElementById('status_consumo');

    const animeFields = document.getElementById('anime-fields');
    const mangaFields = document.getElementById('manga-fields');

    const totalEpisodiosInput = document.getElementById('total_episodios');
    const progressoEpisodiosInput = document.getElementById('progresso_episodios');
    const totalCapitulosInput = document.getElementById('total_capitulos');
    const progressoCapitulosInput = document.getElementById('progresso_capitulos');

    const camposConcluido = document.getElementById('campos_concluido');
    const notaInput = document.getElementById('nota_pessoal');

    // --- LÓGICA DE MANIPULAÇÃO ---

    // Função para mostrar/esconder campos de Anime vs. Mangá
    function handleTipoChange() {
        if (!tipoSelect) return;
        const tipoSelecionado = tipoSelect.value;
        if (tipoSelecionado === 'Anime') {
            if (animeFields) animeFields.style.display = 'block';
            if (mangaFields) mangaFields.style.display = 'none';
        } else if (tipoSelecionado === 'Mangá') {
            if (animeFields) animeFields.style.display = 'none';
            if (mangaFields) mangaFields.style.display = 'block';
        }
    }

    // NOVA FUNÇÃO: Preenche o progresso se o status for 'Concluído'
    function autoFillProgress() {
        if (statusSelect.value === 'Concluído') {
            if (totalEpisodiosInput && progressoEpisodiosInput) {
                progressoEpisodiosInput.value = totalEpisodiosInput.value;
            }
            if (totalCapitulosInput && progressoCapitulosInput) {
                progressoCapitulosInput.value = totalCapitulosInput.value;
            }
        }
    }

    // Função principal que gerencia as mudanças de STATUS
    function handleStatusChange() {
        if (!statusSelect) return;

        const statusSelecionado = statusSelect.value;

        if (statusSelecionado === 'Concluído' || statusSelecionado === 'Abandonado') {
            camposConcluido.style.display = 'block';
            notaInput.required = (statusSelecionado === 'Concluído');
        } else {
            camposConcluido.style.display = 'none';
            notaInput.required = false;
        }

        // Bloqueia ou desbloqueia os campos de progresso
        if (statusSelecionado === 'Concluído') {
            autoFillProgress(); // Chama a função de preenchimento
        }
    }

    // --- EXECUÇÃO E "OUVINTES DE EVENTOS" ---

    // Ouvinte para o seletor de TIPO
    if (tipoSelect) {
        tipoSelect.addEventListener('change', handleTipoChange);
    }
    // Ouvinte para o seletor de STATUS
    if (statusSelect) {
        statusSelect.addEventListener('change', handleStatusChange);
    }

    // NOVOS OUVINTES: Escutam as MUDANÇAS nos campos de TOTAL
    if (totalEpisodiosInput) {
        totalEpisodiosInput.addEventListener('input', autoFillProgress);
    }
    if (totalCapitulosInput) {
        totalCapitulosInput.addEventListener('input', autoFillProgress);
    }

    // Executa as funções uma vez no início para garantir o estado correto da página
    handleTipoChange();
    handleStatusChange();
});