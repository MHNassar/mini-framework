<?php

namespace Core;

class View
{


    public static function render($view, $args = [])
    {
        extract($args, EXTR_SKIP);

        $file = BASE_PATH . "/App/Views/$view.php";

        if (is_readable($file)) {
            require $file;
        } else {
            throw new \Exception("$file not found");
        }
    }

    /**
     * Render a view template using Twig
     *
     * @param string $template The template file
     * @param array $args Associative array of data to display in the view (optional)
     *
     * @return void
     */
//    public static function renderTemplate($template, $args = [])
//    {
//        static $twig = null;
//
//        if ($twig === null) {
//            $loader = new \Twig_Loader_Filesystem(dirname(__DIR__) . '/App/Views');
//            $twig = new \Twig_Environment($loader);
//        }
//
//        echo $twig->render($template, $args);
//    }
}
