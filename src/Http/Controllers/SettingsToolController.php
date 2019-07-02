<?php

namespace Bakerkretzmar\SettingsTool\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Valuestore\Valuestore;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;

class SettingsToolController extends Controller
{
    /**
     * Path to the settings file on disk.
     * @var string
     */
    protected $settingsPath;

    /**
     * Create a new controller instance.
     */
    public function __construct(string $settingsPath = null)
    {
        $this->settingsPath = $settingsPath ?? config('settings.path', storage_path('app/settings.json'));
    }

    /**
     * Retrieve and format settings from a file.
     */
    public function read(Request $request)
    {
        $settings = Valuestore::make($this->settingsPath)->all();

        $settingConfig = config('settings.panels');

        foreach ($settingConfig as $object) {
            foreach ($object['settings'] as $settingObject) {
                if (! array_key_exists($settingObject['key'], $settings)) {
                    if ($settingObject['type'] == 'toggle') {
                        $settings[$settingObject['key']] = $settingObject['default'] ?? false;
                    } else {
                        $settings[$settingObject['key']] = '';
                    }
                }
            }
        }

        return response()->json([
            'settings' => $settings,
            'settingConfig' => $settingConfig,
        ]);
    }

    /**
     * Save updated settings to a file.
     */
    public function write(Request $request)
    {
        $settings = Valuestore::make($this->settingsPath);

        foreach ($request->settings as $setting => $value) {
            $settings->put($setting, $value);
        }

        Cache::tags(config('settings.cache_tag'))->flush();

        return response($settings->all(), 202);
    }
}
