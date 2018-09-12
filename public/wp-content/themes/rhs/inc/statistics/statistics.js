jQuery(function () {
    $ = jQuery;
    jQuery("#parametros").submit(function (event) {
        var filter = [];

        $($("#id_div").val()+" input:checkbox[name=filter]:checked").each(function(){
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
    
    function create_chart(data, data_type, chart_type, where) {
        var chart_type = chart_type || 'bar';
        var where = where || 'estatisticas';

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
            chart = new google.visualization.ColumnChart(document.getElementById(where));
        }else if (chart_type === 'line')
        {
            chart = new google.visualization.LineChart(document.getElementById(where));
        }

        chart.draw(data_table, options);
        /*google.visualization.events.addListener(chart, 'select', function () {
            selectHandler(chart, info);
        });*/
    }

    function prepare_data(data, chart_type, data_type, data_table) {
        var info = [];
        if(chart_type === 'bar')
        {
            $("div.filter-:visible input:checkbox[name=filter]:checked").each(function(){
                var name = $(this).data('name');
                info.push([name, Number(data[$(this).val()])]);
            });

            if(data_type === 'count')
            {
                data_table.addColumn('string', 'Tipo de usuário');
                data_table.addColumn('number', 'Quantidade');
            }else if(data_type === 'average')
            {
                data_table.addColumn('string', 'Info');
                data_table.addColumn('number', 'Quantidade');
            }

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
        }

        data_table.addRows(info);
        return info;
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
            tail = "usuários";
        }else if(type === 'increasing')
        {
            tail = "crescimento";
        }else if(type === 'average')
        {
            tail = "média";
        }

        return title+tail;
    }

    /*function selectHandler(chart, data) {
        var selectedItem = chart.getSelection()[0];
        console.log(data);
        console.log(selectedItem);
        var value = data[selectedItem.row][selectedItem.column];
        console.log('The user selected ' + value);
    }*/

    function set_options(data_type, title) {
        var options = {};
        var width = '100%', height = 750;
        if(data_type === 'count')
        {
            options = {
                title: title,
                width: width,
                height: height,
                vAxis: {
                    title: 'Quantidade'
                },
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
                colors: ['#00b4b4']
            };
        }

        return options;
    }

    $("#parametros").submit();
});