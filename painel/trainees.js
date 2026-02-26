document.getElementById('searchInput').addEventListener('input', function () {
    const filtro = this.value.toLowerCase();
    const itens = document.querySelectorAll('#table tbody tr');

    itens.forEach(function (item) {
        const texto = item.textContent.toLowerCase();
        item.style.display = texto.includes(filtro) ? '' : 'none';
    });
});

function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('active');
}

function preencherFormulario(id, nome, situacao) {
    document.getElementById('id').value = id;
    document.getElementById('nome').value = nome;
    document.getElementById('situacao').value = situacao;
}

function limpaUrl() {
    // Pega a URL atual
    const urlAtual = window.location.href;

    // Remove tudo após o '?'
    const urlLimpa = urlAtual.split('?')[0];

    // Substitui a URL atual no histórico do navegador
    window.history.replaceState(null, null, urlLimpa);
}

// Executa a função após 1 segundo
setTimeout(limpaUrl, 1000);

function voltarTopo(){
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}