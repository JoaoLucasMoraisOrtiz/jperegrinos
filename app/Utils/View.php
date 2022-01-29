<?php

//arquivo responsável por trazer uma página HTML para o Controller

namespace App\Utils;

class View
{

    /**
     * Variáveis padrões da View
     * @var array
     */
    private static $vars = [];

    /**
     * Método responsável por definir os dados iniciais da classe;
     * @param array
     */
    public static function init($vars = [])
    {
        self::$vars = $vars;
    }

    /* getContentView($arg) é uma função pública e estática, ou seja, não haverá: $var = new getContentView($arg) */

    /**
     * Método responsável por retornar o conteúdo de uma view
     * @param string $view
     * @return string
     */

    private static function getContentView($view)
    {

        /*  
            O parâmetro $view receberá o prefixo pages/ e o nome de uma página - home, about, etc. 
            Então, $file pegará este arquivo.
        */
        $file = __DIR__ . "/../../resources/view/" . $view . ".html";

        //se o arquivo requerido existir
        if (file_exists($file)) {

            
            //ele é retornadp
            return file_get_contents($file);
        } else {

            //caso não exista, se retorna vazio
            return "";
        }
    }

    /* render($arg) é uma função pública e estática, ou seja, não haverá: $var = new render($arg) */

    /**
     * Método responsável por retornar o conteúdo renderizado (com variáveis) de uma view
     * @param string $view
     * @param array $vars (stryng/numeric)
     * @return string
     */
    public static function render($view, $vars = [])
    {

        /* $contentView guarda o conteúdo da view, ele recebe o getContenView($arg) da classe atual - View */
        $contentView = self::getContentView($view);

        /* 
            Une as variáveis padrões definidas em init() com as recebidas do usuário
        */
        $vars = array_merge(self::$vars, $vars);

        /*  
            Pegamos as chaves (nomes) das variáveis do array $vars, e jogamos em $keys, ficando:
            [0] => nome_var1
            [1] => nome_var2
            etc...
        */
        $keys = array_keys($vars);


        /*  
            Mapeamos o array key, e dentro de cada item vamos acrescentar '{{'.$item.'}}', ficando:
            [0] => {{nome_var1}}
            [1] => {{nome_var2}}
            etc...
        */
        $keys = array_map(function ($item) {
            return '{{' . $item . '}}';
        }, $keys);

        /* 
            Retornamos a página ($contentView) alterando nela onde ouver as chaves ($keys),
            com os valores das variáveis ($vars);
        */
        return str_replace($keys, array_values($vars), $contentView);
    }
}
