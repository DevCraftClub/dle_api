<<<<<<< New base: Update README.md
<?php

declare(strict_types=1);

namespace DleApi\Http\V2\Controller;

use DleApi\Http\V2\JsonResponder;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * GET /me и OAuth userinfo — субъект AuthToken.
 */
final class MeController {

	public function __construct(
		private readonly MePayloadBuilder $payloadBuilder = new MePayloadBuilder(),
	) {}

	public function me(Request $request, Response $_response): Response {
		return JsonResponder::ok($this->payloadBuilder->fromRequest($request));
	}

}
|||||||
=======
<?php

declare(strict_types=1);

namespace DleApi\Http\V2\Controller;

use DleApi\Http\V2\JsonResponder;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * GET /me и OAuth userinfo — субъект AuthToken.
 */
final class MeController {

	public function __construct(
		private readonly MePayloadBuilder $payloadBuilder = new MePayloadBuilder(),
	) {}

	public function me(Request $request, Response $_response): Response {
		return JsonResponder::ok($this->payloadBuilder->fromRequest($request));
	}

}
>>>>>>> Current commit: Начало обновления до api v2
