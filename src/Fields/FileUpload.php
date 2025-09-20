<?php
namespace Tassili\Tassili\Fields;

class FileUpload
{
    protected string $field;
    protected string $type = 'File';
    protected $defaultValue = '';
    protected $label = '';
    protected $noDatabase = 'no';
    protected $readOnly = 'no';
    protected string $folder;
    
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

    public function registerTo($generator): void
    {
        $generator->tassiliFields[$this->field]['field'] = $this->field;
        $generator->tassiliFields[$this->field]['type'] = $this->type;
        $generator->tassiliFields[$this->field]['value'] = $this->defaultValue;
        $generator->tassiliFields[$this->field]['options']['tempUrls'] = '';
        $generator->tassiliFields[$this->field]['options']['label'] = $this->label;
        $generator->tassiliFields[$this->field]['options']['defaultValue'] = $this->defaultValue;
        $generator->tassiliFields[$this->field]['options']['noDatabase'] = $this->noDatabase;
        $generator->tassiliFields[$this->field]['options']['urlRecord'] = '';
        $generator->tassiliFields[$this->field]['options']['readOnly'] = $this->readOnly;
        $generator->tassiliFields[$this->field]['options']['storage_folder'] = $this->folder;
    }

    
     public function updateTo($generator): void
    {
        $this->registerTo($generator);
        $generator->tassiliFields[$this->field]['type'] = 'FileEdit';
   }


   public function registerToCustomAction($generator): void
    {

        $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['field'] = $this->field;
        $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['type'] = 'FileEdit';
        $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['value'] = $this->defaultValue;
        $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['label'] = $this->label;
        $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['defaultValue'] = $this->defaultValue;
        $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['noDatabase'] = $this->noDatabase;
        $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['tempUrls'] = '';
        $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['urlRecord'] = '';
        $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['readOnly'] = $this->readOnly;
        $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['storage_folder'] = $this->folder;
    }   

   

}