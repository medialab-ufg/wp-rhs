jQuery(function () {
    $ = jQuery;
    jQuery("#parametros").submit(function (event) {
        jQuery.post(ajax_vars.ajaxurl, {
            action: 'rhs_gen_charts',
            type: jQuery("#type").val()
        }).success(function (r) {
            var data = JSON.parse(r);
            create_chart(data, jQuery("#type").val());
        });

        event.preventDefault();
    });

    function create_title(type)
    {
        var title = "Gráfico de ", tail = '';
        if(type == 'user')
        {
            tail = "usuários";
        }

        return title+tail;
    }

    function prepare_data(data, data_type, data_table) {
        if(data_type == 'user')
        {
            data_table.addColumn('string', 'Tipo de usuário');
            data_table.addColumn('number', 'Quantidade');
            data_table.addRows([
                ["Total", Number(data.total)],
                ["Ativos", Number(data.active_users)],
                ["Não ativos", Number(data.not_active_users)],
                ["Autores", Number(data.author)],
                ["Contribuidores", Number(data.contributor)],
                ["Votantes", Number(data.voter)]
            ]);
        }
    }

    function create_chart(data, data_type, chart_type = 'bar', where = 'estatisticas') {
        google.charts.load('current', {'packages':['corechart', chart_type]});
        var title = create_title(data_type);

        google.charts.setOnLoadCallback(function (){
            var data_table = new google.visualization.DataTable();
            prepare_data(data, data_type, data_table);
            drawChart(title, where, chart_type, data_table);
        });
    }

    function drawChart(title, where, chart_type, data_table) {
        var chart;

        // Set chart options
        var options = {
            title: title,
            width: 800,
            height: 500,
            vAxis: {
                title: 'Quantidade'
            }
        };

        if(chart_type === 'bar')
        {
            chart = new google.visualization.ColumnChart(document.getElementById(where));
        }

        chart.draw(data_table, options);
    }
});