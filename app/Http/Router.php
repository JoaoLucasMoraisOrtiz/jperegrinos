<?php

//Responsável por lidar com as rotas do nosso aplicativo

namespace App\Http;

//usado na func addRoute()
use \Closure;
use \Exception;

//usado nas rotas dinâmicas
use \ReflectionFunction;

class Router
{

    /**
     * Variável que guarda a URL completa do nosso aplicativo (raiz)
     * @var string
     */
    private $url = '';

    /**
     * Define o que sempre será igual em nossas rotas
     * @var string
     */
    private $prefix = '';

    /**
     * Índice de rotas do nosso projeto, ou seja, todas as rotas do nosso projeto vão ai dentro;
     * @var array
     */
    private $routes = [];

    /**
     * Instância de Request::class
     * @var Request
     */
    private $request;

    /**  
     *    A func. __contruct() é chamada ao instanciar a classe, e ela toma as ações que definirmos.
     *    Neste caso, ela popula os dados das variáveis privadas da nossa classe.
     *    Recebe um argumento, a url do nosso projeto.
     */
    public function __construct($url)
    {
        $this->request = new Request();
        $this->url = $url;

        //Definimos o prefixo das rotas de forma dinâmica, por meio desta função privada
        $this->setPrefix();
    }

    /**
     * Função que define o prefixo das rotas com base na URL
     * @param string $url
     */
    private function setPrefix()
    {

        //requisita as informações da URL atual
        $parseUrl = parse_url($this->url);

        //a private var prefix recebe o prefixo da URL, ou caso não haja, recebe vazio
        $this->prefix = $parseUrl['path'] ?? '';
    }

    /**
     * Método utilizado para adionar uma rota ao indice de rotas
     * @param string $method
     * @param string $rote
     * @param array $params
     */
    private function addRoute($method, $route, $params = [])
    {
        //passa por todos os itens do array $params
        foreach ($params as $key => $value) {

            // se o valor da presente chave do $params for uma instância de Closure ou seja uma func. anônima:
            if ($value instanceof Closure) {

                //params na key "controller" recebe o valor
                $params['controller'] = $value;

                //excluímos a chave (representada por um número) que contia o valor anteriormente
                unset($params[$key]);
                continue;

                /* 
                    Antes do foreach tinhamos:
                        $params[ [0] => Closure Object, [1] => ... ]
                    Após o foreach, temos:
                        $params[ [controller] => Closure Object, [1] => ...]
                    
                    Ou seja, onde era um Closure Obj, ele foi colocado dentro da key "controller"
                    **lembrando que Clousure é o mesmo que função anônima!!**
                */
            }
        }

        //espaço dentro de params para lidar com variáveis da rota
        $params['variables'] = [];

        /* 
            Definiremos um padrão para as rotas, pois assim podemos lidar com rotas variáveis.
            Por exemplo: Uma rota que aponta para o perfíl de um usuário, 
            poderia ser algo como: localhost:8000/user/$id_user;
            Este Id user é variável, cada um possúi o seu.
            Assim as rotas precisam de um padrão para ver se são válidas,
            pois nem sempre são fixas.

        */

        /* 
            PARA ROTAS VARIÁVEIS:
            define o padrão das rotas pelo $patternVariable;
            em seguida ele verifica as rotas e caso ele encontre variáveis,
            definidas por {} na nossa URL, elas vão ser armazenadas em $matches.
        */
        $patternVariable = '/{(.*?)}/';
        if (preg_match_all($patternVariable, $route, $matches)) {

            //pega a rota encontrada e alocada em $matches, e padroniza ela com o $patternVAriable;
            $route = preg_replace($patternVariable, '(.*?)', $route);

            //coloca ela dentro da área de variáveis de rota
            $params['variables'] = $matches[1];
        }

        /*
            PARA ROTAS FIXAS:
            Receberá a nossa rota, e trocará todas as suas "/" por "\/".
            concatena também em nossa rota "/^"no ínicio e a finaliza com "$/".
            Este será nosso padrão de validação de rotas.
        */
        $patterRoute = '/^' . str_replace('/', '\/', $route) . "$/";

        /*  
            Adiciona a rota dentro do indici de rotas com um padrão:
                routes (todas) [
                    $padrão da rota (/, /home, /about, etc...)[
                        $method (um para cada: GET, POST, PATCH...)[
                            $params (chamada para a página da rota)
                        ]
                    ]
                ]
        */
        $this->routes[$patterRoute][$method] = $params;
    }

    /**
     * Método responsável por retornar os métodos da rota atual
     * @return array
     */
    private function getRoute()
    {

        //recebe a URI
        $uri = $this->getUri();


        //recebe o método da requisição
        $httpMethod = $this->request->getHttpMethod();

        //valia a rota
        foreach ($this->routes as $patterRoute => $methods) {

            //preg_match confere se o padrão de $patternRoute está presente em $uri, e pega as informações da URL se ela for dinâmica
            if (preg_match($patterRoute, $uri, $matches)) {

                //se na rota ouver o metodo HTTP requirido
                if (isset($methods[$httpMethod])) {

                    //retira o indice 0 do $matches caso ele exista
                    unset($matches[0]);

                    //chaves das variáveis em $methods->httpMethod['variables'] (definido em addRoute);
                    $key = $methods[$httpMethod]['variables'];

                    //combina cada chave com seu valor definido em $matches
                    $methods[$httpMethod]['variables'] = array_combine($key, $matches);

                    //adiciona também a nossa request, permitindo assim à acessarmos se nescessário
                    $methods[$httpMethod]['variables']['request'] = $this->request;

                    //retorna a rota
                    return $methods[$httpMethod];
                }
                throw new Exception("Método não permitido!!", 405);
            }
        }
        throw new Exception("URL não encontrada", 404);
    }

    /**
     *Método responsável por retornar a URI desconsiderando o prefixo.
     * @return string
     */
    private function getUri()
    {

        //recebe a URI do request, que é uma instância da classe Request que possuí o método getUri();
        $uri = $this->request->getUri();

        //se existir um prefixo, retira o prefixo da URI, se não a adiciona a $xUri;
        $xUri = strlen($this->prefix) ? explode($this->prefix, $uri) : [$uri];

        //retorna a ultima posição de $xURI, que é a URI sem prefixo
        return end($xUri);
    }
    /* 
        Criando os métodos da classe que serão utilizados;
    */

    /**
     * Método utilizado para fazer as requisições GET em uma rota
     * @param string $route
     * @param array $params
     */
    public function get($route, $params = [])
    {
        $this->addRoute('GET', $route, $params);
    }

    /**
     * Método utilizado para fazer as requisições POST em uma rota
     * @param string $route
     * @param array $params
     */
    public function post($route, $params = [])
    {
        $this->addRoute('POST', $route, $params);
    }

    /**
     * Método utilizado para fazer as requisições PUT em uma rota
     * @param string $route
     * @param array $params
     */
    public function put($route, $params = [])
    {
        $this->addRoute('PUT', $route, $params);
    }

    /**
     * Método utilizado para fazer as requisições DELETE em uma rota
     * @param string $route
     * @param array $params
     */
    public function delete($route, $params = [])
    {
        $this->addRoute('DELETE', $route, $params);
    }

    /**
     * Método utilizado por executar a rota
     * @return Response
     */
    public function run()
    {
        try {

            //$route recebe a rota atual
            $route = $this->getRoute();

            //verifica se há ou não um controlador (obtido com o getRoute())
            if (!isset($route['controller'])) {
                throw new Exception("A URL não pode ser processada", 500);
            }

            $args = [];

            //instancia a classe ReflectionFunction e manda a resposta de $rote['controller'] para dentro de reflection
            $reflection = new ReflectionFunction($route['controller']);

            foreach ($reflection->getParameters() as $parameter) {

                //pega o nome da variável da nossa URL
                $name = $parameter->getName();
                
                //adiciona em args o valor da nossa variável que é pega na chave com o nome dela em $route['variables']
                $args[$name] = $route['variables'][$name] ?? '';
            }

            //retorna a página com alguns argumentos definidos em $args
            return call_user_func_array($route['controller'], $args);
        } catch (Exception $e) {
            return new Response($e->getCode(), $e->getMessage());
        }
    }
}
