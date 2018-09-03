jQuery(function () {
    $ = jQuery;
    jQuery("#parametros").submit(function (event) {
        var filter = [];

        $("div.filter:visible input:checkbox[name=filter]:checked").each(function(){
            filter.push($(this).val());
        });

        $("input[type=date]").each(function () {
            filter.push({date: $(this).val()});
        });

        filter.push({period: $("input:radio[name=filter]:checked").val()});

        jQuery.post(ajax_vars.ajaxurl, {
            action: 'rhs_gen_charts',
            type: jQuery("#type").val(),
            filter: filter
        }).success(function (r) {
            var data = JSON.parse(r);
            create_chart(data, $("#type").val(), $("#chart_type").val());
        });

        event.preventDefault();
    });

    function select_chart_type()
    {
        var type = $("#type").val();
        if(type === 'increasing')
        {
            $("#chart_type").val('line');
        }else if(type === 'user' || type === 'average')
        {
            $("#chart_type").val('bar');
        }
    }

    $("#type").change(function () {
        $("div.filter").hide();
        $("#filter_"+$("#type").val()).show();

        select_chart_type();
    });

    $("#filter_"+$("#type").val()).show();
    select_chart_type();

    function create_title(type)
    {
        var title = "Gráfico de ", tail = '';
        if(type === 'user')
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

    function prepare_data(data, data_type, data_table) {
        var info = [];
        if(data_type === 'user')
        {
            $("div.filter:visible input:checkbox[name=filter]:checked").each(function(){
                var name = $(this).data('name');
                switch ($(this).val()) {
                    case 'all_users':
                        info.push([name, Number(data.all_users)]);
                        break;
                    case 'active':
                        info.push([name, Number(data.active_users)]);
                        break;
                    case 'not_active':
                        info.push([name, Number(data.not_active_users)]);
                        break;
                    case "author":
                        info.push([name, Number(data.author)]);
                        break;
                    case "contributor":
                        info.push([name, Number(data.contributor)]);
                        break;
                    case "voter":
                        info.push([name, Number(data.voter)]);
                        break;
                }
            });

            data_table.addColumn('string', 'Tipo de usuário');
            data_table.addColumn('number', 'Quantidade');
            data_table.addRows(info);
        }else if(data_type === 'increasing')
        {
            var select_types = [];
            data_table.addColumn('string', 'Ano');
            $("div.filter:visible input:checkbox[name=filter]:checked").each(function(){
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
        }else if(data_type === 'average')
        {
            $("div.filter:visible input:checkbox[name=filter]:checked").each(function(){
                var name = $(this).data('name');
                switch ($(this).val()) {
                    case 'all_users':
                        info.push([name, Number(data.all_users)]);
                        break;
                    case "author":
                        info.push([name, Number(data.author)]);
                        break;
                    case "contributor":
                        info.push([name, Number(data.contributor)]);
                        break;
                    case "voter":
                        info.push([name, Number(data.voter)]);
                        break;
                    case "all_posts":
                        info.push([name, Number(data.all_posts)]);
                        break;
                    case "followed":
                        info.push([name, Number(data.followed)]);
                        break;
                    case "comments":
                        info.push([name, Number(data.comments)]);
                        break;
                    case "posts_visits":
                        info.push([name, Number(data.posts_visits)]);
                        break;
                }
            });

            data_table.addColumn('string', 'Info');
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

            var options = set_options(data_type, title);
            drawChart(where, chart_type, data_table, options);
        });
    }

    function drawChart(where, chart_type, data_table, options) {
        var chart;

        if(chart_type === 'bar')
        {
            chart = new google.visualization.ColumnChart(document.getElementById(where));
        }else if (chart_type === 'line')
        {
            chart = new google.visualization.LineChart(document.getElementById(where));
        }

        chart.draw(data_table, options);
    }

    function set_options(data_type, title) {
        var options = {};
        var width = 800, height = 750;
        if(data_type === 'user')
        {
            options = {
                title: title,
                width: width,
                height: height,
                vAxis: {
                    title: 'Quantidade'
                }
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
                }
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
                }
            };
        }

        return options;
    }
});