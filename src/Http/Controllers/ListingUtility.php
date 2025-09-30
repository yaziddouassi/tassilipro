<?php

namespace Tassili\Tassili\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class ListingUtility
{
    public array $tassiliSettings = [];
    public array $tassiliFormList = [];
    public $tassiliRecord = null;
    public array $allFilters = [
        'search' => 'search',
        'paginationPerPage' => 'paginationPerPage',
        'orderByField' => 'orderByField',
        'orderDirection' => 'orderDirection'
    ];
    public array $customFilters = [];
    public array $tabFilterFields = [];
    public array $tabFilterLabels = [];
    public array $tabFilterTypes = [];
    public array $tabFilterOptions = [];
    public string $search = '';
    public $queryFilter;
    public $tables;
    public array $groupActions = [];
    public string $customActionUrlTemoin = '';
    
    private array $arrayTypes1 = ['Text', 'Date', 'Number', 'Hidden', 'Select', 'Radio', 'Textarea', 'Signature'];
    private array $arrayTypes2 = ['Quill'];
    private array $arrayTypes4 = ['FileEdit'];
    private array $arrayTypes5 = ['MultipleFileEdit'];
    private array $arrayTypes6 = ['CheckboxList'];
    private array $arrayTypes7 = ['Checkbox'];
    private array $arrayTypes8 = ['Password'];
    private array $arrayTypes9 = ['Repeater'];

    public function __construct(array $settings)
    {
        config(['inertia.ssr.enabled' => false]);
        
        $this->tassiliSettings = [
            'tassiliPanel' => $settings['tassiliPanel'] ?? '',
            'tassiliDataModelLabel' => $settings['tassiliDataModelLabel'] ?? '',
            'tassiliDataModelTitle' => $settings['tassiliDataModelTitle'] ?? '',
            'tassiliDataRouteListe' => $settings['tassiliDataRouteListe'] ?? '',
            'tassiliDataUrlCreate' => $settings['tassiliDataUrlCreate'] ?? '',
            'tassiliModelClass' => $settings['tassiliModelClass'] ?? '',
            'tassiliModelClassName' => $settings['tassiliModelClassName'] ?? '',
            'paginationPerPageList' => $settings['paginationPerPageList'] ?? [10, 20, 30],
            'orderByFieldList' => $settings['orderByFieldList'] ?? ['id'],
            'orderDirectionList' => $settings['orderDirectionList'] ?? ['asc', 'desc'],
            'urlDelete' => $settings['urlDelete'] ?? '',
        ];
    }

    public function filterList(array $fields): void
    {
        foreach ($fields as $field) {
            $field->registerTo($this);
        }
    }

    public function ActionList(array $fields): void
    {
        foreach ($fields as $field) {
            $field->registerTo($this);
        }
    }

    public function CustomActionForm(array $settings): self
    {
        $this->tassiliFormList[$settings['url']]['info'] = $settings;
        $this->tassiliFormList[$settings['url']]['info']['wizardActive'] = 'no';
        $this->tassiliFormList[$settings['url']]['info']['wizard'] = [];
        $this->customActionUrlTemoin = $settings['url'];
        
        return $this;
    }

    public function form(array $fields): self
    {
        foreach ($fields as $field) {
            $field->registerToCustomAction($this);
        }
        
        return $this;
    }

    public function wizard(array $wizard): self
    {
        $this->tassiliFormList[$this->customActionUrlTemoin]['info']['wizard'] = $wizard;
        $this->tassiliFormList[$this->customActionUrlTemoin]['info']['wizardActive'] = 'yes';
        
        return $this;
    }

    public function initializeQuery($modelClass, Request $request, callable $queryCallback = null)
    {
        $paginationPerPage = $this->tassiliSettings['paginationPerPageList'][0];
        $orderByField = $this->tassiliSettings['orderByFieldList'][0];
        $orderDirection = $this->tassiliSettings['orderDirectionList'][0];

        if ($request->filled('paginationPerPage') && 
            in_array($request->paginationPerPage, $this->tassiliSettings['paginationPerPageList'])) {
            $paginationPerPage = $request->paginationPerPage;
        }

        if ($request->filled('orderByField') && 
            in_array($request->orderByField, $this->tassiliSettings['orderByFieldList'])) {
            $orderByField = $request->orderByField;
        }

        if ($request->filled('orderDirection') && 
            in_array($request->orderDirection, $this->tassiliSettings['orderDirectionList'])) {
            $orderDirection = $request->orderDirection;
        }

        $this->customFilters['Fields'] = $this->tabFilterFields;
        $this->customFilters['Labels'] = $this->tabFilterLabels;
        $this->customFilters['Types'] = $this->tabFilterTypes;
        $this->customFilters['Options'] = $this->tabFilterOptions;
        
        $this->queryFilter = $modelClass::select('*');
        
        if ($queryCallback) {
            $queryCallback($this->queryFilter, $request);
        }

        $this->tables = $this->queryFilter
            ->orderBy($orderByField, $orderDirection)
            ->paginate($paginationPerPage)
            ->appends($request->except('page'));
    }

    public function updateRecord(Request $request): void
    {
        $url = $request->urlValidationurlValidationurlValidationTassili17485RRY4R4RD9448RK48K4RFRFIRU;

        foreach ($request->all() as $key => $value) {
            if (!array_key_exists($key, $this->tassiliFormList[$url]['fields'])) {
                continue;
            }

            $field = $this->tassiliFormList[$url]['fields'][$key];
            
            if (!$field || $field['options']['noDatabase'] === 'yes') {
                continue;
            }

            $this->processFieldValue($key, $value, $field, $request);
        }
    }

    private function processFieldValue(string $key, $value, array $field, Request $request): void
    {
        $type = $field['type'];

        if (in_array($type, array_merge($this->arrayTypes1, $this->arrayTypes2))) {
            $this->tassiliRecord[$key] = $value;
        }
        elseif (in_array($type, $this->arrayTypes6)) {
            $this->tassiliRecord[$key] = is_array($value) 
                ? json_encode($value) 
                : json_encode(explode(',', $value));
        }
        elseif (in_array($type, $this->arrayTypes7)) {
            $this->tassiliRecord[$key] = $value === 'true';
        }
        elseif (in_array($type, $this->arrayTypes8)) {
            if ($value) {
                $this->tassiliRecord[$key] = Hash::make($value);
            }
        }
        elseif (in_array($type, $this->arrayTypes9)) {
            $this->processRepeaterField($key, $value, $field);
        }
        elseif (in_array($type, $this->arrayTypes4)) {
            $this->processFileField($key, $field, $request);
        }
        elseif (in_array($type, $this->arrayTypes5)) {
            $this->processMultipleFileField($key, $value, $field, $request);
        }
    }

    private function processRepeaterField(string $key, $value, array $field): void
    {
        $cleanedRepeater = [];
        $allowedTypes = ['Text', 'Date', 'Number', 'Hidden', 'Select', 'Radio', 'Textarea', 'Quill', 'Checkbox'];

        foreach ($value as $repeaterItem) {
            $cleanedItem = [];

            foreach ($repeaterItem as $subKey => $subValue) {
                $subType = $field['fields'][$subKey]['type'] ?? null;

                if ($subType === 'CheckboxList') {
                    $cleanedItem[$subKey] = is_array($subValue) 
                        ? $subValue 
                        : explode(',', $subValue);
                }
                elseif (in_array($subType, $allowedTypes)) {
                    $cleanedItem[$subKey] = $subValue ?? '';
                }
            }

            $cleanedRepeater[] = $cleanedItem;
        }

        $this->tassiliRecord[$key] = json_encode($cleanedRepeater);
    }

    private function processFileField(string $key, array $field, Request $request): void
    {
        if (!$request->hasFile($key)) {
            return;
        }

        $dossier = $field['options']['storage_folder'];
        $dossierStorage = 'uploads/' . $dossier;
        $file = $request->file($key);
        $uniqueName = Str::uuid() . '.' . $file->getClientOriginalName();
        $file->storeAs($dossierStorage, $uniqueName, config('tassili.storage_disk'));
        $this->tassiliRecord[$key] = $dossierStorage . '/' . $uniqueName;
    }

    private function processMultipleFileField(string $key, $value, array $field, Request $request): void
    {
        $tab1 = json_decode($request->input($key . '_newtab'), true) ?? [];
        $dossier = $field['options']['storage_folder'];
        $dossierStorage = 'uploads/' . $dossier;

        if ($value) {
            foreach ($value as $file) {
                $uniqueName = Str::uuid() . '.' . $file->getClientOriginalName();
                $path = $file->storeAs($dossierStorage, $uniqueName, config('tassili.storage_disk'));
                $tab1[] = $path;
            }
        }

        $this->tassiliRecord[$key] = json_encode($tab1);
    }

    public function getInertiaData(): array
    {
        return [
            'tassiliPanel' => $this->tassiliSettings['tassiliPanel'],
            'items' => $this->tables,
            'user' => \Illuminate\Support\Facades\Auth::user(),
            'routes' => \Tassili\Tassili\Models\TassiliCrud::where('active', true)
                        ->where('panel',$this->tassiliSettings['tassiliPanel'])->get(),
            'tassiliSettings' => $this->tassiliSettings,
            'allFilters' => $this->allFilters,
            'customFilters' => $this->customFilters,
            'groupActions' => $this->groupActions,
            'tassiliFormList' => $this->tassiliFormList,
            'tassiliUrlStorage' => config('tassili.storage_url'),
        ];
    }
}