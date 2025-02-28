<x-text-field name="settings[key]" :label="__('API Key')" :value="Arr::get($settings ?? [], 'api_key')" autocomplete="off" />
