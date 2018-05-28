## Configurações extra para modo DEBUG
As etapas listadas neste arquivo são completamente opcionais e não influenciam no comportamento da aplicação.
No entanto, podem ser úteis em modos de desenvolvimento e debug.

Para começar a usar, vamos incluir o autoload do composer em `$RAIZ/public/wp-config.php`:

    if (WP_DEBUG) {
        require_once __DIR__ . '/../vendor/autoload.php';
    }
    
A partir de agora temos acesso às classes mapeadas no `autoload-dev` do nosso composer.json,
e podemos utilizá-las em nossos temas e plugins personalizados.

É possível estender facilmente as libs/classes do autoload conforme necessidade posteriormente.