<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Livewire\Component;

class SettingsManager extends Component
{
    public array $settings = [];

    public function mount(): void
    {
        $this->settings = Setting::all()->mapWithKeys(function ($setting) {
            return [$setting->key => $setting->value];
        })->toArray();
    }

    public function save(): void
    {
        foreach ($this->settings as $key => $value) {
            Setting::where('key', $key)->update(['value' => $value]);
        }

        session()->flash('success', 'Paramètres sauvegardés avec succès.');
    }

    public function render()
    {
        $allSettings = Setting::all()->groupBy('group');

        return view('livewire.admin.settings-manager', [
            'groupedSettings' => $allSettings,
        ]);
    }
}
