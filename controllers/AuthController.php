<?php

namespace Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class AuthController extends BaseController
{
    public function login(Request $request, Response $response, $args): Response
    {
        return $this->render($response, 'login.html.twig');
    }

    public function loginSubmit(Request $request, Response $response, $args): Response
    {
        $data = $request->getParsedBody();
        $user = $this->get('account')->login($data);
        if ($user)
            return $this->redirect($response, 'index');
        return $this->redirect($response, 'account_login');
    }

    public function singup(Request $request, Response $response, $args): Response
    {
        return $this->render($response, 'singup.html.twig');
    }

    public function singupSubmit(Request $request, Response $response, $args): Response
    {
        $data = $request->getParsedBody();
        $user = $this->get('account')->create($data);
        if ($user)
            return $this->redirect($response, 'account_login');
        return $this->redirect($response, 'singup');
    }
}
