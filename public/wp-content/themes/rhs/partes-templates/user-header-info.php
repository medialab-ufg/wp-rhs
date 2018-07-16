<?php 
// para esse template é necessário receber o valor da variável $curauth

$curauth = get_queried_object(); //(isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author)); 
?>
<div class="tab-content">
    <div role="tabpanel" class="tab-pane fade in active" id="verDados">
        <div class="jumbotron">
        <?php if ($curauth) { ?>
            <?php
            global $RHSUsers;
            $RHSUsers = new RHSUsers($curauth->ID);
            global $RHSVote;
            global $RHSFollow;

            $total_votos = $RHSVote->get_total_votes_by_author($curauth->ID);

            $total_followed = $RHSFollow->get_total_follows($curauth->ID, RHSFollow::FOLLOWED_KEY);
            $total_follow = $RHSFollow->get_total_follows($curauth->ID, RHSFollow::FOLLOW_KEY);
            $followed_posts = $RHSFollow->get_total_follows($curauth->ID, RHSFollow::FOLLOWED_POSTS_KEY);

            $total_posts = count_user_posts($curauth->ID);
            ?>
        <div class="avatar-user">
            <?php echo get_avatar($RHSUsers->getUserId()); ?>
        </div>
        <div class="info-user">
            <p class="nome-author">
                <?php echo $RHSUsers->get_user_data('display_name'); ?>
                <?php if( is_user_logged_in() && is_author(get_current_user_id())) : ?>
                    <span class="btn-editar-user"><a class="btn btn-default" href="<?php echo home_url(RHSRewriteRules::PROFILE_URL ); ?>">EDITAR</a></span>
                <?php endif; ?>
            </p>
            <p class="localidade">
                <?php echo the_user_ufmun($RHSUsers->getUserId()); ?>
            </p>
            <p class="desde">
                <?php echo ' <span>Membro desde:</span> ' . date("d/m/Y", strtotime(get_the_author_meta('user_registered', $curauth->ID))); ?>
            </p>
            <?php if (count_user_posts($curauth->ID)) { ?>
                <div class="contagem">
                    <span class="contagem-valor-author"><?php echo $total_posts; ?></span>
                    <span class="contagem-desc-author"><?php echo ($total_votos == 1 ? "POST" : "POSTS" );  ?></span>
                </div>
            <?php } ?>
            <?php if ($total_votos) { ?>
                <div class="contagem">
                    <span class="contagem-valor-author"><?php echo $total_votos; ?></span>
                    <span class="contagem-desc-author"><?php echo ($total_votos == 1 ? "VOTO" : "VOTOS" );  ?></span>
                </div>
            <?php } ?>
            
            <div class="contagem">
                <a class="btn-link" href="<?php echo get_author_posts_url($curauth->ID) . RHSRewriteRules::FOLLOW_URL; ?>">
                    <span class="contagem-valor-author"><?php echo $total_follow ?></span>
                    <span class="contagem-desc-author">SEGUINDO</span>
                </a>
            </div>
            
            <div class="contagem">
                <a class="btn-link" href="<?php echo get_author_posts_url($curauth->ID) . RHSRewriteRules::FOLLOWED_URL; ?>">
                    <span class="contagem-valor-author"><?php echo $total_followed ?></span>
                    <span class="contagem-desc-author"><?php echo ($total_followed == 1 ? "SEGUIDOR" : "SEGUIDORES" );  ?></span>
                </a>
            </div>

            <?php
            $_post_author_id = get_the_author_meta('ID');
            if ($_post_author_id == get_current_user_id()) { ?>
            <div class="contagem">
                <a class="btn-link" href="<?php echo get_author_posts_url($curauth->ID) . RHSRewriteRules::FOLLOWED_POSTS_URL; ?>">
                    <span class="contagem-valor-author"><?php echo $followed_posts; ?></span>
                    <span class="contagem-desc-author"><?php echo "POSTS SEGUIDOS"; ?></span>
                </a>
            </div>
            <?php } ?>
            
        </div>
        <span class="seguir-mensagem">
            <?php do_action('rhs_author_header_actions', $curauth->ID); ?>
        </span>
        <div class="clearfix"></div>
        <?php } else { ?>
            <div class="user-unknown">Esse usúario não existe !</div>
        <?php } ?>
        </div>
    </div>
</div>