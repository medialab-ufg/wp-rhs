# Rede Humaniza SUS - WordPress

## Criando a ambiente de desenvolvimento


### Instale as dependências

Além do git, apache, php, mysql e outras ferramentas básicas, é preciso instalar:

* Composer
* Ruby (para instalar o sass)
* SASS

Instale o git e o composer caso não tenha instalado:

```
sudo apt-get install composer ruby
sudo gem install sass
```

Também é preciso instalar o wp-cli (mais info em http://wp-cli.org/#installing)

```
curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
chmod +x wp-cli.phar
sudo mv wp-cli.phar /usr/local/bin/wp
```

### Clone o Repositório

Primeiramente devemos fazer um clone do repositório. Execute o comando
a seguir:

```
git clone git@github.com:medialab-ufg/wp-rhs.git
```
Acesse a pasta onde se localiza o repositório que foi feito o clone. E
execute o comando para instalar os repositorios do projeto.

```
composer install
```

Agora você esta com todas as bibliotecas e classes necessárias da RHS.


### Crie o link para a pasta de uploads

```
ln -s dev_uploads public/wp-content/uploads
```

### Crie e edite o wp-config.php e o wp-config-sample.php

O repositório vem com arquivos modelos: wp-config-sample.php e htaccess-sample, faça uma cópia para wp-config.php e .htaccess, respectivamente e edite com suas informações.



### Carregue o banco de dados de desenvolvimento

Entenda mais sobre esse comando ali na seção "Administrando fixtures"

```
cd wp-scripts
./reset all
```


### Compile o SASS

De dentro da pasta themes/rhs/assets/scss execute o comando para compilar 


```
cd public/wp-content/themes/rhs/assets/scss
sass style.scss:../../style.css
```


## Mantendo o ambiente de desenvolvimento

### Compilando o SASS

Durante o desenvolvimento, você quer agilidade para fazer modificações no SASS e vê-las aplicadas na folha de estilo do site.

Para isso, use o comando sass --watch e o sass será compilado automaticamente toda vez que você salvar uma alteração no arquivo fonte

De dentro da pasta themes/rhs/assets/scss execute o comando

```
cd public/wp-content/themes/rhs/assets/scss
sass --watch style.scss:../../style.css
```

### Administrando fixtures

Durante o desenvolvimento, a equipe compartilha uma base de dados de desenvolvimento e arquivos de fixtures (fotos e etc).

Isso facilita a replicação de um mesmo ambiente por toda a equipe, já que para que seja possível testar e desenvolver algumas funcionalidades é preciso ter coisas criadas no banco e arquivos enviados pelo usuário.

Para isso, desenvolvemos alguns scripts que nos ajudam. Eles estão na pasta wp-scripts.

Você pode ver a documentação completa deles em: [wp-scritps/scripts-docs.md](wp-scritps/scripts-docs.md) , mas os comandos que você mais vai usar são *reset all* e *commit*:

```
./reset all
```

Este comando:

* faz um revert da pasta uploads (remove os arquivos que estão ali mas que não foram adicionados ao git)
* pega o arquivo base.sql do git ignorando o seu (executa o git revert antes)
* aplica o base.sql (que foi pego do git)
* migra as urls do banco (coloca as urls baseadas no DOMAIN_CURRENT_SITE do seu wp-config.php)
* executa o git pull


```
commit
```

Este comando:

* adiciona novos arquivos da pasta dev_uploads
* faz git pull
* cria dump do banco (base.sql)
* commita as alterações de arquivos e o dump do banco

*Atenção*: Quando quiser enviar novas features para o repositorio, dê um RESET antes, adicione as features, e, em seguida, dê o commit. Isso evita que você passe por cima de alerações de outras pessoas e de incluir coisas desnecessárias ao repositorio.


