<?php

namespace Tassili\Tassili\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection;

class TassiliUpdate
{
    public array $tassiliSettings = [];
    public array $tassiliFields = [];
    public $tassiliRecord = null;
    public $tassiliRecordInput = null;

    public array $arrayTypes1 = ['Text','Date','Number','Hidden','Select','Radio','Textarea'];
    public array $arrayTypes2 = ['Quill'];
    public array $arrayTypes4 = ['FileEdit'];
    public array $arrayTypes5 = ['MultipleFileEdit'];
    public array $arrayTypes6 = ['CheckboxList'];
    public array $arrayTypes7 = ['Checkbox'];
    public array $arrayTypes8 = ['Password'];
    public array $arrayTypes9 = ['Repeater'];
    public array $arrayTypes10 = ['Signature'];

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

    public function form(array $fields): void
    {
        foreach ($fields as $field) {
            $field->updateTo($this);
        }
    }

    public function checkRecord(Request $request)
    {
        $record = $this->tassiliSettings['tassiliModelClass']::find($request->id);

        if (!$record) {
            return redirect($this->tassiliSettings['tassiliDataRouteListe']);
        }

        $this->tassiliRecordInput = $record;

        return null;
    }

    public function updateRecord(Request $request): void
    {
        foreach ($request->all() as $key => $value) {
            if (!array_key_exists($key, $this->tassiliFields)) continue;

            $field = $this->tassiliFields[$key];
            if ($field['options']['noDatabase'] === 'no') {

                if (in_array($field['type'], array_merge($this->arrayTypes1, $this->arrayTypes2, $this->arrayTypes10))) {
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

                elseif (in_array($field['type'], $this->arrayTypes4)) {
                    $this->tassiliRecord[$key] = $this->handleSingleFile($field, $request, $key);
                }

                elseif (in_array($field['type'], $this->arrayTypes5)) {
                    $this->tassiliRecord[$key] = $this->handleMultipleFile($field, $request, $key);
                }
            }
        }
    }

    protected function handleRepeater(array $field, array $value): string
    {
        $cleanedRepeater = [];
        $tabTemp = ['Text','Date','Number','Hidden','Select','Radio','Textarea','Quill','Checkbox'];

        foreach ($value as $repeaterItem) {
            $cleanedItem = [];
            foreach ($repeaterItem as $subKey => $subValue) {
                $subType = $field['fields'][$subKey]['type'] ?? null;
                if ($subType === 'CheckboxList') {
                    $cleanedItem[$subKey] = is_array($subValue) ? $subValue : explode(',', $subValue);
                } elseif (in_array($subType, $tabTemp)) {
                    $cleanedItem[$subKey] = $subValue ?? '';
                }
            }
            $cleanedRepeater[] = $cleanedItem;
        }

        return json_encode($cleanedRepeater);
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
        $existingFiles = json_decode($request->input($key . '_newtab', '[]'), true);
        $dossierStorage = 'uploads/' . $field['options']['storage_folder'];

        if ($value = $request->file($key)) {
            foreach ($value as $file) {
                $uniqueName = Str::uuid() . '.' . $file->getClientOriginalName();
                $path = $file->storeAs($dossierStorage, $uniqueName, config('tassili.storage_disk'));
                $existingFiles[] = $path;
            }
        }

        return json_encode($existingFiles);
    }

    public function initFieldAgain(Request $request): void
    {
        foreach ($this->tassiliFields as $key => $value) {
            if ($value['options']['noDatabase'] === 'no') {
                $record = $this->tassiliRecordInput[$key] ?? null;

                if (in_array($value['type'], array_merge($this->arrayTypes1, $this->arrayTypes2, $this->arrayTypes7, $this->arrayTypes10))) {
                    $this->tassiliFields[$key]['value'] = $record ?? '';
                } elseif (in_array($value['type'], $this->arrayTypes6)) {
                    $this->tassiliFields[$key]['value'] = json_decode($record, true) ?? [];
                } elseif (in_array($value['type'], $this->arrayTypes4)) {
                    $this->tassiliFields[$key]['options']['urlRecord'] = $record;
                } elseif (in_array($value['type'], $this->arrayTypes5)) {
                    $this->tassiliFields[$key]['options']['existingFiles'] = json_decode($record, true) ?? [];
                } elseif (in_array($value['type'], $this->arrayTypes9)) {
                    $this->tassiliFields[$key]['value'] = json_decode($record, true) ?? [];
                }
            }
        }
    }

     public function getInertiaData(): array
    {
        return [

            'user' => \Illuminate\Support\Facades\Auth::user(),
            'routes' => \Tassili\Tassili\Models\TassiliCrud::where('active', true)
                ->where('panel',$this->tassiliSettings['tassiliPanel'])->get(),
            'tassiliPanel' => $this->tassiliSettings['tassiliPanel'], 
            'tassiliUrlStorage' => config('tassili.storage_url'),

            'tassiliFields' => $this->tassiliFields,
            'tassiliDataModelLabel' => $this->tassiliSettings['tassiliDataModelLabel'],
            'tassiliDataModelTitle' => $this->tassiliSettings['tassiliDataModelTitle'],
            'tassiliDataRouteListe' => $this->tassiliSettings['tassiliDataRouteListe'],
            'tassiliDataUrlCreate' => $this->tassiliSettings['tassiliDataUrlCreate'],
            'tassiliModelClass' => $this->tassiliSettings['tassiliModelClass'],
            'tassiliModelClassName' => $this->tassiliSettings['tassiliModelClassName'],
            'tassiliValidationUrl' => $this->tassiliSettings['tassiliValidationUrl'],
            'tassiliRecordInput' => $this->tassiliRecordInput,
        ];
    }       
           
}