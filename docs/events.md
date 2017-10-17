# Template RHS para Eventos ao Vivo 

### Introdução

Atualmente é possível embedar uma transmissão ao vivo de um vídeo do YouTube num template
do RHS criado especificamente para este fim.
 
Além disso, também é embedado o chat em tempo real do vídeo, desde que:

1) O chat esteja habilitado para a transmissão;

2) A transmissão permita que o chat seja embedado (nas configurações do YouTube);

### Como utilizar

Basta pegar o `ID` do vídeo e utilizá-lo em um shortcode específico do RHS, da seguinte maneira:

Por exemplo, para embedar a transmissão e o chat do vídeo `https://www.youtube.com/watch?v=zLKUaBTpHIg23d` (URL fictícia), basta
selecionarmos apenas o `ID` do mesmo, que é a sequência de caracteres logo após o `watch?v=`.
No caso, o `ID` é `zLKUaBTpHIg23d`.

Com isso, utilizamos o `ID` no seguinte shortcode:

`[rhs_youtube_live id="zLKUaBTpHIg23d"]`

E substituímos o `ID` conforme necessário.

### Flexibilidade de uso

Com o shortcode exemplificado acima, podemos embedar a transmissão em Páginas e Posts conforme necessário, 
diretamente no editor padrão do WordPress.

Porém, é possível fornecer um layout ainda mais adequado para a transmissão do evento ao vivo:

* Basta embedar o shortcode numa página, e selecionar a opção `'Evento ao vivo'`, na sessão de Atributos
da página, na parte de 'Modelo'.

Com isso, a página não exibirá a coluna lateral direita, e toda a área será utilizada para a exibição da transmissão ao vivo e do chat em tempo real.

Como o shortcode é inserido no editor padrão do Wordpress, é possível inserir conteúdo antes ou após o mesmo,
que será renderizado normalmente na nova página.

Vale lembrar que, após a finalização do evento, o chat não será mais exibido.