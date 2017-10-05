<?php
/**
 * Script de setup inicial da RHS, feitos para executarem uma única vez
 *
 * veja documentação em setup.md nesta mesma pasta
 *
 */
class RHSSetup {

    public $steps = array(
        // nome-do-arquivo => Descrição do passo
        'users-clean-spam' => 'Identifica e marca users SPAM cadastrados.'
    );

    var $from = 0;
    var $to = 0;
    var $run = false;

    function __construct($argv) {
        $this->parse_args($argv);
        $this->validate_args();
        $this->run();
    }
    
    function parse_args($argv) {
        if (!is_array($argv))
            return;

        if (isset($argv[1])) {
            if (is_numeric($argv[1])) {
                $this->from = (int) $argv[1];
                if ($this->from < 0) {
                    $this->to = $this->from * -1;
                    $this->from = 1;
                }
                $this->run = 'range';
            } else {
                $this->run = $argv[1] == 'all' || $argv[1] == 'help' || $argv[1] == 'list' || $argv[1] == 'h' || $argv[1] == '-h' ? $argv[1] : 'help';
            }
        }
        
        if (isset($argv[2])) {
            if (is_numeric($argv[2])) {
                $this->to = (int) $argv[2];
                $this->run = 'range';
            } else {
                $this->run = 'help';
            }
        }
        
        if (isset($argv[3])) {
            $this->run = 'help';
        }
        
        // acerta o range
        if ($this->run == 'range') {
        
            if ($this->from > 0 && $this->to == 0)
                $this->to = sizeof($this->steps);
        
        } elseif ($this->run == 'all') {
            $this->to = sizeof($this->steps);
            $this->from = 1;
        }
    
    }
    
    function validate_args() {
        if ($this->run != 'all' && $this->run != 'range' && $this->run != 'help'  && $this->run != 'list' )
            $this->run = 'help';
        
        if ($this->run == 'all' || $this->run == 'help' || $this->run == 'list')
            return true;
        
        if ($this->run == 'range') {
            if ( ($this->from > 0 || $this->to > 0) ) {
                if ($this->to > 0 && $this->from > $this->to)
                    $this->stop('Argumentos inválidos');
            } else {
                $this->stop('Argumentos inválidos');
            }
        }
    }
    
    function run() {
        switch ($this->run) {
            case 'help':
                $this->print_help();
            case 'list':
                $this->print_list();
                return;
                break;
        }
        
        // Run 
        
        $start = $partial = microtime(true);
        
        // Avoid warnings
        $_SERVER['SERVER_PROTOCOL'] = "HTTP/1.1";
        $_SERVER['REQUEST_METHOD'] = "GET";
        
        define( 'WP_USE_THEMES', false );
        define( 'SHORTINIT', false );
        require( '../public/wp/wp-blog-header.php' );

        global $wpdb;
        $s = 0;
        foreach ($this->steps as $f => $step) {
            $s++;
            if ($s < $this->from)
                continue;
                
            if ($s > $this->to)
                break;
            
            $filename = "setup_steps/$f.php";
            $this->log("==========================================================");
            $this->log("== Iniciando passo $s: $step");
            
            if (!file_exists($filename)) {
                $this->stop("Arquivo $filename não encontrado");
            }

            $set_step = "set_" . str_replace("-", "_", $f);
            $step_already_run = get_option($set_step);

            echo "Verificando '$f' ...\n";
            if( $step_already_run ) {
                echo "'$f' ja foi executado! Pulando etapa ...\n";
            } else {
                echo "Executando $f pela primeira e unica vez ...\n";
                $check = update_option($set_step, true, true);
                if($check) {
                    include($filename);
                    $steptime = microtime(true) - $partial;
                    $partial = microtime(true);
                    $this->log("\t... Sucesso!");
                    $this->log("== Passo $s finalizado em {$steptime}s");
                    $this->log("==========================================================");
                }
            }
        }
        
        $scripttime = microtime(true) - $start;
        
        $this->log("==========================================================");
        $this->log("==========================================================");
        $this->log("=== Fim do script. Tempo de execução {$scripttime}s");
        $this->log("==========================================================");
        $this->log("==========================================================");
    }
    
    function wpcli($command) {
        echo exec('cd ../public && wp ' . $command);
        echo "\n";
    }
    
    function get_steps_list() {
        $list = '';
        $i = 1;
        foreach ($this->steps as $step) {
            $list .= "$i: $step \n";
            $i++;
        }
        
        return $list;
    }
    
    function stop($msg, $print_help = false) {
        $this->log("Erro: $msg");
        
        if ($print_help)
            $this->print_help();
        
        die;
    }
    
    function log($msg) {
        echo $msg . "\n";
    }
    
    function print_list() {
        echo $this->get_steps_list();
    }
    
    function print_help() {
        $steps = $this->get_steps_list();
        echo <<<EOF
        Modo de usar: php rhs_setup.php [help|all|list|\$from] [\$to]
        
        help > Imprime esta mensagem de ajuda.
        list > Lista os passos disponíveis do script
        
        all  > Roda toda ou uma parte da importação. "all" irá rodar toda a importação. 
        Exemplos:
         \$php rhs_setup.php all ## Roda toda a importação
         \$php rhs_setup.php help ## Imprime esta mensagem de ajuda
        
        from > Roda a importação a partir deste passo. Se for um número negativo, roda do começo até este número. Se --to não for especificado, roda até o final
        to   > Roda a importação até este ponto.
        Exemplos
         \$php rhs_setup.php 3 ## Roda a importação do passo 3 em diante, até o fim
         \$php rhs_setup.php 3 5 ## Roda a importação do passo 3 ao 5 (incluindo os passos 3 e 5)
         \$php rhs_setup.php -5 ## Roda a importação início até o passo 5 (incluindo o 5)
        
        Lista dos passos da importação:
EOF;
    
    }


}

$setup = new RHSSetup($argv);
