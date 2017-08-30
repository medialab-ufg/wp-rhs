<?php
/**
 * Template de busca para exibir no menu.
 *
 * @package wp-rhs
 * @subpackage Rede Humaniza SuS
 * @since Rede Humaniza SuS
 */
$search_form_action = get_query_var('rhs_busca') == 'users' ? RHSSearch::BASE_USERS_URL : RHSSearch::BASE_URL;
?>
<form autocomplete="off" class="form-search-rhs navbar-form navbar-left" role="search" action="<?php echo home_url($search_form_action); ?>" method="get" id="menuPesquisa">
    <div class="form-group" style="display: inline;">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Digite aqui o que você procura." size="15" maxlength="128" name="s" id="search" value="<?php echo RHSSearch::get_param('keyword'); ?>">
            <span class="input-group-btn">
                <button type="submit" class="btn btn-default">
                    <span class="glyphicon glyphicon-search"></span>
                </button>
            </span>
        </div>
    </div>
</form>