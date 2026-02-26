function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('active');
}

function preencherFormulario(registro_id, id, nome, situacao, data, hora_entrada, hora_saida) {
    document.getElementById('registro_id').value = registro_id;   
    document.getElementById('id').value = id;        
    document.getElementById('nome').value = nome;
    document.getElementById('situacao').value = situacao;
    document.getElementById('data').value = data;
    document.getElementById('hora_entrada').value = hora_entrada;
    document.getElementById('hora_saida').value = hora_saida;

    console.log({ registro_id, id, nome, situacao, data, hora_entrada, hora_saida });
}

function voltarTopo(){
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}
    