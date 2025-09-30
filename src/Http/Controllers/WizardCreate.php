<?php

namespace Tassili\Tassili\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class WizardCreate
{
    public array $tassiliSettings = [];
    public array $tassiliFields = [];
    public $tassiliRecord = null;
    public array $tassiliWizardInfo = [];

    public array $arrayTypes1 = ['Text','Date','Number','Hidden','Select','Radio','Textarea','Signature'];
    public array $arrayTypes2 = ['Quill'];
    public array $arrayTypes3 = ['File'];
    public array $arrayTypes5 = ['MultipleFile'];
    public array $arrayTypes6 = ['CheckboxList'];
    public array $arrayTypes7 = ['Checkbox'];
    public array $arrayTypes8 = ['Password'];
    public array $arrayTypes9 = ['Repeater'];

    public function __construct(array $settings = [])
    {
        config(['inertia.ssr.enabled' => false]);
        $this->tassiliSettings = array_merge($this->tassiliSettings, $settings);
        $this->initField();
    }

    public function initField(): void
    {
        // à override dans le controller
    }

    public function form(array $fields): self
    {
        foreach ($fields as $field) {
            $field->registerTo($this);
        }
        return $this;
    }

    public function createRecord(Request $request): void
    {
        foreach ($request->all() as $key => $value) {
            if (!array_key_exists($key, $this->tassiliFields)) continue;

            $field = $this->tassiliFields[$key];
            if ($field['options']['noDatabase'] !== 'no') continue;

            if (in_array($field['type'], array_merge($this->arrayTypes1, $this->arrayTypes2))) {
                $this->tassiliRecord[$key] = $value;
            } 
            elseif (in_array($field['type'], $this->arrayTypes6)) {
                $this->tassiliRecord[$key] = is_array($value) ? json_encode($value) : json_encode(explode(',', $value));
            } 
            elseif (in_array($field['type'], $this->arrayTypes7)) {
                $this->tassiliRecord[$key] = match($value) {
                    'true' => true,
                    'false' => false,
                    default => $value,
                };
            } 
            elseif (in_array($field['type'], $this->arrayTypes8) && $value) {
                $this->tassiliRecord[$key] = Hash::make($value);
            } 
            elseif (in_array($field['type'], $this->arrayTypes9)) {
                $this->tassiliRecord[$key] = $this->handleRepeater($field, $value);
            } 
            elseif (in_array($field['type'], $this->arrayTypes3)) {
                $this->tassiliRecord[$key] = $this->handleSingleFile($field, $request, $key);
            } 
            elseif (in_array($field['type'], $this->arrayTypes5)) {
                $this->tassiliRecord[$key] = $this->handleMultipleFile($field, $request, $key);
            }
        }
    }

    protected function handleRepeater(array $field, array $value): string
    {
        $cleaned = [];
        $tabTemp = ['Text','Date','Number','Hidden','Select','Radio','Textarea','Quill','Checkbox'];
        foreach ($value as $item) {
            $cleanedItem = [];
            foreach ($item as $k => $v) {
                $subType = $field['fields'][$k]['type'] ?? null;
                if ($subType === 'CheckboxList') {
                    $cleanedItem[$k] = is_array($v) ? $v : explode(',', $v);
                } elseif (in_array($subType, $tabTemp)) {
                    $cleanedItem[$k] = $v ?? '';
                }
            }
            $cleaned[] = $cleanedItem;
        }
        return json_encode($cleaned);
    }

    protected function handleSingleFile(array $field, Request $request, string $key): ?string
    {
        $dossierStorage = 'uploads/' . $field['options']['storage_folder'];
        if ($request->hasFile($key)) {
            $file = $request->file($key);
            $uniqueName = Str::uuid() . '.' . $file->getClientOriginalName();
            $file->storeAs($dossierStorage, $uniqueName, config('tassili.storage_disk'));
            return $dossierStorage . '/' . $uniqueName;
        }
        return null;
    }

    protected function handleMultipleFile(array $field, Request $request, string $key): string
    {
        $temp = [];
        $dossierStorage = 'uploads/' . $field['options']['storage_folder'];
        if ($request->hasFile($key)) {
            foreach ($request->file($key) as $file) {
                $uniqueName = Str::uuid() . '.' . $file->getClientOriginalName();
                $file->storeAs($dossierStorage, $uniqueName, config('tassili.storage_disk'));
                $temp[] = $dossierStorage . '/' . $uniqueName;
            }
        }
        return json_encode($temp);
    }

    public function wizard(array $wizard): self
    {
        $this->tassiliWizardInfo = $wizard;
        return $this;
    }
}