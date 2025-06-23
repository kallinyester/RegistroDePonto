function toggleSidebar() {
const sidebar = document.getElementById('sidebar');
sidebar.classList.toggle('active');
    }

function preencherFormulario(id, nome, situacao, data, hora_entrada, hora_saida, total_horas_dia) {
    document.getElementById('id').value = id;        
    document.getElementById('nome').value = nome;
    document.getElementById('situacao').value = situacao;
    document.getElementById('data').value = data;
    document.getElementById('hora_entrada').value = hora_entrada;
    document.getElementById('hora_saida').value = hora_saida;
    document.getElementById('total_horas_dia').value = total_horas_dia;
}

function voltarTopo(){
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}
    