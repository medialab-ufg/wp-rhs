Esta é a estrutura de notificações do projeto RHS. Ele leva em consideração uma estrutura capaz de integrar com push notifications usando o serviço externo do Firebase. (https://firebase.google.com/docs/cloud-messaging/).

# O problema

Cada usuário vai ter um ícone de notificações, onde vai ver o número de coisas novas que tem para ele, baseado nas coisas que ele segue. Isso vai estar tanto no site, quanto no aplicativo celular.

Algumas notificações podem tb ser enviadas por email pra ele e serão enviadas via "push notification" para o celular.

Precisamos de um esquema que seja leve, pois são muitos usuários e muitos posts, então não dá pra ficar criando uma entrada no banco para cada notificação individual para cada pessoa. (por ex, se milhares de pessoas seguem um usuário ou um post, a ideia é que criemos uma única entrada no banco, e não milhares).

Para conversar bem com o serviço de cloud messaging para o app, também é bom que a gente organize essas notificações em "canais", e aí a gente pode dizer quais usuários acompanham quais canais.

Não precisamos ter um controle de se o usuário leu ou não cada notificação individualmente, podemos simplesmente saber se tem algo novo desde a última vez que checou ou não

# O caminho

Vamos trabalhar com o conceito de canais. Cada notificação é publicada em um canal específico. Quando formos checar se tem alguma notificação para um usuário X, verificamos quais canais ele acompanha, e aí verificamos se tem notificações nesses canais.

Os canais são criados dinamicamente, de acordo com os acontecimentos. Esses são os canais que temos até agora:

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

Além disso, também guardaríamos o ID da última notificação que o usuário viu. Dessa maneira a gente pode verificar se há novas notificações, e quantas notificações há para esse usuário. Essa checagem só é feita na hora em que esse usuário se conecta e imprimimos o ícone de notificações, portanto não pesa para a aplicação ter que calcular isso pra todo mundo nunca.

Agora nós criamos uma nova tabela para as notificações em si, relacionadas aos canais. Essa tabela teria as seguintes colunas:

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


E, fechando a arquitetura, criamos uma tabela para relacionar as notificações aos usuários. Esta tabela tem apenas 3 colunas:

**notf_id**: O id da notificação

**user_id**: O id do usuário

**read**: booleano que indica se ela o usuário já viu esta notificação

Exemplo:

| notf_id  | user_id | read | 
| ------------- | ------------- | ------------ |
| 1 | 50 | true | 
| 1 | 51 | false |
| 2 | 51 | true | 

Esta tabela é alimentada no momento em que vamos checar se há notificações para um determinado usuário. Isso garante que temos uma relação direta entre as notificações e os usuários, que podemos ter um controle das notificações vistas, e que o histórico de notificações é mantido. Por exemplo, se um usuário deixar de acompanhar um canal, ele ainda terá em seu histórico as notificações recebidas naquele canal.


# Principais métodos

Abaixo os principais métodos da classe `RHSNotifications`.

## get_news($user_id)

Retorna as notificações para um usuário, desde a última data em que o usuário checou por notficações

```
global $RHSNotifications;
$news = $RHSNotifications->get_news($user_id);
```

## get_notifications($user_id[, $args])

Retorna as notificações para um usuário. Opcionalmente pode-se passar argumentos para filtrar os resultados.

Os argumentos e seus valores padrão são:

```
 [
'from_datetime' => null, // rertorna notificacoes a partir de uma data
'paged' => null, // numero da pagina. a funcao retorna 50 itens por pagina
'onlyCount' => false, // se true, retorna apenas o numero de notificaoes encontrado, e nao as notificacoes
'onlyUnread' => false, // se true, retorna apenas as notificacoes nao lidas
'onlyRead' => false // se true, retorna apenas as notificacoes lidas
]
```

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

Cada classe destas, deve implementar esses métodos.

**static notify($param)** - método que recebe o que vem do hook que dispara a notificação e adiciona a notificação chamando `RHSNotifications::add_notification`.

**text()** - método que retorna o texto HTML da notificação. Por exemplo: "O usupario X publicou um novo post na comunidade Y". Para isso ele tem o objeto da notificação em `$this` e pode fazer as consultas que quiser para montar esse HTML.

**image()** - retorna o endereço da imagem, há casos que retorna a imagem do avatar do usuário e em outros o thumbnail do post e outras opções podem vir a surgir.

**textPush()** - retorna o texto que será exibido na push notification (notificação para o App de celular). É apenas um texto plano, sem HTML ou links.

**buttons()** - retorna os botões que serão exibidos para cada notificação, sendo composto por `id` e `text`. Deve retornar um ArrayObject. [Tipos de Botões](#botoes-em-notificacoes)

**getImageClass()** - (opcional) Retorna a calsse css que será adicionada a tag img. Por padrão é adicionada "avatar-notification"


No cabeçalho do arquivo há duas linhas de comentários sobre o tipo de notificação que essa classe implementa. Esses comentários atualmente são utilizados para montar a interface de configuração de notificações no aplicativo de celular

exemplo:
```
/**
Description: Notificação ao autor de novo usuário que segue um post
Short description: Novos usuários seguindo seu post
*/
```


No arquivo `inc/notifications/registered-notifications.php` temos a relação dos hooks que geram as notificações. Esses hooks vão disparar o método `notify` do tipo de notificação correspondentes. Este arquivo descreve um `array` onde as chaves são o hook que vão disparar as notificações e os valores são os tipos de notificação que serão gerados.

Eles são informados por um array, onde o primeiro índice é o nome da classe, o segundo é a prioridade do hook e o terceiro é o número de argumentos que o hook vai receber (mesmo formato da função add_action()

por exemplo:

```
'comment_post' => ['comments_in_post'],
'rhs_replied_ticket' => ['replied_ticket', 10, 3],
```

Essa linha indica que o hook `comment_post`, que faz parte do core do WP e é disparado quando um novo comentário é publicado, irá disparar uma notificação do tipo `comments_in_post`. Nesse caso, iria disparar o método `notify` da classe `comments_in_post` que está declarada dentro da pasta `inc/notifications/types`.

# Customizações do OneSignal

A seguir alguns fatores de customização que o OneSignal oferece que podemos investigar. A maioria dos recursos estão descritos na documentação da API, [nesta página](https://documentation.onesignal.com/reference).

## Cor ##
O cor do ícone da notificação é setado em ``android_accent_color``, deve ser uma string sem o ``#``, no formato ARGB, exemplo: ``FF00b4b4``

## Imagem da Notificação ## 
O endereço da imagem em string, seja do post ou do usuário, é inserida em ``large_icon``.

## Passando dados extras

Para enviar informações complementares ao app, que podem ser utilizadas, por exemplo, para o redirecionamento de páginas, basta passar pares "chaves-valor" para o parâmtro `additionalData`.

## Agrupamentos de notificações

O agrupamento é feito através do parâmetro `android_group`, onde é passada uma key indicando o grupo. Podemos ter, por exemplo, agrupamentos pra qualquer notificação da RHS (`android_group = 'rhs'`) ou pra cada tipo de notificações (`android_group = 'rhs_comments_in_post'`). Também é possível customizar a mensagem de agrupamento pelo parâmetro `android_group_message`, por exemplo: `android_group_message = 'Você tem $[notif_count] novos usuários na RHS.'`. 

*Aparentemente não é possível customizar este recurso no iOS.*

**Tags**

Ação | android_group
----- | --------------- 
Comentário no post | rhs_comments_in_post
Novo Post na Comunidade | rhs_new_community_post
Novo Post de Usuário | open_post_new_post_from_user
Novo Post sendo Seguido | rhs_post_followed
Contato respondido | rhs_replied_ticket
Usuário sendo Seguido| rhs_user_follow_author
Post Indicado | rhs_post_recommend


## Colapso de notificações

Caso queiramos garantir que uma notificação substitua uma que foi enviada e o usuário ainda não abriu, podemos passar o parâmetro `collapse_id`. Isso pode ser útil, por exemplo, se uma mensagem de nova versão app foi anunciada, e logo em seguida outra atualização foi oferecida, já que não nos interessa que o usuário veja a antiga. Pode tornar possível tbm a correção de mensagens enviadas equivocadas. Ex.: `collapse_id = 'rhs_new_update'`.


## Botões em notificações

Até 3 botões podem ser exibidos em notificações. Isso pode ser útil para casos onde uma notificação está associada à mais de uma ação, por exemplo:

<table>
  <tr>
    <td colspan="3">Novo comentário no seu Post</td>
  </tr>
  <tr>
    <td colspan="3" >O usuário Fulano comentou no seu post *Uma nov..*</td>
  </tr>
  <tr>
    <td>Ver comentário</td>
    <td>Ver usuário</td>
    <td>Ver post</td>
  </tr>
</table>

Neste caso deve ser passar um array de buttons do tipo:
```
buttons = [
    {"id": "open_comment", "text": "Ver comentário"}, 
    {"id": "open_user", "text": "Ver usuário"}, 
    {"id": "open_post", "text": "Ver post"}
]
```
Os dados serão passados no campo `Action Buttons` para o lado do App, que tratará o evento de clique apropriadamente. Neste caso, o id da página destino deve ser passado também, como campos extras.

**Botões**

Ação | id | text
----- | -- | ----
Comentário no post | open_comments_in_post | Ver Comentário
Comentário no post | open_user_comments_in_post | Ver Usuário
Novo Post de Usuário | open_new_post_from_user | Ver Post
Novo Post de Usuário | open_user_new_post_from_user | Ver Usuário
Novo Post sendo Seguido | open_post_followed | Ver Post
Novo Post sendo Seguido | open_user_post_followed | Ver Usuário
Contato respondido | open_replied_ticket | Ver Resposta
Usuário sendo Seguido | open_user_follow_author | Ver Usuário
Post Promovido | open_post_promoted | Ver Post
Post Indicado | open_post_recommend | Ver Post 
Post Indicado | open_user_post_recommend | Ver Usuário

## Prioridades

