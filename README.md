# **wp-rhs**

**Criando a área de trabalho**

> Atualizando as bibliotecas:

    sudo apt-get update

**1 – Instalando o Apache**

> Em termos mais técnico temos algumas configurações diferentes, caso
> seja utilizado um servidor próprio em vez de uma hospedagem de sites,
> dessa forma serão abordados alguns tópicos de instalação, começando
> pelo Apache. O servidor web Apache está entre os servidores web mais
> populares do mundo. É bem documentado, e tem sido largamente utilizado
> por grande parte da história da web, o que o torna uma excelente
> escolha padrão para hospedagem de um site. Podemos instalar Apache
> facilmente usando o gerenciador de pacotes do Ubuntu, apt . Um
> gerenciador de pacotes permite instalar programas a partir de um
> repositório mantido pelo Ubuntu. Os comandos abaixo, respectivamente,
> atualizam o gerenciador de pacotes e em seguida instala o pacote do
> apache.

    sudo apt-get update
    sudo apt-get install apache2

> Como estamos usando um sudo comando, essas operações são executadas
> com privilégios de root. Ele pedirá sua senha de usuário regular para
> verificar suas intenções. Depois de inserir sua senha, apt irá
> dizer-lhe quais os pacotes que pretende instalar e quanto espaço em
> disco extra que vai pegar. Pressione Y e pressione Enter para
> continuar, e a instalação continuará. Você pode fazer uma verificação
> no local imediatamente para verificar se tudo correu como planeado,
> visitando o endereço de loopback do seu servidor em um navegador da
> Internet:

    http://localhost

**2 – Instalando o MySQL**

> Agora que temos o nosso servidor se web e funcionando, é hora de
> instalar o MySQL. O MySQL é um sistema de gerenciamento de banco de
> dados. Basicamente, ele organizará e proporcionar o acesso a bases de
> dados em que o nosso site pode armazenar informações. Mais uma vez,
> podemos usar apt para adquirir e instalar o nosso software. Desta vez,
> vamos também instalar alguns outros pacotes que irão nos ajudar na
> obtenção de nossos componentes para se comunicar uns com os outros:

    sudo apt-get install mysql-server

> Mais uma vez, será mostrada uma lista dos pacotes que serão
> instalados, assim como a quantidade de espaço em disco eles ocupam.
> Digite Y para continuar. Durante a instalação, o servidor irá pedirlhe
> para selecionar e defina uma senha para o usuário "root" no MySQL .
> Esta é uma conta administrativa no MySQL que todos privilégios. Pense
> nisso como sendo semelhante à conta root para o próprio servidor (o
> que você está configurando agora é uma conta específica do MySQL, no
> entanto). Certifique-se esta é uma senha forte, único, e não a deixe
> em branco. Quando a instalação estiver completa, queremos executar um
> script simples de segurança que removerá alguns padrões perigosas e
> bloquear o acesso ao nosso sistema de banco de dados um pouco. Inicie
> o script interativo executando o comando:

    sudo mysql_secure_installation

> Será apresentado um conjunto de perguntas, você deve pressionar Y e
> pressione a tecla Enter em cada prompt. Isto removerá alguns usuários
> anônimos e o banco de dados de teste, desabilitar logins root remotos,
> e carregar essas novas regras para que o MySQL respeita imediatamente
> as mudanças que fizemos. Neste ponto, o sistema de banco de dados está
> agora configurado e podemos seguir em frente.

**3 – Instalando o PHP**

> O PHP é o componente da nossa configuração que processará o código
> para exibir conteúdo dinâmico. Ele pode executar scripts, conectar aos
> nossos bancos de dados MySQL para obter informações e entregar o
> conteúdo processado para o nosso servidor web para exibir. Podemos
> mais uma vez aproveitar o apt do sistema para instalar nossos
> componentes. Nós vamos incluir alguns pacotes auxiliares, bem como,
> para que o código PHP pode ser executado sob o servidor Apache e se
> conectar com o nosso banco de dados MySQL:

> Adicione o repositóirio:

    sudo add-apt-repository ppa:ondrej/php

> Update:

    sudo apt-get update

> Instalar:

    sudo apt-get install php7.0 libapache2-mod-php7.0 php7.0-mcrypt php7.0-mysql

> Assim o PHP será instalado sem maiores problemas. Na maioria dos
> casos, vamos querer modificar a maneira que o Apache serve arquivos
> quando um diretório é acessado. Atualmente, se um usuário solicita um
> diretório do servidor, o Apache procurará primeiro por um arquivo
> chamado index.html.
> 
> Nós queremos definir que o nosso servidor web a prefira arquivos PHP,
> por isso vamos configurar o Apache a procurar primeiramente um arquivo
> index.php toda vez que um diretório for acessado.  Para fazer isso,
> digite o seguinte comando para abrir o dir.conf arquivo em um editor
> de texto com privilégios de root:

    sudo nano /etc/apache2/mods-enabled/dir.conf

> O arquivo possuirá o seguinte conteúdo:

    <IfModule mod_dir.c> DirectoryIndex index.html index.cgi index.pl
    index.php index.xhtml index.htm </IfModule>

> Queremos mover o arquivo de índice PHP destacado acima para a primeira
> posição após a diretiva DirectoryIndex , para isso o deve ser
> realizada a seguinte alteração no conteúdo do arquivo:

    <IfModule mod_dir.c> DirectoryIndex index.php index.html index.cgi index.pl index.xhtml index.htm
    </IfModule>

> Quando tiver terminado, salve e feche o arquivo pressionando Ctrl-X.
> Você terá que confirmar a salvar digitando Y e pressione a tecla Enter
> para confirmar o arquivo local de salvamento. Depois disso, é preciso
> reiniciar o servidor web Apache para que as nossas mudanças para ser
> reconhecido. Você pode fazer isso com o seguinte comando:

    sudo systemctl restart apache2

> A fim de testar que o nosso sistema está configurado corretamente para
> PHP, podemos criar um script muito básico PHP. Vamos chamar esse
> script info.php . Para que o Apache para encontre o arquivo e o
> carregue corretamente, ele deve ser salvo em um diretório muito
> específico, que é chamado de "web root" ou simplesmente a pasta raiz
> do servidor web. No Ubuntu 14.04, este diretório está localizado em
> /var/www/html/. Podemos criar o arquivo nesse local, digitando o
> comando no terminal:

    sudo nano /var/www/html/info.php

> Isto abrirá um arquivo em branco. Queremos colocar o seguinte texto,
> que é o código PHP válido, dentro do arquivo:

    <?php phpinfo();

> Quando tiver terminado, salve e feche o arquivo. Agora podemos testar
> se o nosso servidor web pode exibir corretamente o conteúdo gerado por
> um script PHP. Para testar isso, só temos de visitar esta página em
> nosso navegador web. O endereço que pretende visitar será:

    http://localhost/index.php

> Esta página basicamente dá-lhe informações sobre o servidor a partir
> da perspectiva do PHP. É útil para depuração e para garantir que suas
> configurações estão sendo aplicadas corretamente.

**4 – Configurando a área de Trabalho**

*"Caso queira usar o ROOT pule para o Passo 4.1"*

> O primeiro passo para a configuração é a criação do banco de dados que
> será utilizado pelo Wordpress. Para é necessário fazer login na conta
> do superusuário “root” no servidor de banco de dados Mysql. Isso pode
> ser feito com o comando:

    mysql -u root -p

> Será solicitada a senha do usuário root que foi definida no momento da
> instalação do Mysql. Efetuando o login com sucesso, será apresentado
> um prompt de comando Mysql. Assim podemos criar um banco de dados
> separado que o WordPress pode controlar. O mesmo pode ter o nome
> desejado, nesse exemplo será chamando apenas de wordpress, porque é
> descritivo e simples. Digite este comando para criar o banco de dados:

    CREATE DATABASE wordpress;

> Toda declaração MySQL deve terminar com um ponto e vírgula (;), de
> modo a garantir que o comando seja executado. Em seguida, vamos criar
> uma conta de usuário do MySQL separado que usaremos exclusivamente
> para operar na nossa nova base de dados. Criação de bases de dados de
> uma função e contas é uma boa ideia do ponto de vista de gestão e
> segurança. A nova conta terá como usuário wordpressuser e sua senha
> password. Você deve definitivamente alterar a senha para a sua
> instalação e pode nomear o usuário com o login que desejar. Este é o
> comando que você precisa para criar o usuário:

    CREATE USER wordpressuser@localhost IDENTIFIED BY 'password';

> Neste ponto, você tem um banco de dados e uma conta de usuário, cada
> um feito especificamente para WordPress. No entanto, esses dois
> componentes têm nenhuma relação ainda. O usuário não tem acesso ao
> banco de dados. Assim é necessário dar totais privilégios a base de
> dados wordpress para o usuário wordpressuser por meio do comando:

    GRANT ALL PRIVILEGES ON wordpress * TO wordpressuser @localhost.;

> Agora o usuário tem acesso total ao banco de dados. Precisamos
> atualizar os privilégios, para que a instância atual do MySQL aplique
> as mudanças nas permissões que executamos:

    FLUSH PRIVILEGES;

> Estamos prontos agora. Podemos sair do prompt do MySQL, com o comando:

    exit

**4.1 Baixando o repositório**

> Instale o git e o composer caso não tenha instalado:

    sudo apt-get install git composer

> Crie sua Key para ser integrada ao Github, execute o comando:

    ssh-keygen

> Visualize sua key e salve no GitHub:

    cat /home/media/.ssh/id_rsa.pub

**4.2 Clonando o Repositório**

> Primeiramente devemos fazer um clone do repositório. Execute o comando
> a seguir:

    git clone git@github.com:medialab-ufg/wp-rhs.git

> Acesse a pasta onde se localiza o repositório que foi feito o clone. E
> execute o comando para instalar os repositorios do projeto.

    composer install

> Agora você esta com todas as bibliotecas e classes necessárias da RHS.

**5 Instalando e configurando o SaSS**

> Primeiro precisamos instalar o Ruby

    sudo apt-get install ruby

> Em seguida adicionamos o node.js ao repositório para ser instalado

    sudo add-apt-repository ppa:chris-lea/node.js

> Realizamos um update

    sudo apt-get update

> Instalamos os componetes necessários

    sudo apt-get install python-software-properties python g++ make nodejs npm ruby-dev

> Instalamos o bower e o grunt-cli pelo npm

    sudo npm install -g bower grunt-cli

> Instalamos o Foundation através do ruby

    sudo gem install foundation

> O compass

    sudo gem install compass

> Instalando o SASS

    sudo gem install sass

> Execulte o comando para ver se está instalado mesmo.

    sass -v

> E irá aparecer a versão do sass. Caso apareça a instalação está OK.

**5.1 Compilando o Sass**

> De dentro da pasta assets/scss execute o comando para compilar e o mesmo irá ficar aberto compilando
> enquanto você vai realizando as modificações

    sass --watch style.scss:../../style.css

**Explicações:**
style.scss *-> arquivo para ser compilado*
../../style.css *-> local e nome que será salvo.*

> Para compilar como minificado execulte o comando:

    sass --watch style.scss:../../style.min.css --style compressed
