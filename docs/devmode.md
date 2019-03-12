## Auxílio para modo de desenvolvimento
As etapas listadas neste arquivo são completamente opcionais e não influenciam no comportamento da aplicação.
No entanto, são úteis em modos de desenvolvimento e debug.

#### Compilando e observando ("watching") as mudanças nos arquivos SCSS automaticamente
Basta executar o arquivo ./compile-sass.sh, que encontra-se na raiz do projeto, passando também o parâmetro "w":

`./compile-sass.sh w`

E então não será mais necessário ir para a pasta correta do tema da RHS manualmente e nem iniciar o sass em modo watch.
Altere o estilo conforme desejado e veja as mudanças no navegador.

## Observando mudanças no código para executar os testes automaticamente
Certifique-se que tenha instalado as dependências de dev do composer.json.
Então, da raiz do projeto, execute o comando: `vendor/bin/phpunit-watcher watch`

#### Configurações extra para modo DEBUG
Para começar a usar, vamos incluir o autoload do composer em `$RAIZ/public/wp-config.php`:

    if (WP_DEBUG) {
        require_once __DIR__ . '/../vendor/autoload.php';
    }
    
A partir de agora temos acesso às classes mapeadas no `autoload-dev` do nosso composer.json,
e podemos utilizá-las em nossos temas e plugins personalizados.

É possível estender facilmente as libs/classes do autoload conforme necessidade posteriormente.