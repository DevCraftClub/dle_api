<<<<<<< New base: Update README.md
<?php

declare(strict_types=1);

/**
 * Маршруты API v2.
 *
 * @var \Slim\App $app
 */

use Slim\Routing\RouteCollectorProxy;
use DleApi\Http\V2\Controller\ApiKeyCheckController;
use DleApi\Http\V2\Controller\MeController;
use DleApi\Http\V2\Controller\OauthController;
use DleApi\Http\V2\Controller\ResourceController;
use DleApi\Http\V2\Controller\TableCrudController;
use DleApi\Http\V2\Controller\UploadController;
use DleApi\Http\V2\Controller\XfieldController;
use DleApi\Http\V2\Middleware\ApiKeyAuthMiddleware;
use DleApi\Http\V2\Middleware\BearerAuthMiddleware;

require_once DLEPlugins::Check(API_ROOT . '/src/Fluent/functions.php');

$app->group('', function (RouteCollectorProxy $group) {
	$oauth = new OauthController();
	$group->post('/oauth/token[/]', [$oauth, 'token']);
	$group->post('/oauth/revoke[/]', [$oauth, 'revoke']);
	$group->map(['GET', 'POST'], '/oauth/authorize[/]', [$oauth, 'authorize']);
	$group->get('/.well-known/oauth-authorization-server[/]', [$oauth, 'discovery']);
	$group->get('/health[/]', [new ResourceController(), 'health']);
});

$app->group('', function (RouteCollectorProxy $group) {
	$group->get('/key/check[/]', [new ApiKeyCheckController(), 'check']);
})->add(new ApiKeyAuthMiddleware());

$app->group('', function (RouteCollectorProxy $group) {
	$table    = new TableCrudController();
	$resource = new ResourceController();
	$xf       = new XfieldController();
	$up       = new UploadController();
	$me       = new MeController();
	$oauth    = new OauthController();

	$group->get('/me[/]', [$me, 'me']);
	$group->get('/oauth/userinfo[/]', [$oauth, 'userinfo']);

	$group->get('/post[/]', [$resource, 'listPosts']);
	$group->get('/post/{id}[/]', [$resource, 'getPost']);
	$group->post('/post[/]', [$resource, 'createPost']);
	$group->post('/user[/]', [$resource, 'createUser']);
	$group->post('/usergroup[/]', [$resource, 'createUsergroup']);
	$group->post('/plugin[/]', [$resource, 'createPlugin']);
	$group->get('/conversations[/]', [$resource, 'conversations']);

	$group->get('/table/{name}[/]', [$table, 'list']);
	$group->get('/table/{name}/{id}[/]', [$table, 'get']);
	$group->post('/table/{name}[/]', [$table, 'create']);
	$group->put('/table/{name}/{id}[/]', [$table, 'update']);
	$group->delete('/table/{name}/{id}[/]', [$table, 'delete']);

	$group->post('/upload[/]', [$up, 'upload']);

	$group->get('/xfields/{scope}[/]', [$xf, 'list']);
	$group->post('/xfields/{scope}/encode[/]', [$xf, 'encode']);
	$group->get('/xfields/{scope}/{name}[/]', [$xf, 'get']);
	$group->post('/xfields/{scope}[/]', [$xf, 'create']);
	$group->put('/xfields/{scope}/{name}[/]', [$xf, 'put']);
	$group->patch('/xfields/{scope}/{name}[/]', [$xf, 'patch']);
	$group->delete('/xfields/{scope}/{name}[/]', [$xf, 'delete']);
})->add(new BearerAuthMiddleware());
|||||||
=======
<?php

declare(strict_types=1);

/**
 * Маршруты API v2.
 *
 * @var \Slim\App $app
 */

use Slim\Routing\RouteCollectorProxy;
use DleApi\Http\V2\Controller\ApiKeyCheckController;
use DleApi\Http\V2\Controller\MeController;
use DleApi\Http\V2\Controller\OauthController;
use DleApi\Http\V2\Controller\ResourceController;
use DleApi\Http\V2\Controller\TableCrudController;
use DleApi\Http\V2\Controller\UploadController;
use DleApi\Http\V2\Controller\XfieldController;
use DleApi\Http\V2\Middleware\ApiKeyAuthMiddleware;
use DleApi\Http\V2\Middleware\BearerAuthMiddleware;

require_once DLEPlugins::Check(API_ROOT . '/src/Fluent/functions.php');

$app->group('', function (RouteCollectorProxy $group) {
	$oauth = new OauthController();
	$group->post('/oauth/token[/]', [$oauth, 'token']);
	$group->post('/oauth/revoke[/]', [$oauth, 'revoke']);
	$group->map(['GET', 'POST'], '/oauth/authorize[/]', [$oauth, 'authorize']);
	$group->get('/.well-known/oauth-authorization-server[/]', [$oauth, 'discovery']);
	$group->get('/health[/]', [new ResourceController(), 'health']);
});

$app->group('', function (RouteCollectorProxy $group) {
	$group->get('/key/check[/]', [new ApiKeyCheckController(), 'check']);
})->add(new ApiKeyAuthMiddleware());

$app->group('', function (RouteCollectorProxy $group) {
	$table    = new TableCrudController();
	$resource = new ResourceController();
	$xf       = new XfieldController();
	$up       = new UploadController();
	$me       = new MeController();
	$oauth    = new OauthController();

	$group->get('/me[/]', [$me, 'me']);
	$group->get('/oauth/userinfo[/]', [$oauth, 'userinfo']);

	$group->get('/post[/]', [$resource, 'listPosts']);
	$group->get('/post/{id}[/]', [$resource, 'getPost']);
	$group->post('/post[/]', [$resource, 'createPost']);
	$group->post('/user[/]', [$resource, 'createUser']);
	$group->post('/usergroup[/]', [$resource, 'createUsergroup']);
	$group->post('/plugin[/]', [$resource, 'createPlugin']);
	$group->get('/conversations[/]', [$resource, 'conversations']);

	$group->get('/table/{name}[/]', [$table, 'list']);
	$group->get('/table/{name}/{id}[/]', [$table, 'get']);
	$group->post('/table/{name}[/]', [$table, 'create']);
	$group->put('/table/{name}/{id}[/]', [$table, 'update']);
	$group->delete('/table/{name}/{id}[/]', [$table, 'delete']);

	$group->post('/upload[/]', [$up, 'upload']);

	$group->get('/xfields/{scope}[/]', [$xf, 'list']);
	$group->post('/xfields/{scope}/encode[/]', [$xf, 'encode']);
	$group->get('/xfields/{scope}/{name}[/]', [$xf, 'get']);
	$group->post('/xfields/{scope}[/]', [$xf, 'create']);
	$group->put('/xfields/{scope}/{name}[/]', [$xf, 'put']);
	$group->patch('/xfields/{scope}/{name}[/]', [$xf, 'patch']);
	$group->delete('/xfields/{scope}/{name}[/]', [$xf, 'delete']);
})->add(new BearerAuthMiddleware());
>>>>>>> Current commit: Начало обновления до api v2
