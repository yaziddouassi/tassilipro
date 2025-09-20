<?php
namespace Tassili\Tassili\Fields;

class MultipleFile
{
    protected string $field;
    protected string $type = 'MultipleFile';
    protected $defaultValue = [];
    protected $label = '';
    protected $noDatabase = 'no';
    protected $nullable = 'no';
    protected $noTouchable = 'no';
    protected $readOnly = 'no';
    protected $maxNumberFiles = 1000000000000;
    protected string $folder;

    public static function make(string $field): self
    {
        $instance = new self();
        $instance->field = $field;
        $instance->folder = config('tassili.storage_folder');
        $instance->label = ucfirst($field);
        return $instance;
    }

    public function label(string $label): self
    {
        $this->label = $label;
        return $this;
    }

     public function folder(string $folder): self
    {
        $this->folder = $folder;
        return $this;
    }

      public function readOnly(): self
    {
        $this->readOnly = 'yes';
        return $this;
    }
   
    public function notInDatabase(): self
    {
        $this->noDatabase = 'yes';
        return $this;
    }

    public function fieldAndRecordNotNull(): self
    {
        $this->nullable = 'yes';
        return $this;
    }

     public function keepExistingFiles(): self
    {
        $this->noTouchable = 'yes';
        return $this;
    }

    public function maxNumberFiles(int $maxNumberFiles): self
    {
        $this->maxNumberFiles = $maxNumberFiles;
        return $this;
    }

    public function registerTo($generator): void
    {
        $generator->tassiliFields[$this->field]['field'] = $this->field;
        $generator->tassiliFields[$this->field]['type'] = $this->type;
        $generator->tassiliFields[$this->field]['value'] = $this->defaultValue;
        $generator->tassiliFields[$this->field]['options']['tempUrlTabs'] = [];
        $generator->tassiliFields[$this->field]['options']['existingFiles'] = [];
        $generator->tassiliFields[$this->field]['options']['label'] = $this->label;
        $generator->tassiliFields[$this->field]['options']['defaultValue'] = $this->defaultValue;
        $generator->tassiliFields[$this->field]['options']['noDatabase'] = $this->noDatabase;
        $generator->tassiliFields[$this->field]['options']['nullable'] = $this->nullable;
        $generator->tassiliFields[$this->field]['options']['noTouchable'] = $this->noTouchable;
        $generator->tassiliFields[$this->field]['options']['readOnly'] = $this->readOnly;
        $generator->tassiliFields[$this->field]['options']['maxNumberFiles'] = $this->maxNumberFiles;
        $generator->tassiliFields[$this->field]['options']['storage_folder'] = $this->folder;
        
    }

    
     public function updateTo($generator): void
    {
        $this->registerTo($generator);
        $generator->tassiliFields[$this->field]['type'] = 'MultipleFileEdit';
   }

    public function registerToCustomAction($generator): void
    {

        $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['field'] = $this->field;
        $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['type'] = 'MultipleFileEdit';
        $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['value'] = $this->defaultValue;
        $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['label'] = $this->label;
        $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['defaultValue'] = $this->defaultValue;
        $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['noDatabase'] = $this->noDatabase;
        $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['tempUrlTabs'] = [];
        $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['existingFiles'] = [];
        $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['nullable'] = $this->nullable;
        $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['noTouchable'] = $this->noTouchable;
        $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['readOnly'] = $this->readOnly;
        $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['maxNumberFiles'] = $this->maxNumberFiles;
        $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['storage_folder'] = $this->folder;
    }   



}