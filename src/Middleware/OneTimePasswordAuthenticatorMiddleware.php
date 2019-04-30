<?php
declare(strict_types=1);
/**
 * Copyright 2010 - 2019, Cake Development Corporation (https://www.cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2010 - 2018, Cake Development Corporation (https://www.cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace CakeDC\Auth\Middleware;

use CakeDC\Auth\Authentication\AuthenticationService;
use CakeDC\Auth\Authenticator\CookieAuthenticator;
use Cake\Core\Configure;
use Cake\Http\Response;
use Cake\Routing\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class OneTimePasswordAuthenticatorMiddleware implements MiddlewareInterface
{
    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $service = $request->getAttribute('authentication');

        if (!$service->getResult() || $service->getResult()->getStatus() !== AuthenticationService::NEED_TWO_FACTOR_VERIFY) {
            return $handler->handle($request);
        }

        $request->getSession()->write(CookieAuthenticator::SESSION_DATA_KEY, [
            'remember_me' => $request->getData('remember_me'),
        ]);

        $url = Router::url(Configure::read('OneTimePasswordAuthenticator.verifyAction'));

        return (new Response())
            ->withHeader('Location', $url)
            ->withStatus(302);
    }
}
