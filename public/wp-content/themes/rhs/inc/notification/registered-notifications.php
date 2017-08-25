<?php

/**
 * Neste arquivo registramos as notificações
 * 
 * As chaves do array são os hooks, que estão espalhados pelo código do sistema. Por exemplo, quando publica um novo post, ou quando umusuário é promovido.
 *
 * Os valores são o tipo de notificação que será gerado. 
 *
 * É preciso que exista uma subclasse de RHSNotification com o nome deste tipo. Estas subclasses ficam na pasta types (ou podem ser incuídas via plugin).
 *
 * A subclasse do tipo de notificação deve implementar um método chamado verify(), que vai receber o parâmetro que o hook passa e criar uma notificação em um ou mais canais.
 * 
 * 
 */

return [

    'rhs_post_promoted' => 'post_promoted'

];