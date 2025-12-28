$(document).ready(function() {
    // Inicialização das instâncias dos gráficos
    let graficoVendasPeriodo, graficoTopProdutos, graficoVendasMetodo, graficoVendasAmbiente;

    // Função para gerar cores aleatórias para os gráficos
    const getRandomColor = () => `rgba(${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, 0.7)`;

    function initGraficos() {
        const ctxPeriodo = document.getElementById('grafico_vendas_periodo').getContext('2d');
        graficoVendasPeriodo = new Chart(ctxPeriodo, {
            type: 'bar',
            data: { labels: [], datasets: [{ label: 'Total de Vendas', data: [] }] },
            options: { responsive: true, scales: { y: { beginAtZero: true } } }
        });

        const ctxTopProdutos = document.getElementById('grafico_top_produtos').getContext('2d');
        graficoTopProdutos = new Chart(ctxTopProdutos, {
            type: 'doughnut',
            data: { labels: [], datasets: [{ data: [] }] },
            options: { responsive: true }
        });

        const ctxMetodo = document.getElementById('grafico_vendas_metodo').getContext('2d');
        graficoVendasMetodo = new Chart(ctxMetodo, {
            type: 'pie',
            data: { labels: [], datasets: [{ data: [] }] },
            options: { responsive: true }
        });

        const ctxAmbiente = document.getElementById('grafico_vendas_ambiente').getContext('2d');
        graficoVendasAmbiente = new Chart(ctxAmbiente, {
            type: 'pie',
            data: { labels: [], datasets: [{ data: [] }] },
            options: { responsive: true }
        });
    }

    function carregarDashboard() {
        const filtros = {
            tipo_periodo: $('#filtro_periodo').val(),
            data_inicio: $('#filtro_data_inicio').val(),
            data_fim: $('#filtro_data_fim').val()
        };

        // Vendas por período
        $.ajax({
            url: '../src/consultar/dashboard/vendas_por_periodo.php',
            type: 'GET',
            data: filtros,
            success: function(data) {
                graficoVendasPeriodo.data.labels = data.map(item => item.periodo);
                graficoVendasPeriodo.data.datasets[0].data = data.map(item => item.total);
                graficoVendasPeriodo.data.datasets[0].backgroundColor = data.map(() => getRandomColor());
                graficoVendasPeriodo.update();
            }
        });

        // Top 5 produtos
        $.ajax({
            url: '../src/consultar/dashboard/top_produtos.php',
            type: 'GET',
            data: filtros,
            success: function(data) {
                graficoTopProdutos.data.labels = data.map(item => item.label);
                graficoTopProdutos.data.datasets[0].data = data.map(item => item.quantidade);
                graficoTopProdutos.data.datasets[0].backgroundColor = data.map(() => getRandomColor());
                graficoTopProdutos.update();
            }
        });

        // Vendas por método de pagamento
        $.ajax({
            url: '../src/consultar/dashboard/vendas_por_metodo.php',
            type: 'GET',
            data: filtros,
            success: function(data) {
                graficoVendasMetodo.data.labels = data.map(item => item.label);
                graficoVendasMetodo.data.datasets[0].data = data.map(item => item.total);
                graficoVendasMetodo.data.datasets[0].backgroundColor = data.map(() => getRandomColor());
                graficoVendasMetodo.update();
            }
        });

        // Vendas por ambiente
        $.ajax({
            url: '../src/consultar/dashboard/vendas_por_ambiente.php',
            type: 'GET',
            data: filtros,
            success: function(data) {
                graficoVendasAmbiente.data.labels = data.map(item => item.label);
                graficoVendasAmbiente.data.datasets[0].data = data.map(item => item.total);
                graficoVendasAmbiente.data.datasets[0].backgroundColor = data.map(() => getRandomColor());
                graficoVendasAmbiente.update();
            }
        });
    }

    // Event listener para o botão de filtros
    $('#btn_aplicar_filtros').on('click', carregarDashboard);

    // Preenche as datas com os últimos 30 dias e carrega o dashboard inicial
    function carregarDashboardInicial() {
        const hoje = new Date();
        const trintaDiasAtras = new Date(new Date().setDate(hoje.getDate() - 30));
        
        $('#filtro_data_fim').val(hoje.toISOString().split('T')[0]);
        $('#filtro_data_inicio').val(trintaDiasAtras.toISOString().split('T')[0]);
        
        carregarDashboard();
    }

    initGraficos();
    carregarDashboardInicial();
});
