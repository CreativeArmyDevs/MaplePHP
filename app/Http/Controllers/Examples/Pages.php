<?php

namespace Http\Controllers\Examples;

use MaplePHP\Http\Interfaces\ResponseInterface;
use MaplePHP\Http\Interfaces\RequestInterface;
use MaplePHP\Foundation\Http\Provider;
use Http\Controllers\BaseController;

class Pages extends BaseController
{
    protected $url;
    protected $responder;
    protected $users;

    public function __construct(Provider $provider)
    {
    }

    /**
     * The start page see router
     * @param  ResponseInterface $response PSR-7 Response
     * @param  RequestInterface  $request  PSR-7 Request
     * @return ResponseInterface
     */
    public function start(ResponseInterface $response, RequestInterface $request): ResponseInterface
    {
        // Overwrite default meta
        // Meta is propagated by Models/Navbar and then meta it self in the middleware "DomManipulation" where
        // some standard DOM element is preset.
        //$this->head()->getElement("title")->setValue("Welcome to my awesome app");
        //$this->head()->getElement("description")->attr("content", "Some text about my awesome app");

        $this->view()->setPartial("breadcrumb", [
            "tagline" => getenv("APP_NAME"),
            "name" => "Welcome to MaplePHP",
            "content" => "Get ready to build you first application."
        ]);

        // Auto clear cache on update and on a future pulish date!
        // withLastModified will only work with the middleware "LastModifiedHandler"
        // It will tho automatically be turned off IF session is open to make sure no important
        // information stays in cache.
        // return $response->withLastModified("2023-09-04 14:30:00")
        // ->withExpires($this->date()->withValue("+1 year")->format("Y-01-01"));
        return $response;
    }

    /**
     * The about page (see router)
     * @param  ResponseInterface $response PSR-7 Response
     * @param  RequestInterface  $request  PSR-7 Request
     * @return ResponseInterface
     */
    public function about(ResponseInterface $response, RequestInterface $request): ResponseInterface
    {
        $this->view()->setPartial("breadcrumb", [
            "tagline" => "Layered structure MVC framework",
            "name" => "MaplePHP",
            "content" => "MaplePHP is a layered structure PHP framework that has been meticulously crafted to " .
            "provide developers with an intuitive, user-friendly experience that doesn't compromise on performance " .
            "or scalability. By leveraging a modular architecture with full PSR interface support, the framework " .
            "allows for easy customization and flexibility, enabling developers to pick and choose the specific " .
            "components they need to build their applications."
        ]);

        // Browser cache content up to an hour
        // This will work even with a session open so be careful
        // return $response->setCache($this->date()->getTimestamp(), 3600);
        return $response;
    }

    /**
     * Will be invoked if method in router is missing
     * @param  ResponseInterface $response
     * @param  RequestInterface  $request
     * @return ResponseInterface
     */
    public function __invoke(ResponseInterface $response, RequestInterface $request): object
    {
        $_response = $response->withHeader("Content-type", "application/json; charset=UTF-8");

        // Repaint the whole HTML document with:
        // @response->getBody()->write("New content...")
        // @responder->build(), will do as above but read current responder json data
        return $this->responder()->build();
    }
}
