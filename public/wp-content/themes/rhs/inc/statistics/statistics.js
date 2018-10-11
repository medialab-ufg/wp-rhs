jQuery(function ($) {
    var selected_filters = "div.filter-:visible input:checkbox[name=filter]:checked";
    jQuery("#parametros").submit(function (event) {
        var filter = [];

        var id_div = $("#id_div").val() ? $("#id_div").val() : "#quantidade";

        $(id_div+" input:checkbox[name=filter]:checked").each(function(){
            filter.push($(this).val());
        });

        $("input[type=date]").each(function () {
            filter.push({date: $(this).val()});
        });

        filter.push({period: $("input:radio[name=filter]:checked").val()});

        var selected_data_chart = jQuery("#selected_data_type").val();
        $("#loader").show();
        jQuery.post(ajax_vars.ajaxurl, {
            action: 'rhs_gen_charts',
            type: selected_data_chart,
            filter: filter
        }).success(function (r) {
            var data = JSON.parse(r);
            create_chart(data, selected_data_chart, $("#chart_type").val());
        });

        event.preventDefault();
    });
    
    function create_chart(data, data_type, chart_type = 'bar', where = 'estatisticas') {
        google.charts.load('current', {'packages':['corechart', chart_type]});
        var title = create_title(data_type);

        google.charts.setOnLoadCallback(function (){
            var data_table = new google.visualization.DataTable();

            var info = prepare_data(data, chart_type, data_type, data_table);

            var options = set_options(data_type, title);
            drawChart(info, where, chart_type, data_table, options);

            $("#loader").hide();
        });
    }

    function drawChart(info, where, chart_type, data_table, options) {
        var chart;

        if(chart_type === 'bar')
        {
            var view = new google.visualization.DataView(info);
            view.setColumns([0,
                1,
                {
                    calc: "stringify",
                    sourceColumn: 1,
                    type: "string",
                    role: "annotation"
                }]);

            var columnWrapper = new google.visualization.ChartWrapper({
                chartType: 'ColumnChart',
                containerId: where,
                dataTable: view,
                options: options
            });

            columnWrapper.draw();
        }else if (chart_type === 'line')
        {
            chart = new google.visualization.LineChart(document.getElementById(where));
            chart.draw(data_table, options);
        }
    }

    function prepare_data(data, chart_type, data_type, data_table) {
        var info = [];
        if(chart_type === 'bar')
        {
            if(data_type === 'count')
            {
                info.push(['Tipo de usuário', 'Quantidade']);
            }else if(data_type === 'average')
            {
                info.push(['Info', 'Quantidade']);
            }

            $(selected_filters).each(function(){
                var name = $(this).data('name');
                info.push([name, Number(data[$(this).val()])]);
            });

            return google.visualization.arrayToDataTable(info);

        }else if(chart_type === 'line')
        {
            var select_types = [];
            data_table.addColumn('string', 'Ano');
            $("div.filter-:visible input:checkbox[name=filter]:checked").each(function(){
                data_table.addColumn('number', $(this).data('name'));
                select_types.push($(this).val());
            });

            for(var date in data)
            {
                var line = [];
                line.push(date);
                for(var type of select_types)
                {
                    if(data[date][type])
                    {
                        line.push(data[date][type]);
                    }else line.push(0);
                }

                info.push(line);
            }

            data_table.addRows(info);
            return info;
        }
    }

    function select_chart_type(type, id_div)
    {
        if(type === 'increasing')
        {
            $("#chart_type").val('line');
        }else if(type === 'count' || type === 'average')
        {
            $("#chart_type").val('bar');
        }

        $("#selected_data_type").val(type);
        $("#id_div").val(id_div);
    }

    $(".nav-pills li").click(function () {
        var div_id = "#"+$(this).find('a').prop('href').split("#")[1];
        $("#id_div").val(div_id);
        select_chart_type($(this).data('type'), div_id);
        $("#parametros").submit();
    });

    select_chart_type(jQuery("li.active").data('type'));

    function create_title(type)
    {
        var title = "Gráfico de ", tail = '';
        if(type === 'count')
        {
            title = "";
            tail = "Número total de usuários, posts e compartilhamentos";
        }else if(type === 'increasing')
        {
            tail = "crescimento por período";
        }else if(type === 'average')
        {
            tail = "média";
        }

        return title+tail;
    }

    function set_options(data_type, title) {
        var options = {}, chartArea = {'width': '90%', 'height': '70%'};
        if($(selected_filters).length <= 2)
        {
            chartArea =  {};
        }
        var width = '100%', height = 600;
        if(data_type === 'count')
        {
            options = {
                title: title,
                width: width,
                height: height,
                vAxis: {
                    title: 'Quantidade'
                },
                chartArea: chartArea,
                legend: { position: 'bottom' },
                colors: ['#00b4b4']
            };
        }else if(data_type === 'increasing')
        {
            options = {
                title: title,
                width: width,
                height: height,
                lineWidth: 3,
                vAxis: {
                    title: 'Quantidade'
                },
                hAxis: {
                    title: 'Período'
                },
                chartArea: {'width': '90%', 'height': '70%'},
                legend: { position: 'bottom' },
                colors: ['#00b4b4', '#CC0000', '#0133FF', '#924790', '#00209F', '#6D9C91', '#D2691E', '#D4AF37', '#FF1493']
            };
        }else if (data_type === 'average')
        {
            options = {
                title: title,
                width: width,
                height: height,
                vAxis: {
                    title: 'Quantidade'
                },
                hAxis: {
                    title: 'Período'
                },
                chartArea: chartArea,
                legend: { position: 'bottom' },
                colors: ['#00b4b4']
            };
        }

        return options;
    }

    $("#show-legends").click(function () {
        $(".legend").toggle();
    });

    $("#parametros").submit();
});