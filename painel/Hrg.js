function toggleSidebar() {
const sidebar = document.getElementById('sidebar');
sidebar.classList.toggle('active');
    }

function preencherFormulario(id, nome, situacao, data_rg, hora_entrada) {
    document.getElementById('id').value = id;
    document.getElementById('nome').value = nome;
    document.getElementById('situacao').value = situacao;
    document.getElementById('hora_entrada').value = hora_entrada;
    document.getElementById('data_rg').value = data;
}

function voltarTopo(){
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}
    