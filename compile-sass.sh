#!/bin/bash
 
# Executa o comando 'sass' para verificar se existe (veja http://stackoverflow.com/a/677212/329911)
command -v sass >/dev/null 2>&1 || {
  echo >&2 "SASS parece não estar disponivel. Abortando ...";
  exit 1;
}

echo "Compilando Sass ..." 
cd public/wp-content/themes/rhs/assets/scss/
if [ "$1" == "w" ]
then
    echo 'Observando alterações no código ..'
    sass --watch style.scss:../../style.css
else 
	sass style.scss:../../style.css
fi

echo "Sass compilado com sucesso. Atualize o navegador para ver as mudanças."
exit 0