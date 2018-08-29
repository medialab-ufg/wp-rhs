jQuery(function () {
    $ = jQuery;
    jQuery("#parametros").submit(function (event) {
        var filter = [];

        $("div.filter:visible input:checkbox[name=filter]:checked").each(function(){
            filter.push($(this).val());
        });

        jQuery.post(ajax_vars.ajaxurl, {
            action: 'rhs_gen_charts',
            type: jQuery("#type").val(),
            filter: filter
        }).success(function (r) {
            var data = JSON.parse(r);
            create_chart(data, $("#type").val());
        });

        event.preventDefault();
    });

    $("#type").change(function () {
        $("div.filter").hide();
        $("#filter_"+$("#type").val()).show();
    });
    $("#filter_"+$("#type").val()).show();

    function create_title(type)
    {
        var title = "Gráfico de ", tail = '';
        if(type === 'user')
        {
            tail = "usuários";
        }

        return title+tail;
    }

    function prepare_data(data, data_type, data_table) {
        if(data_type === 'user')
        {
            var info = [];

            $("div.filter:visible input:checkbox[name=filter]:checked").each(function(){
                switch ($(this).val()) {
                    case 'all':
                        info.push(["Total", Number(data.total)]);
                        break;
                    case 'active':
                        info.push(["Ativos", Number(data.active_users)]);
                        break;
                    case 'not_active':
                        info.push(["Não ativos", Number(data.not_active_users)]);
                        break;
                    case "author":
                        info.push(["Autores", Number(data.author)]);
                        break;
                    case "contributor":
                        info.push(["Contribuidores", Number(data.contributor)]);
                        break;
                    case "voter":
                        info.push(["Votantes", Number(data.voter)]);
                        break;
                }
            });

            data_table.addColumn('string', 'Tipo de usuário');
            data_table.addColumn('number', 'Quantidade');
            data_table.addRows(info);
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