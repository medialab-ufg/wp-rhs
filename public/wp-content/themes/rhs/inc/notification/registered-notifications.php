<?php

/**
 * Neste arquivo registramos as notificações
 * 
 * As chaves do array são os hooks, que estão espalhados pelo código do sistema. Por exemplo, quando publica um novo post, ou quando um usuário é promovido.
 *
 * Os valores são o tipo de notificação que será gerado. Eles são informados por um array, onde o primeiro índice é o nome da classe, o segundo é a prioridade do hook e o terceiro é o número de argumentos q o hook vai receber (mesmo formato da função add_action())
 *
 * É preciso que exista uma subclasse de RHSNotification com o nome deste tipo. Estas subclasses ficam na pasta types (ou podem ser incuídas via plugin).
 *
 * A subclasse do tipo de notificação deve implementar um método estático chamado verify(), que vai receber o parâmetro que o hook passa e criar uma notificação em um ou mais canais.
 * 
 * 
 */

return [

    'rhs_post_promoted'                         => ['post_promoted'],
    'rhs_add_user_follow_post'                  => ['post_followed'],
    'rhs_new_post_in_communities'               => ['new_community_post'],
    'rhs_new_post_from_user'                    => ['new_post_from_user'],
    'comment_post'                              => ['comments_in_post'],
    'comment_unapproved_to_approved'            => ['comments_in_post'],
    'comment_spam_to_approved'                  => ['comments_in_post'],
    'rhs_add_user_follow_author'                => ['user_follow_author'],
    'rhs_ticket_replied'                        => ['replied_ticket', 10, 3],
];