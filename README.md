# API Simples - Trine Series Fan Br e Outros Jogos da Frozenbyte

A API do Projeto Trine Series Fan Br é uma interface de programação de aplicativos especialmente desenvolvida para fornecer acesso às informações detalhadas dos jogos da série Trine e outros jogos desenvolvidos pela Frozenbyte. 

## Endereço da API

A API está publicamente acessível em: [https://myapis.testar.be/wp-json/games/v1/](https://myapis.testar.be/wp-json/games/v1/)

## Recursos Disponíveis

A API oferece vários endpoints que permitem aos desenvolvedores e fãs acessar:

- **Informações dos Jogos**: Detalhes completos dos jogos, incluindo nome, plataformas, categorias, notas, classificações, imagens, e muito mais.
- **Detalhes Específicos de Jogos**: Acesso a informações detalhadas de um jogo específico através de seu slug.

## Como Consumir a API

Para consumir a API, você pode fazer requisições HTTP GET aos endpoints disponíveis. Aqui estão alguns exemplos de como acessar os recursos:

### Listar Todos os Jogos
GET https://myapis.testar.be/wp-json/games/v1/games

### Listar Todos os Trines
GET https://myapis.testar.be/wp-json/games/v1/trines

### Obter Detalhes de um Jogo Específico
GET https://myapis.testar.be/wp-json/games/v1/game/{slug}

