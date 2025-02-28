
<p>
   <small class="text-white">
    Cups Server is required to send emails.
   </small>
</p>
<x-text-field name="settings[hostname]" :label="__('Hostname')" :value="Arr::get($settings ?? [], 'hostname')" />
<x-text-field name="settings[username]" :label="__('Username')" :value="Arr::get($settings ?? [], 'username')" />
