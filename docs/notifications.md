Esta é a estrutura de notificações do projeto RHS. Ele leva em consideração uma estrutura capaz de integrar com push notifications usando o serviço externo do Firebase. (https://firebase.google.com/docs/cloud-messaging/).

# O problema

Cada usuário vai ter um ícone de notificações, onde vai ver o número de coisas novas que tem para ele, baseado nas coisas que ele segue. Isso vai estar tanto no site, quanto no aplicativo celular.

Algumas notificações podem tb ser enviadas por email pra ele e serão enviadas via "push notification" para o celular.

Precisamos de um esquema que seja leve, pois são muitos usuários e muitos posts, então não dá pra ficar criando uma entrada no banco para cada notificação individual para cada pessoa. (por ex, se milhares de pessoas seguem um usuário ou um post, a ideia é que criemos uma única entrada no banco, e não milhares).

Para conversar bem com o serviço de cloud messaging para o app, também é bom que a gente organize essas notificações em "canais", e aí a gente pode dizer quais usuários acompanham quais canais.

Não precisamos ter um controle de se o usuário leu ou não cada notificação individualmente, podemos simplesmente saber se tem algo novo desde a última vez que checou ou não

# O caminho

Vamos trabalhar com o conceito de canais. Cada notificação é publicada em um canal específico. Quando formos checar se tem alguma notificação para um usuário X, verificamos quais canais ele acompanha, e aí verificamos se tem notificações nesses canais.

Os canais são criados dinamicamente, de acordo com os acontecimentos. Esses são os canais que pensamos até agora:

**everyone**: Canal geral, que todo mundo assina por padrão. Uma notificação aqui chega pra todo mundo

**private_for_{$user_id}**: Cada usuário também assina por padrão um canal só seu, para notificações particulares. Por exemplo: "você foi promovido a votante"

**comments_in_post_{$post_id}**: Canal para novos comentários em um post. O autor do post e pessoas que comentam nesse post assinam ele automaticamente.

**user_{$user_id}**: Canal para quem segue um determinado usuário

**community_{$community_id}**: Canal para quem segue uma determinada comunidade.

Os canais que cada usuário assina ficam guardados como um user_meta múltiplo, ou seja, podem haver várias entradas com o mesmo meta_key e vários valores diferentes. Exemplo:

| user_id  | meta_key | meta_value |
| ------------- | ------------- | ------------ |
| 3 | _channels | user_55 |
| 3 | _channels | comments_in_post_44 |

(Nota: os canais padrão everyone e private não precisam ficar no banco)

Além disso, também guardaríamos a data/hora em que ele verificou se havia notificações pela última vez. Dessa maneira a gente pode verificar se há novas notificações, e quantas notificações há para esse usuário. Essa checagem só seria feita na hora em que esse usuário se conectasse e fôssemos imprimir o ícone de notificações, portanto não pesa para a aplicação ter que calcular isso pra todo mundo nunca.

Agora nós criamos uma nova tabela para as notificações em si. Essa tabela teria as seguintes colunas:

**ID**: auto increment

**type**: o tipo de notificação. Com o tempo podemos adicionar mais tipos, isso que vai definir qual o texto da mensagem da notificação, o link, etc

**channel**: em qual canal essa notificação está publicada

**object_id**: A que se refere essa notificação? a um post? um usuário.. bota o ID aqui. Junto com o type, isso vai nos ajudar a montar o link para onde essa notificação nos leva

**user_id**: O ID do usuário que foi o gerador da notificação. Por exemplo, para uma notificação de novo post em uma comunidade, seria o ID do autor do post. Em alguns casos, esse ID não se aplica, por exemplo quando um usuário é promovido, o `user_id` fica 0 (zero). Este ID é útil para evitar que usuários recebam notificações de coisas que eles mesmo fizeram. Por exemplo, se eu comento em um post, gera uma notificação no canal `comments_in_post_$id`. Se eu seguir esse post e não houvesse essa informação, eu receberia uma notificação avisando que eu mesmo fiz um comentário no post. Com essa coluna, conseguimos filtrar para não mostrar aos usuários notificações sobre coisas que eles mesmos fizeram.

**data/hora**: data e hora da notificação

Vamos ver um exemplo de como isso ficaria:

| ID  | type | channel | object_id | user_id | datetime |
| ------------- | ------------- | ------------ | --- | ---- | --- |
| 1 | new_post_from_user | user_55 | 567 | 2 | 12/12/12... |
| 2 | post_promoted | private_for_12 | 33 | 0 | 12/12/12... |
| 3 | new_community_post | community_66 | 78 | 3 | 12/12/12... |


# Principais métodos

Abaixo os principais métodos da classe `RHSNotifications`.

## get_news($user_id)

Retorna as notificações para um usuário, desde a última data em que o usuário checou por notficações

```
global $RHSNotifications;
$news = $RHSNotifications->get_news($user_id);
```

## get_notifications($user_id[, $from_datetime])

Retorna as notificações para um usuário. Opcionalmente pode-se passar uma data de início para checar notificações mais novas do que essa data.

```
global $RHSNotifications;
$notifications = $RHSNotifications->get_notifications($user_id);

foreach ($notifications as $notification) {
    echo $notification->getImage();
    echo $notification->getText();
    echo $notification->getTextDate(); // Formato "Há 2 dias"
}

```

## get_news_number($user_id)

Retorna o número as notificações para um usuário desde a última data em que o usuário checou por notficações

```
global $RHSNotifications;
$news = $RHSNotifications->get_news_number($user_id);
echo $news . " notificações";
```

## add_notification( $channel, $channel_id = null, $type, $object_id, $user_id = 0, $datetime = null )

Cria uma nova notificação em um canal.

Este método deve ser chamado de dentro do método `notify` da classe de um tipo de notificação. Veja documentação abaixo para mais detalhes.

parâmetros:

**$channel** - o canal onde vai ser inserida a notificação. Deve-se usar uma das constantes da classe, que trazem a string que representa o canal:
```
const CHANNEL_EVERYONE = 'everyone';
const CHANNEL_PRIVATE = 'private_for_%s';
const CHANNEL_COMMENTS = 'comments_in_post_%s';
const CHANNEL_USER = 'user_%s';
const CHANNEL_COMMUNITY = 'community_%s'; 
```
**$channel_id** - o id do canal. Por exemplo, se quiser adicionar uma notificação ao canal privado de um usuário, **$channel** seria o `RHSNotifications::CHANNEL_PRIVATE` e o **$channel_id** seria o ID do usuário.

**$type** - o tipo de notificação. Nome da classe que extende a classe `RHSNotification` e implementa um tipo de notificação. Os tipos de notificação ficam na pasta `inc/notifications/types`

**$object_id** - o ID do objeto a que a notificação se refere. O ID do comentário, do post, do usuário, etc. Depende do tipo de notificação.

**$user_id** - O ID do usuário que gerou a notificação. Quando não se aplica, deixar em branco. Útil para evitar que usuários recebam notificações de suas prórpias ações. (ex: um usuário que publica um post em uma comunidade não quer receber uma notificação que ele mesmo publicou o post, apesar de assinar o canal da comunidade)

**datetime** - Datad a notificação, padrão é a data atual.

## add_user_to_channel( $channel, $channel_id = 0, $user_id ) 

Adiciona um usuário a um canal

**$channel** - o canal onde vai ser inserida a notificação. Deve-se usar uma das constantes da classe, que trazem a string que representa o canal:
```
const CHANNEL_EVERYONE = 'everyone';
const CHANNEL_PRIVATE = 'private_for_%s';
const CHANNEL_COMMENTS = 'comments_in_post_%s';
const CHANNEL_USER = 'user_%s';
const CHANNEL_COMMUNITY = 'community_%s'; 
```
**$channel_id** - o id do canal. Por exemplo, se quiser adicionar uma notificação ao canal privado de um usuário, **$channel** seria o `RHSNotifications::CHANNEL_PRIVATE` e o **$channel_id** seria o ID do usuário.

**$user_id** - ID do usuário

## delete_user_from_channel( $channel, $channel_id = 0, $user_id ) 

Adiciona um usuário a um canal

**$channel** - o canal onde vai ser inserida a notificação. Deve-se usar uma das constantes da classe, que trazem a string que representa o canal:

```
const CHANNEL_EVERYONE = 'everyone';
const CHANNEL_PRIVATE = 'private_for_%s';
const CHANNEL_COMMENTS = 'comments_in_post_%s';
const CHANNEL_USER = 'user_%s';
const CHANNEL_COMMUNITY = 'community_%s'; 
```

**$channel_id** - o id do canal. Por exemplo, se quiser adicionar uma notificação ao canal privado de um usuário, **$channel** seria o `RHSNotifications::CHANNEL_PRIVATE` e o **$channel_id** seria o ID do usuário.

**$user_id** - ID do usuário


# Gerenciando eventos que adicionam e removem usuários dos canais

Algumas ações no site podem fazer com que os usuário se inscrevam ou se desinscrevam de canais. Por exemplo, quando um usuário segue outro, ele passa a se inscrever no canal daquele usuário.

Para gerenciar esses eventos, usamos a classe `RHSNotifications_Channel_Hooks`, que está no arquivo `inc/notifications/channels-hooks.php`.

No método `__construct()` desta classe registramos os hooks da aplicação que vão fazer com que um usuário se inscreva ou desinscreva de um canal. Por exemplo:

```
add_action('rhs_add_user_follow_author', array(&$this, 'rhs_add_user_follow_author'));
add_action('rhs_delete_user_follow_author', array(&$this, 'rhs_delete_user_follow_author'));
```

Acima registramos 2 hooks. Um quando um usuário segue o outro, e outro quando ele deixa de seguir.

Agora, na mesma classe, criamos os métodos que vão tratar esses eventos.
```
/**
 * Quando usuário começa a seguir um autor
 */
function rhs_add_user_follow_author($args) {
    global $RHSNotifications;
    $RHSNotifications->add_user_to_channel(RHSNotifications::CHANNEL_USER, $args['author_id'], $args['user_id'] );
}

/**
 * Quando usuário deixa de seguir um autor
 */
function rhs_delete_user_follow_author($args) {
    global $RHSNotifications;
    $RHSNotifications->delete_user_from_channel(RHSNotifications::CHANNEL_USER, $args['author_id'], $args['user_id']);
}
```


# Tipos de notificação (entendendo e criando novas notificações)

Para cada tipo de notificação, existe uma classe que extende a classe `RHSNotification`. Essas classes ficam dentro da pasta `inc/notifications/types`.

Cada classe destas, deve implementar três métodos.

**static notify($param)** - método que recebe o que vem do hook que dispara a notificação e adiciona a notificação chamando `RHSNotifications::add_notification`.

**text()** - método que retorna o texto HTML da notificação. Por exemplo: "O usupario X publicou um novo post na comunidade Y". Para isso ele tem o objeto da notificação em `$this` e pode fazer as consultas que quiser para montar esse HTML.

**image()** - retorna o html (tag `img`) que representa a notificação. As vezes vai ser a imagem do post, as vezes o avatar do usuário, e outras opções podem vir a surgir.

No arquivo `inc/notifications/registered-notifications.php` temos a relação dos hooks que geram as notificações. Esses hooks vão disparar o método `notify` do tipo de notificação correspondentes. Este arquivo descreve um `array` onde as chaves são o hook que vão disparar as notificações e os valores são os tipos de notificação que serão gerados.

Eles são informados por um array, onde o primeiro índice é o nome da classe, o segundo é a prioridade do hook e o terceiro é o número de argumentos q o hook vai receber (mesmo formato da função add_action()

por exemplo:

```
'comment_post' => ['comments_in_post'],
'rhs_contact_replied' => ['contact_replied', 10, 3],
```

Essa linha indica que o hook `comment_post`, que faz parte do core do WP e é disparado quando um novo comentário é publicado, irá disparar uma notificação do tipo `comments_in_post`. Nesse caso, iria disparar o método `notify` da classe `comments_in_post` que está declarada dentro da pasta `inc/notifications/types`.
