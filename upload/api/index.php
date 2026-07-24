<?php

declare(strict_types=1);

/**
 * Корневая точка /api — редирект на v2 (fallback без rewrite).
 */

$target = '/api/v2/';
header('Location: ' . $target, true, 308);
header('Content-Type: application/json; charset=utf-8');
echo json_encode([
	'redirect' => $target,
	'message'  => 'Используйте /api/v2 с Authorization: Bearer.',
], JSON_UNESCAPED_UNICODE);
