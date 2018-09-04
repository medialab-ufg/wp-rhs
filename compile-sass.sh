#!/bin/bash
 
# Executa o comando 'sass' para verificar se existe (veja http://stackoverflow.com/a/677212/329911)
command -v sass >/dev/null 2>&1 || {
  echo >&2 "SASS parece não estar disponivel. Abortando ...";
  exit 1;
}

# Define o caminho.
cd public/wp-content/themes/rhs/assets/scss/
echo "Compilando Sass..." 
sass style.scss:../../style.css
# sass -E 'UTF-8' rhs_editor_style.scss:../../rhs_editor_style.css / arquivo nao existe
echo "Sass compilado com sucesso. \nAtualize o navegador para ver as mudanças."

exit 0
