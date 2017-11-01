[![Stories in Ready](https://badge.waffle.io/medialab-ufg/wp-rhs.png?label=ready&title=Ready)](https://waffle.io/medialab-ufg/wp-rhs) 
[![Stories in Ready](https://travis-ci.org/medialab-ufg/wp-rhs.svg?branch=continuousIntegration)](https://travis-ci.org/medialab-ufg/wp-rhs) 

# Rede Humaniza SUS - WordPress   

Este é o repositório do projeto Rede Humaniza SUS em WordPress.

Ele contém todo o projeto, incluindo a instalação de todas as dependências via Composer (incluindo o próprio WordPress), o tema da RHS para WP e os Scripts para a migração dos dados do Drupal para o WP.

Abaixo estão as instruções de como montar o ambiente de desenvolvimento e fazer deploy do projeto.

# Documentações

* Script de importação do Drupal: [migration-scripts/docs.md](migration-scripts/docs.md).
* Script de migrações de dados: [migration-scripts/migrations.md](migration-scripts/migrations.md).
* Notificações: [docs/notifications.md](docs/notifications.md).
* RHS-API: [docs/rhs-api.md](docs/rhs-api.md).

## Criando o ambiente de desenvolvimento


### Instale as dependências

Além do git, apache, php, mysql e outras ferramentas básicas, é preciso instalar:

* Composer
* Ruby (para instalar o sass)
* SASS
* wp-cli

##### Linux

```
sudo apt-get install composer ruby
sudo gem install sass

curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
chmod +x wp-cli.phar
sudo mv wp-cli.phar /usr/local/bin/wp
```

##### macOS (Com [Homebrew](https://brew.sh/index_pt-br.html))
```
brew install composer ruby
gem install sass

curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
chmod +x wp-cli.phar
mv wp-cli.phar /usr/local/bin/wp
```

##### Windows

Instaladores:
[Composer](https://getcomposer.org/Composer-Setup.exe), [Ruby](https://rubyinstaller.org/downloads/)

```
gem install sass

cd C:\
composer create-project wp-cli/wp-cli --no-dev
```

Adicione ao [PATH](https://www.java.com/pt_BR/download/help/path.xml) o diretório bin do PHP e MySQL, e também o caminho *C:\wp-cli\bin*

### Clone o Repositório

Primeiramente devemos fazer um clone do repositório. Execute o comando
a seguir:

```
git clone git@github.com:medialab-ufg/wp-rhs.git
```
Acesse o diretório onde se localiza o repositório que foi feito o clone. E
execute o comando para instalar os repositórios do projeto.

```
composer install
```

Agora você está com todas as bibliotecas e classes necessárias da RHS.

### Crie o diretório uploads

Este diretório deve ser criado em ``` public/wp-content/ ```

### Crie e edite o wp-config.php e o .htaccess

O repositório vem com arquivos modelos: wp-config-sample.php e htaccess-sample (ambos no diretório `public`), faça uma cópia para wp-config.php e .htaccess, respectivamente e edite com suas informações.

### Instale o WordPress e importe os dados

Visite o WordPress pelo navegador e siga o passo a passo.

A raíz do site deve ser a pasta `public` do repositório.

Faça uma instalação do WordPress para criar o banco de dados novo.

Importe os dados da Drupal. Veja [migration-scripts/docs.md](migration-scripts/docs.md).


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

### Auto Compilando SASS ao executar git pull

Ao executar o comando git pull você terá de executar o comando para compilar o sass caso tenha alteração do mesmo. Para não ter necessidade disso.

##Execute os seguintes passos:

Dentro da pasta do projeto: wp-rhs/

```
cd .git/hooks/
nano post-merge
```

Cole as seguintes linhas dentro do arquivo criado:
```
#!/bin/sh

# Generate CSS from SASS
bash compile-sass.sh
```


bash compile-sass.sh -> Arquivo que se encontra no diretório do projeto (wp-rhs) e execulta os comandos para compilar o sass.


### Testes

Abra o arquivo `tests/wordpress-tests-lib/wp-tests-config-sample.php`, edite as informações de conexão com banco de dados e salve o arquivo com o nome `wp-tests-config-sample.php`.

**Atenção**: Crie uma base de dados separada exclusivamente para os testes. Ela será apagada e recriada cada vez que você rodar os testes.

Para rodar os testes, basta rodar o script na raíz do repositório:

```
./run-tests.sh
```

### Administrando fixtures

ISSO NÃO ESTÁ EM USO AGORA, PQ ESTAMOS SEMPRE IMPORTANDO DADOS DO DRUPAL...

Durante o desenvolvimento, a equipe compartilha uma base de dados de desenvolvimento e arquivos de fixtures (fotos e etc).

Isso facilita a replicação de um mesmo ambiente por toda a equipe, já que para que seja possível testar e desenvolver algumas funcionalidades é preciso ter coisas criadas no banco e arquivos enviados pelo usuário.

Para isso, desenvolvemos alguns scripts que nos ajudam. Eles estão na pasta wp-scripts.

Você pode ver a documentação completa deles em: [wp-scripts/scripts-docs.md](wp-scripts/scripts-docs.md) , mas os comandos que você mais vai usar são *reset all* e *commit*:

```
./reset all
```

Este comando:

* faz um revert da pasta uploads (remove os arquivos que estão ali, mas que não foram adicionados ao git)
* pega o arquivo base.sql do git ignorando o seu (executa o git revert antes)
* aplica o base.sql (que foi pego do git)
* migra as urls do banco (coloca as urls baseadas no DOMAIN_CURRENT_SITE do seu wp-config.php)
* executa o git pull


```
./commit
```

Este comando:

* adiciona novos arquivos da pasta dev_uploads
* faz git pull
* cria dump do banco (base.sql)
* commita as alterações de arquivos e o dump do banco

*Atenção*: Quando quiser enviar novas features para o repositorio, dê um RESET antes, adicione as features, e, em seguida, dê o commit. Isso evita que você passe por cima de alerações de outras pessoas e de incluir coisas desnecessárias ao repositorio.
