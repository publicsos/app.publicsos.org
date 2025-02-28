
<x-text-field name="settings[host]" :label="__('SMTP Host that can answer to rtpc notify')" :value="Arr::get($settings ?? [], 'host')" />

<x-text-field type="number" name="settings[port]" :label="__('SMTP Port')" :value="Arr::get($settings ?? [], 'port')" />

<x-text-field name="settings[encryption]" :label="__('Encryption')" :value="Arr::get($settings ?? [], 'encryption')" />

<x-text-field name="settings[username]" :label="__('Username')" :value="Arr::get($settings ?? [], 'username')" />

<x-text-field type="password" name="settings[password]" :label="__('Password')" :value="Arr::get($settings ?? [], 'password')" />
