<?php

namespace Tassili\Tassili\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class WizardUpdate
{
    public $tassiliSettings = [];
    public $tassiliFields = [];
    public $tassiliWizardInfo = [];
    public $tassiliRecord = null;
    public $tassiliRecordInput;

    // Types de champs
    public $arrayTypes1 = ['Text','Date','Number','Hidden','Select','Radio','Textarea'];
    public $arrayTypes2 = ['Quill'];
    public $arrayTypes4 = ['FileEdit'];
    public $arrayTypes5 = ['MultipleFileEdit'];
    public $arrayTypes6 = ['CheckboxList'];
    public $arrayTypes7 = ['Checkbox'];
    public $arrayTypes8 = ['Password'];
    public $arrayTypes9 = ['Repeater'];
    public $arrayTypes10 = ['Signature'];

    public function __construct(array $settings)
    {
        config(['inertia.ssr.enabled' => false]);
        $this->tassiliSettings = $settings;
    }

    public function form(array $fields)
    {
        foreach ($fields as $field) {
            $field->updateTo($this);
        }
        return $this;
    }

    public function wizard(array $wizard)
    {
        $this->tassiliWizardInfo = $wizard;
        return $this;
    }

    public function checkRecord(Request $request)
    {
        $record = $this->tassiliSettings['tassiliModelClass']::find($request->id);

        if ($record === null) {
            return redirect($this->tassiliSettings['tassiliDataRouteListe']);
        }

        $this->tassiliRecordInput = new Collection();
        $this->tassiliRecordInput = $record;
        return null;
    }

    public function updateRecord(Request $request)
    {
        foreach ($request->all() as $key => $value) {
            if (!array_key_exists($key, $this->tassiliFields)) {
                continue;
            }

            $field = $this->tassiliFields[$key];

            if ($field && $field['options']['noDatabase'] === 'no') {

                // Cas simple (Text, Date, Number, etc.)
                if (in_array($field['type'], $this->arrayTypes1) ||
                    in_array($field['type'], $this->arrayTypes2) ||
                    in_array($field['type'], $this->arrayTypes10)) {
                    $this->tassiliRecord[$key] = $value;
                }

                // CheckboxList
                elseif (in_array($field['type'], $this->arrayTypes6)) {
                    $this->tassiliRecord[$key] = is_array($value)
                        ? json_encode($value)
                        : json_encode(explode(',', $value));
                }

                // Checkbox
                elseif (in_array($field['type'], $this->arrayTypes7)) {
                    $this->tassiliRecord[$key] = ($value === 'true');
                }

                // Password
                elseif (in_array($field['type'], $this->arrayTypes8) && $value) {
                    $this->tassiliRecord[$key] = Hash::make($value);
                }

                // Repeater
                elseif (in_array($field['type'], $this->arrayTypes9)) {
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
                    $this->tassiliRecord[$key] = json_encode($cleanedRepeater);
                }

                // FileEdit
                elseif (in_array($field['type'], $this->arrayTypes4)) {
                    $folder = $field['options']['storage_folder'];
                    $folderStorage = 'uploads/' . $folder;
                    if ($request->hasFile($key)) {
                        $file = $request->file($key);
                        $uniqueName = Str::uuid() . '.' . $file->getClientOriginalName();
                        $file->storeAs($folderStorage, $uniqueName, config('tassili.storage_disk'));
                        $this->tassiliRecord[$key] = $folderStorage . '/' . $uniqueName;
                    }
                }

                // MultipleFileEdit
                elseif (in_array($field['type'], $this->arrayTypes5)) {
                    $tab1 = json_decode($request->input($key . '_newtab')) ?? [];
                    $folder = $field['options']['storage_folder'];
                    $folderStorage = 'uploads/' . $folder;

                    if ($value) {
                        foreach ($value as $file) {
                            $uniqueName = Str::uuid() . '.' . $file->getClientOriginalName();
                            $path = $file->storeAs($folderStorage, $uniqueName, config('tassili.storage_disk'));
                            array_push($tab1, $path);
                        }
                    }

                    $this->tassiliRecord[$key] = json_encode($tab1);
                }
            }
        }
    }

    public function initFieldAgain(Request $request)
    {
        foreach ($this->tassiliFields as $key => $value) {
            if ($value['options']['noDatabase'] === 'no') {
                if (in_array($value['type'], $this->arrayTypes1) ||
                    in_array($value['type'], $this->arrayTypes2) ||
                    in_array($value['type'], $this->arrayTypes7)) {
                    $this->tassiliFields[$key]['value'] = $this->tassiliRecordInput[$key];
                }

                elseif (in_array($value['type'], $this->arrayTypes6)) {
                    $this->tassiliFields[$key]['value'] = json_decode($this->tassiliRecordInput[$key], true);
                }

                elseif (in_array($value['type'], $this->arrayTypes4)) {
                    $this->tassiliFields[$key]['options']['urlRecord'] = $this->tassiliRecordInput[$key];
                }

                elseif (in_array($value['type'], $this->arrayTypes5)) {
                    $this->tassiliFields[$key]['options']['existingFiles'] = json_decode($this->tassiliRecordInput[$key], true);
                }

                elseif (in_array($value['type'], $this->arrayTypes9)) {
                    $this->tassiliFields[$key]['value'] = json_decode($this->tassiliRecordInput[$key], true);
                }

                elseif (in_array($value['type'], $this->arrayTypes10)) {
                    $this->tassiliFields[$key]['value'] = $this->tassiliRecordInput[$key] ?? '';
                }
            }
        }
    }
}
