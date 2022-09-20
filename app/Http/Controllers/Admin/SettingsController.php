<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use App\Rules\CpfCnpj;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:settings_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:settings_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:settings_view', ['only' => ['show', 'index']]);
        $this->middleware('permission:settings_delete', ['only' => ['destroy']]);
    }

    public function edit()
    {
        $settings = Settings::with(['city' => fn ($query) => $query->state()])
            ->first();

        return view('settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $this->validate(
            $request,
            $this->rules($request, null)
        );

        $settings = Settings::first();

        if (!$settings) {
            $settings = new Settings();
        }

        $inputs = $request->all();
        $settings->fill($inputs);

        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {

            $upload = $request->file('logo')->store('settings', 'public');

            if ($settings->logo) {
                Storage::disk('public')->delete($settings->logo);
            }

            $settings->logo = $upload;
        }

        if ($request->hasFile('terms') && $request->file('terms')->isValid()) {

            $upload = $request->file('terms')->store('settings', 'public');

            if ($settings->terms) {
                Storage::disk('public')->delete($settings->terms);
            }

            $settings->terms = $upload;
        }

        if ($request->hasFile('privacy_policy') && $request->file('privacy_policy')->isValid()) {

            $upload = $request->file('privacy_policy')->store('settings', 'public');

            if ($settings->privacy_policy) {
                Storage::disk('public')->delete($settings->privacy_policy);
            }

            $settings->privacy_policy = $upload;
        }


        $settings->save();

        return redirect()->route('settings.edit')
            ->withStatus('Registro atualizado com sucesso.');
    }

    private function rules(Request $request, $primaryKey = null, bool $changeMessages = false)
    {
        $rules = [
            'name' => ['required', 'max:35'],
            'full_name' => ['required', 'max:50'],
            'nif' => ['required', new CpfCnpj],
            'city_id' => ['required'],
            'address' => ['required', 'max:50'],
            'number' => ['required', 'max:5'],
            'district' => ['nullable', 'max:35'],
            'maps' => ['nullable', 'max:150'],
            'contact' => ['nullable', 'max:30'],
            'zip_code' => ['required', 'max:9'],
            'email' => ['required', 'max:50'],
            'phone' => ['required', 'max:15'],
            'status' => ['required'],
            'logo' => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:1000'],
            'note' => ['nullable', 'max:200'],
            'instagram_url' => ['nullable', 'max:80'],
            'instagram_user' => ['nullable', 'max:40'],
            'instagram_password' => ['nullable', 'max:15'],
            'facebook_url' => ['nullable', 'max:80'],
            'facebook_user' => ['nullable', 'max:40'],
            'facebook_password' => ['nullable', 'max:15'],
            'youtube_url' => ['nullable', 'max:80'],
            'youtube_user' => ['nullable', 'max:40'],
            'youtube_password' => ['nullable', 'max:15'],
            'twitter_url' => ['nullable', 'max:80'],
            'twitter_user' => ['nullable', 'max:40'],
            'twitter_password' => ['nullable', 'max:15'],
            'pixels' => ['nullable', 'max:80'],
            'ads' => ['nullable', 'max:80'],
            'meta_tags' => ['nullable', 'max:200'],
            'footer' => ['nullable', 'max:110'],
        ];

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }
}
