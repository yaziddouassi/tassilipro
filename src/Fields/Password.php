<?php
namespace Tassili\Tassili\Fields;

class Password
{
    protected string $field;
    protected string $type = 'Password';
    protected $defaultValue = '';
    protected $label = '';
    protected $noDatabase = 'no';
    
    
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

    public function registerTo($generator): void
    {
        $generator->tassiliFields[$this->field]['field'] = $this->field;
        $generator->tassiliFields[$this->field]['type'] = $this->type;
        $generator->tassiliFields[$this->field]['value'] = $this->defaultValue;
        $generator->tassiliFields[$this->field]['options']['label'] = $this->label;
        $generator->tassiliFields[$this->field]['options']['defaultValue'] = $this->defaultValue;
        $generator->tassiliFields[$this->field]['options']['noDatabase'] = $this->noDatabase;
    }

    
     public function updateTo($generator): void
    {
        $this->registerTo($generator);
   }


   public function repeteurTo($generator,$champs): void
    {
        $b = [];
        $b['type'] = $this->type ;
        $b['field'] = $this->field ;
        $b['label'] = $this->label ;
        $b['value'] = $this->default ;

        $generator->tabRepeaterFields[$champs][$b['field']] = $b ;
      
  
    }


    public function repeteurToUpdate($generator,$champs): void
    {
      $this->repeteurTo($generator,$champs);
  
    } 

     public function registerToCustomAction($generator): void
    {

        $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['field'] = $this->field;
        $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['type'] = $this->type;
        $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['value'] = $this->defaultValue;
        $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['label'] = $this->label;
        $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['defaultValue'] = $this->defaultValue;
        $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['noDatabase'] = $this->noDatabase;
    }   


}