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

    public static function make(string $field): self
    {
        $instance = new self();
        $instance->field = $field;
        $instance->label = ucfirst($field);
        return $instance;
    }

    public function label(string $label): self
    {
        $this->label = $label;
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
    }   



}