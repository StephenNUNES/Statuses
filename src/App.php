<?php

use Exception\ExceptionHandler;
use Exception\HttpException;
use Routing\Route;
use View\TemplateEngineInterface;
use Http\Request;
use Http\Response;

class App
{
    /**
     * @var array
     */
    private $routes = array();

    /**
     * @var TemplateEngineInterface
     */
    private $templateEngine;

    /**
     * @var boolean
     */
    private $debug;

    /**
     * @var statusCode
     */
    private $statusCode;
    
    
    private $events = [];
    

    public function __construct(TemplateEngineInterface $templateEngine, $debug = false)
    {
        $this->templateEngine = $templateEngine;
        $this->debug          = $debug;

        $exceptionHandler = new ExceptionHandler($templateEngine, $this->debug);
        set_exception_handler(array($exceptionHandler, 'handle'));
    }

    /**
     * @param string $template
     * @param array  $parameters
     * @param int    $statusCode
     *
     * @return string
     */
    public function render($template, array $parameters = array(), $statusCode = 200)
    {
        $this->statusCode = $statusCode;

        return $this->templateEngine->render($template, $parameters);
    }

    /** Appel d'une requête HTTP d'obtention d'informations avec le verbe GET
     * @param string   $pattern URI de la requête
     * @param callable $callable Controlleur associé à la requête
     *
     * @return App L'instance courante de la class App
     */
    public function get($pattern, $callable)
    {
        $this->registerRoute(Request::GET, $pattern, $callable);
        return $this;
    }

    /** Appel d'une requête HTTP d'ajout d'informations avec le verbe POST
     * @param string   $pattern URI de la requête
     * @param callable $callable Controlleur associé à la requête
     *
     * @return App L'instance courante de la class App
     */
    public function post($pattern, $callable)
    {
        $this->registerRoute(Request::POST, $pattern, $callable);
        return $this;
    }

    /** Appel d'une requête HTTP de suppresions d'informations avec le verbe GET
     * @param string   $pattern URI de la requête
     * @param callable $callable Controlleur associé à la requête
     *
     * @return App L'instance courante de la class App
     */
    public function delete($pattern, $callable)
    {
        $this->registerRoute(Request::DELETE, $pattern, $callable);
        return $this;
    }

    /** Appel d'une requête HTTP de modifications d'informations avec le verbe PUT
     * @param string   $pattern URI de la requête
     * @param callable $callable Controlleur associé à la requête
     *
     * @return App L'instance courante de la class App
     */
    public function put($pattern, $callable)
    {
        $this->registerRoute(Request::PUT, $pattern, $callable);
        return $this;
    }

     /**
     * Méthode de lancement de l'application.
     * Lors de l'appel de cette méthode, le verbe HTTP est récupéré, ainsi que
     * l'URI associée à la requête
     *
     *
     */
    public function run(Request $request = null)
    {
        if (null === $request) {
            $request = Request::createFromGlobals();
        }
        
        foreach ($this->routes as $route) {
            if ($route->match($request->getMethod(), $request->getUri())) {
                return $this->process($route, $request);
            }
        }

        throw new HttpException(404, 'Page Not Found');
    }

    /**
     * @param Route $route
     */
    private function process(Route $route, Request $request)
    {
        $this->dispatch('process.before', [ $request ]);
        $arguments = $route->getArguments();
        array_unshift($arguments, $request);
        try {
            $response = new Response(call_user_func_array($route->getCallable(), $arguments),
                http_response_code($this->statusCode));
            $response->send();
        } catch (HttpException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new HttpException(500, null, $e);
        }
    }

    /** Sauvegarde une nouvelle instance de la classe Route dans le tableau $routes attribut de la classe courante
     * @param string   $method Verbe HTTP utilisé
     * @param string   $pattern Route vers la ressource
     * @param callable $callable Controlleur invoqué
     */
    private function registerRoute($method, $pattern, $callable)
    {
       $newRoute = new Route($method, $pattern, $callable);
       array_push($this->routes, $newRoute);
    }
    
    public function redirect($to, $statusCode = 302)
    {
        http_response_code($statusCode);
        header(sprintf('Location: %s', $to));

        die;
    }
    
    public function addListener($name, $callable)
    {
        $this->events[$name][] = $callable;
    }

    public function dispatch($name, array $arguments = [])
    {
        foreach ($this->events[$name] as $callable) {
            call_user_func_array($callable, $arguments);
        }
    }
}
