<?php

declare(strict_types=1);

namespace DevCraft\Modules\DleApi\Ajax;

use DevCraft\Core\Application;
use DevCraft\Core\Config\Paths;
use DevCraft\Core\Http\AjaxRequest;
use DevCraft\Core\Http\JsonResponse;
use DevCraft\Core\Support\DataManager;
use DevCraft\Core\Config\DevCraftConfig;
use DevCraft\Core\Admin\SettingsFormService;
use DevCraft\Core\Interfaces\ResponseInterface;
use DevCraft\Core\Interfaces\AjaxHandlerInterface;

/**
 * Сохранение настроек DLE API.
 */
final class SettingsHandler implements AjaxHandlerInterface {

	public function handle(AjaxRequest $request): ResponseInterface {
		$plugin = Application::instance()->registry()->forMod($request->mod);
		$schema = $plugin?->settingsSchema();

		if($schema === null) {
			return JsonResponse::fail(__('Ошибка'), __('Схема настроек недоступна'), 'validation');
		}

		$configDir  = Paths::config();
		$configFile = $configDir . '/' . $schema->codename . '.json';

		if(is_file($configFile) && !is_writable($configFile)) {
			return JsonResponse::fail(__('Ошибка'), __('Файл конфигурации недоступен для записи'), 'validation', 500);
		}

		if(!is_dir($configDir) && !DataManager::createDir($configDir)) {
			return JsonResponse::fail(__('Ошибка'), __('Каталог конфигурации недоступен для записи'), 'validation', 500);
		}

		$service = new SettingsFormService();
		$result  = $service->validatePartial($request->data, $schema);

		if($result['valid'] === [] && $result['errors'] !== []) {
			return JsonResponse::fail(__('Ошибка'), __('Все поля недействительны'), 'validation', 422, ['fields' => $result['errors']]);
		}

		if($result['valid'] !== []) {
			$existing = DataManager::getConfig($schema->codename, null, 'dleapi');
			$merged   = array_merge(is_array($existing) ? $existing : [], $result['valid']);

			if(($merged['secret'] ?? '') === '') {
				$merged['secret'] = bin2hex(random_bytes(16));
			}

			if(\DevCraft\Modules\DleApi\Services\DleApiConfig::isDemoMode()) {
				$merged['secure'] = true;
			}

			DataManager::saveConfig($schema->codename, $merged);
			DevCraftConfig::resetCache();
			\DevCraft\Modules\DleApi\Services\DleApiConfig::resetCache();
		}

		if(function_exists('clear_cache')) {
			clear_cache();
		}

		if($result['errors'] !== []) {
			return JsonResponse::fail(__('Внимание'), __('Частичное сохранение с ошибками'), 'validation', 422, ['fields' => $result['errors']]);
		}

		return JsonResponse::toast(__('Сохранено'), ['saved' => true]);
	}

}
