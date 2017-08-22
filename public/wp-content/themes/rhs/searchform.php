<?php
/**
 * Template de busca para exibir no menu.
 *
 * @package wp-rhs
 * @subpackage Rede Humaniza SuS
 * @since Rede Humaniza SuS
 */
?>
<form autocomplete="off" class="form-search-rhs navbar-form navbar-left" role="search" action="<?php echo home_url('/'); ?>" method="get" id="menuPesquisa">
    <div class="form-group" style="display: inline;">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Digite aqui o que você procura." size="15" maxlength="128" name="s" id="search" value="<?php the_search_query(); ?>">
            <span class="input-group-btn">
                <button type="submit" class="btn btn-default">
                    <span class="glyphicon glyphicon-search"></span>
                </button>
            </span>
        </div>
    </div>
</form>