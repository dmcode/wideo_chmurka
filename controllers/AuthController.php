<?php

namespace Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class AuthController extends BaseController
{
    public function login(Request $request, Response $response, $args): Response
    {
        $params = $request->getQueryParams();
        if (isset($params['next']) && strlen($params['next'])>0)
            $this->get('session')->set('next', $params['next']);
        return $this->render($response, 'login.html.twig');
    }

    public function loginSubmit(Request $request, Response $response, $args): Response
    {
        try {
            $data = $request->getParsedBody();
            $this->get('auth')->authenticate($data['email'], $data['password']);
            $next = $this->get('session')->get('next', 'index');
            $this->get('session')->delete('next');
            return $this->redirect($response, $next);
        }
        catch (\InvalidArgumentException) {

        }
        return $this->redirect($response, 'login');
    }

    public function logout(Request $request, Response $response, $args): Response
    {
        $this->get('auth')->logout();
        return $this->redirect($response, 'index');
    }

    public function singup(Request $request, Response $response, $args): Response
    {
        return $this->render($response, 'singup.html.twig');
    }

    public function singupSubmit(Request $request, Response $response, $args): Response
    {
        $data = $request->getParsedBody();
        $user = $this->get('auth')->create($data);
        if ($user)
            return $this->redirect($response, 'login');
        return $this->redirect($response, 'singup');
    }
}
