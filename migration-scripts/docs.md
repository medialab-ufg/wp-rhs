# Scripts de Migração de conteúdo

Esta é a documentação do conjunto de scripts para importação do conteúdo da base de dados do Drupal para o WordPress.

Aqui documentamos como desenvolver e rodar o script, assim como uma visão geral do que faz cada passo do script.


## Visão geral

O script de importação do conteúdo é modular, composto por vários passos, ordenados em uma sequência.

Ele pode ser rodado integralmente, passando por todos os passos, ou parcialmente.

Ele pressupõe que você tenha a base de dados do Drupal carregada no seu banco de dados e acessível com as mesmas credenciais utilizadas para acessar o banco de dados do WordPress.

O nome da base de dados do Drupal é definido no wp-config.php, altere essa linha para o nome que vocẽ estiver usando:

```PHP
define('RHS_DRUPALDB', 'rhs_drupal');
```

TODO: Alterar SQL pasa usar o table_prefix e botar opção para o nome da base de dados do drupal.


## Rodando o script

Rode o script pela linha de comando usando o comando php. Exemplo:

```
php import.php
```

Rode ele sem nenhum parâmetro (ou com o parâmero "help") para ver todas as opções. Aqui estão as básicas:

Rodar tudo
```
php import.php all
```

Listar os passos
```
php import.php list
```

Rodar a partir do passo 3
```
php import.php 3
```

Rodar do passo 3 ao 5
```
php import.php 3 5
```

Rodar apenas o passo 4
```
php import.php 4 4
```

Rodar do início até o passo 3
```
php import.php -3
```

## Desenvolvendo o script

Cada passo do script é um arquivo PHP independente dentro da pasta "steps".

Para criar um novo passo:

* Crie um novo arquivo PHP na pasta steps com seu script
* Registre este novo passo no arquivo import.php


### Criando um novo passo

1. Crie um novo arquivo com a extenção php dentro da pasta steps

2. Neste arquivo, faça as rotinas que seu script irá tratar

Neste arquivo, a variável global $wpdb já está disponível.

Utilize o método `$this->log($mensagem)` para imprimir mensagens sobre o andamento do script e dar feedback.

Exemplo:

```PHP
<?php

$this->log('Iniciando substituição');

$wpdb->update( $wpdb->postmeta, ['x' => 'y'], ['w' => 'z'] );

$this->log('Substituição concluída');


```

Também está disponível o método `$this->wpcli($command)` para rodar comandos do WP-Cli.

#### Utilizando um SQL em um passo

Para Queries SQL muito complexas, podemos usar arquivos específicos para isso. Na pasta sql ficam os arquivos SQL que podem ser carregados usando a função `$this->get_sql($name)` dentro do seu passo.

Dentro do SQL, você pode usar algumas marcações para se referir aos nomes das tabelas do WordPress e ao nome do banco de dados do Drupal (definido no wp-config.php).

Coloque dentro de chaves duplas o nome da tabela que quer acessar (ex: posts, postmeta, users) ou `{{drupaldb}}` para se referir a base do Drupal.

Por exemplo, no seu SQL `sql/posts.sql`:

```SQL

INSERT INTO {{posts}} SELECT * from {{drupaldb}}.node;

```

E no arquivo php:

```PHP

$query = $this->get_sql('posts');

$wpdb->query($query);

```



### Registrando um novo passo

1. Abra o arquivo import.php

2. Edite o array $steps e adicione o novo passo

Logo no início do arquivo há a declaração do atributo `$steps` da classe `RHSImporter`.

Adiciona uma entrada neste array, na posição adequada (o script é rodado na ordem deste array).

A chave do item do array é o nome do arquivo (sem a extensão php), e o valor é uma descrição que será exibida na listagem dos passos, para que seja possível identificá-lo.

Exemplo:


```PHP
<?php

/**
 * Script de importação dos dados do Drupal para o WordPress
 * 
 * veja documentação em docs.md nesta mesma pasta
 * 
 */ 

class RHSImporter {

    var $steps = array(
    
        // nomeDoArquivo => Descrição do passo
        
        'posts' => 'Importação básica dos posts',
        'outroPasso' => 'Substituição de X por Y',
        'user-meta' => 'Importação dos metadados dos usuários',
        
    ...

```
