<?php 

$depara = [
    
    'Águas Claras|Distrito federal' => 'Brasília',
    'Amapari|Amapá' => 'Pedra Branca do Amapari',
    'Anel|Alagoas' => 'Viçosa',
    'Batingas|Alagoas' => 'Arapiraca',
    'Bonsucesso|Rio de Janeiro' => 'Rio de Janeiro',
    'Brazlândia|Distrito federal' => 'Brasília',
    'Canafístula|Alagoas' => 'Palmeira dos Índios',
    'Candangolândia|Distrito federal' => 'Brasília',
    'Ceilândia|Distrito federal' => 'Brasília',
    'Cruzeiro|Distrito federal' => 'Brasília',
    'Eldorado dos Carajás|Pará' => 'Eldorado do Carajás',
    'Embu|São Paulo' => 'Embu das Artes',
    'Fercal|Distrito federal' => 'Brasília',
    'Gama|Distrito federal' => 'Brasília',
    'Graccho Cardoso|Sergipe' => 'Gracho Cardoso',
    'Guará|Distrito federal' => 'Brasília',
    'Itapoã|Distrito federal' => 'Brasília',
    'Jardim Botânico|Distrito federal' => 'Brasília',
    'Lago Norte|Distrito federal' => 'Brasília',
    'Lago Sul|Distrito federal' => 'Brasília',
    'Lagoa do Caldeirão|Alagoas' => 'Igaci',
    'Luziápolis|Alagoas' => 'Teotônio Vilela',
    'Núcleo Bandeirante|Distrito federal' => 'Brasília',
    'Paranoá|Distrito federal' => 'Brasília',
    'Parati|Rio de Janeiro' => 'Paraty',
    'Park Way|Distrito federal' => 'Brasília',
    'Planaltina|Distrito federal' => 'Brasília',
    'Presidente Juscelino|Rio Grande do Norte' => 'Serra Caiada',
    'Recanto das Emas|Distrito federal' => 'Brasília',
    'Riacho Fundo|Distrito federal' => 'Brasília',
    'Riacho Fundo II|Distrito federal' => 'Brasília',
    'Samambaia|Distrito federal' => 'Brasília',
    'Santa Isabel do Pará|Pará' => 'Santa Izabel do Pará',
    'Santa Maria|Distrito federal' => 'Brasília',
    'Santana do Livramento|Rio Grande do Sul' => "Sant'ana do Livramento",
    'São Miguel de Touros|Rio Grande do Norte' => 'São Miguel do Gostoso',
    'São Sebastião|Distrito federal' => 'Brasília',
    'Sobradinho|Distrito federal' => 'Brasília',
    'Sobradinho II|Distrito federal' => 'Brasília',
    'Sudoeste/Octogonal|Distrito federal' => 'Brasília',
    'Taboleiro do Pinto|Alagoas' => 'Maceió',
    'Taguatinga|Distrito federal' => 'Brasília',
    'Vicente Pires|Distrito federal' => 'Brasília',


    
];

$this->log('Adaptando cidades a lista do IBGE');

foreach ($depara as $de => $para) {
    
    $this->log("Substituindo $de por $para");
    $ce = explode('|', $de);
    $c = $wpdb->query( $wpdb->prepare("UPDATE $table SET name = %s WHERE name = %s AND parent_name = %s", $para, $ce[0], $ce[1]));
    $this->log("$c registros");
    
}

 ?>