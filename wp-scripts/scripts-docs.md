# WP Scripts

Scripts que nos ajudam a compartilhar fixtures no repositorio


## commit

Quando chamado sem nenhuma opção ele:

* adiciona arquivos da pasta upload (ou blogs.dir)
* faz UPDATE do git
* cria dump do banco (base.sql)
* commita as alterações de arquivos e o dump do banco


### Opções

#### no_add_files

Faz o dum do banco e envia para o repositório, mas não adiciona ao git arquivos que estejam na pasta dev_uploads

* cria dump do banco (base.sql)
* commita as alterações de arquivos e o dump do banco



## reset

Não pode ser chamado sem nenhuma opção

### Opcções


#### reset all:

* faz um revert da pasta uploads
* pega o arquivo base.sql do git ignorando o seu (executa o git revert antes)
* aplica o base.sql (que foi pego do git)
* migra as urls do banco (coloca as urls baseadas no DOMAIN_CURRENT_SITE )
* executa o git UP


#### reset no_revert_uploads:

* não mexe nos uploads e nem dá git pull
* pega o arquivo base.sql do git ignorando o seu (executa o git revert antes)
* aplica o base.sql (que foi pego do git)
* migra as urls do banco (coloca as urls baseadas no DOMAIN_CURRENT_SITE )


#### reset no_revert_db:

* não mexe nos uploads e nem dá git pull
* aplica o base.sql que está na sua pasta db, sem puxar nada do git
* migra as urls do banco (coloca as urls baseadas no DOMAIN_CURRENT_SITE ) 


#### reset no_drop_db:

* não mexe nos uploads e nem dá git pull
* migra as urls do banco (coloca as urls baseadas no DOMAIN_CURRENT_SITE )
* não faz revert do base.sql e nem reimporta o banco





