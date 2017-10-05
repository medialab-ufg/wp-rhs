# Scripts de setup inicial

Os scripts aqui documentados foram feitos para rodar uma única vez - geralmente no setup inicial de uma instalação do RHS. 

## Visão geral

O script de setup é modular, composto por vários passos, ordenados em uma sequência.

Ele pode ser rodado integralmente, passando por todos os passos, ou parcialmente.

Ele pressupõe que você tenha configurado corretamente as credenciais utilizadas para acessar o banco de dados do WordPress.

## Rodando o script

Rode o script pela linha de comando usando o comando php. Exemplo:

```
php rhs_setup.php
```

Rode ele sem nenhum parâmetro (ou com o parâmero "help") para ver todas as opções. Aqui estão as básicas:

Rodar tudo
```
php rhs_setup.php all
```

Listar os passos
```
php rhs_setup.php list
```

Rodar a partir do passo 3
```
php rhs_setup.php 3
```

Rodar do passo 3 ao 5
```
php rhs_setup.php 3 5
```

Rodar apenas o passo 4
```
php rhs_setup.php 4 4
```

Rodar do início até o passo 3
```
php rhs_setup.php -3
```

## Desenvolvendo o script

Cada passo do script é um arquivo PHP independente dentro da pasta "setup_steps".

Para criar um novo passo:

* Crie um novo arquivo PHP na pasta setup_steps com seu script
* Registre este novo passo no arquivo rhs_setup.php


### Criando um novo passo

1. Crie um novo arquivo com a extensão php dentro da pasta setup_steps

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


### Registrando um novo passo

1. Abra o arquivo rhs_setup.php

2. Edite o array $steps e adicione o novo passo

Logo no início do arquivo há a declaração do atributo `$steps` da classe `RHSSetup`.

Adicione uma entrada neste array, na posição adequada (o script é rodado na ordem deste array).

A chave do item do array é o nome do arquivo (sem a extensão php), e o valor é uma descrição que será exibida na listagem dos passos, para que seja possível identificá-lo.

Exemplo:


```PHP
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
        'users-clean-spam' => 'Identifica e marca users SPAM cadastrados.',
        
    ...

```
