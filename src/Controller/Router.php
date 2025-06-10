<?php
namespace Riotoon\Controller;

use AltoRouter;

class Router
{
    private string $view_path;
    private AltoRouter $router;

    public function __construct(string $view_path)
    {
        $this->view_path = $view_path;
        $this->router = new AltoRouter();
    }

    /**
     * Register GET route
     * @param string $url The URL pattern to match
     * @param string $view The view associeted to route
     * @param string $name The route's name
     * @return Router
     */
    public function get(string $url, string $view, ?string $name = null): self
    {
        $this->router->map('GET', $url, $view, $name);
        return $this;
    }

    /**
     * Register a POST route
     * @param string $url The URL pattern to match
     * @param string $view The view associeted to route
     * @param string|null $name The route's name
     * @return Router
     */
    public function post(string $url, string $view, ?string $name = null): self
    {
        $this->router->map('POST', $url, $view, $name);
        return $this;
    }

    /**
     * Register a route for both GET and POST methods.
     * @param string $url The URL pattern to match.
     * @param string $view The view associated with the route.
     * @param string $name Optional. The name of the route.
     * @return \Riotoon\Controller\Router The Router instance for method chaining.
     */
    public function fallOver(string $url, string $view, ?string $name = null): self
    {
        $this->router->map('GET|POST', $url, $view, $name);
        return $this;
    }

    /**
     * Execute the route and render the associated view
     * @return Router
     */
    public function run(): self
    {
        $match = $this->router->match();
        $router = $this;

        if (is_array($match)) {
            $view = $match['target'];
            $params = $match['params'];
        } else
            $view = "";

        $layout = 'base';

        try {
            ob_start();
            require $this->view_path . DIRECTORY_SEPARATOR . $view . ".php";
            $pg_content = ob_get_clean();
            require $this->view_path . DIRECTORY_SEPARATOR . $layout . '.php';
        } catch (\Exception $e) {
            // $_SESSION['error'] = $e->getMessage();
            die($e->getMessage());
            // header('Location:' . $router->url('error'));
        }

        return $this;
    }

    /**
     * Generate a URL for a named route
     * @param string $name Route's name
     * @param array $params Optional. Associative array of route parameters
     * @return string The generated URL
     */
    public function url(string $name, array $params = []): string
    {
        return $this->router->generate($name, $params);
    }
}