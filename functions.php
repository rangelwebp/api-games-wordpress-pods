<?php

defined('ABSPATH') or die();

// Sliders
// **********
add_action( 'rest_api_init', function () {
    register_rest_route( 'games/v1', '/sliders', array(
      'methods' => 'GET',
      'callback' => 'get_sliders_api',
    ) );
} );

function get_sliders_api() {
  $sliders = array();
  $params = array(
      "limit" => -1,
      "post_status" => "publish",
      "where" => "slider.meta_value = 1"
  );

  $mypod = pods('games', $params);
  // Aqui, você inicializa 'sliders' como um array vazio para garantir que ele seja interpretado como um array
  $sliders['sliders'] = array();
  $sliders['total'] = $mypod->total();

  while ($mypod->fetch()) {
      $slider = array(
          'id' => $mypod->field('id'),
          'nome' => $mypod->field('post_title'),
          'slug' => $mypod->field('slug'),
          'descricao' => wp_strip_all_tags ($mypod->field('post_content')),
          'categoria_principal' => $mypod->field('categoria_principal'),
          'traducao' => $mypod->field('traducao'),
          'nota' => $mypod->field('nota'),
          'classificacao' => $mypod->field('classificacao'),
          'indicacao' => $mypod->field('indicacao'),
          // Certifique-se de verificar se 'imagens_sliders' existe e tem itens antes de acessar [0]
          'imagem' => isset($mypod->field('imagens_sliders')[0]) ? $mypod->field('imagens_sliders')[0]['guid'] : null,
      );
      // Adicionando cada slider ao array de 'sliders', sem usar chave associativa
      $sliders['sliders'][] = $slider;
  }

  return rest_ensure_response($sliders);
}


// Trine Series
// **********
add_action( 'rest_api_init', function () {
    register_rest_route( 'games/v1', '/seriestrines', array(
      'methods' => 'GET',
      'callback' => 'get_trines_api',
    ) );
} );

  function get_trines_api(){
    $result = array();
    
    $params = array(
        "limit" => -1,
        "post_status" => "publish",
        "where" => "trine.meta_value = 1 AND complemento.meta_value = 0"
    );

    $mypod = pods( 'games', $params );
    $result['t'] = array();
    $result['total'] = $mypod->total();

    while ( $mypod->fetch() ) {
        $itemTrine = array(
            'id' => $mypod->field('id'),
            'nome' => $mypod->field('post_title'),
            'thumb' => $mypod->field('thumbnail')['guid'],
            'slug' => $mypod->field('slug'),
            'nota' => $mypod->field('nota'),
        );
        $result['t'][] = $itemTrine;
    }

    return rest_ensure_response ( $result );

  }

// Todos os jogos
// **********
add_action( 'rest_api_init', function () {
    register_rest_route( 'games/v1', '/games', array(
      'methods' => 'GET',
      'callback' => 'get_all_games_api',
    ) );
} );

  function get_all_games_api(){
    $retorno = array();
    
    $params = array(
        "limit" => -1,
        "post_status" => "publish",
    );

    $mypod = pods( 'games', $params );
    $retorno['games'] = array();
    $retorno['total'] = $mypod->total();

    while ( $mypod->fetch() ) {
        $itemGame = array(
            'id' => $mypod->field('ID'),
            'nome' => $mypod->field('post_title'),
            'slug' => $mypod->field('slug'),
            'nota' => $mypod->field('nota'),
            'thumb' => $mypod->field('thumbnail')['guid'],
        );
        $retorno['games'][] = $itemGame;
    }

    return rest_ensure_response ( $retorno );
  }

  // Outros Jogos
// **********
add_action( 'rest_api_init', function () {
  register_rest_route( 'games/v1', '/anothers', array(
    'methods' => 'GET',
    'callback' => 'get_anothers_api',
  ) );
} );

function get_anothers_api(){
  $anothers = array();
  
  $params = array(
      "limit" => -1,
      "post_status" => "publish",
      // "where" => "trine.meta_value =" . 0,
      "where" => "trine.meta_value = 0 AND complemento.meta_value = 0 AND slider.meta_value = 0"
  );

  $mypod = pods( 'games', $params );
  $anothers['total'] = $mypod->total();
  $anothers['games'] = array();

  while ( $mypod->fetch() ) {
      $item = array(
          'id' => $mypod->field('ID'),
          'nome' => $mypod->field('post_title'),
          'thumb' => $mypod->field('thumbnail')['guid'],
          'slug' => $mypod->field('slug'),
          'nota' => $mypod->field('nota'),
      );
      $anothers['games'][] = $item;
  }

  return rest_ensure_response ( $anothers );

}

// Mods, Complementos e Extensoes
// **********
add_action( 'rest_api_init', function () {
  register_rest_route( 'games/v1', '/mods', array(
    'methods' => 'GET',
    'callback' => 'get_mods_api',
  ) );
} );

function get_mods_api(){
  $mods = array();
  
  $params = array(
      "limit" => -1,
      "post_status" => "publish",
      "where" => "complemento.meta_value =" . 1
  );

  $mypod = pods( 'games', $params );
  $mods['total'] = $mypod->total();

  $chaveId = 0;
  while ( $mypod->fetch() ) {
      $chaveId++;
      $mod = array(
          'id' => $mypod->field('ID'),
          'nome' => $mypod->field('post_title'),
          'thumb' => $mypod->field('thumbnail')['guid'],
          'slug' => $mypod->field('slug'),
          'nota' => $mypod->field('nota'),
          'complemento' => $mypod->field('complemento'),
      );
      $mods['mods'][$chaveId] = $mod;
  }

  return rest_ensure_response ( $mods );

}

// Get one game
// **********
add_action( 'rest_api_init', function () {
  register_rest_route( 'games/v1', '/game/(?P<slug>[a-zA-Z0-9-]+)', array(
    'methods' => 'GET',
    'callback' => 'get_game_by_slug_api',
    'args' => array(
      'slug' => array(
        'validate_callback' => function($param, $request, $key) {
          return !empty($param);
        },
        'sanitize_callback' => 'sanitize_text_field'
      ),
    ),
  ) );
});

function get_game_by_slug_api( $request ) {

  $slug = $request['slug'];

  if (empty($slug)) {
    return new WP_Error( 'invalid_request', 'Slug inválido.', array( 'status' => 400 ) );
  }

  $gamepod = pods( 'games', $slug);

  if(!$gamepod->exists()) {
    return new WP_Error( 'game_not_found', 'Jogo não encontrado.', array( 'status' => 404 ) );
  }

  $game = array(
      'id' => $gamepod->field('ID'),
      'nome' => $gamepod->field('post_title'),
      'slug' => $gamepod->field('slug'),
      'descricao' => wp_strip_all_tags ($gamepod->field('post_content')),
      'thumbnail' => $gamepod->field('thumbnail')['guid'],
      'capa' => $gamepod->field('capa')['guid'],
      'plataformas' => $gamepod->field('plataformas'),
      'categoria_principal' => $gamepod->field('categoria_principal'),
      'categorias' => $gamepod->field('categorias'),
      'traducao' => $gamepod->field('traducao'),
      'nota' => $gamepod->field('nota'),
      'classificacao' => $gamepod->field('classificacao'),
      'indicacao' => $gamepod->field('indicacao'),
      'video' => $gamepod->field('video'),
      'lancamento' => $gamepod->field('lancamento'),
      'galeria' => $gamepod->field('galeria'),
  );

  return rest_ensure_response ( $game );
}