<?php
namespace Tassili\Tassili\Fields;

class DateInput
{
    protected string $field;
    protected string $type = 'Date';
    protected $defaultValue = '';
    protected $label = '';
    protected $noDatabase = 'no';
    protected $min = 'inifinite';
    protected $max = 'inifinite';
    
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

    public function value($value): self
    {
        $this->defaultValue = $value;
        return $this;
    }


    public function min($value): self
    {
        $this->min = $value;
        return $this;
    }

    public function max($value): self
    {
        $this->max = $value;
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
        $generator->tassiliFields[$this->field]['options']['max'] = $this->max;
        $generator->tassiliFields[$this->field]['options']['min'] = $this->min;
    }

    
     public function updateTo($generator): void
    {
        $this->registerTo($generator);
   }


   public function repeteurTo($generator , $field): void
    {

        $generator->tassiliFields[$field]['fields'][$this->field]['field'] = $this->field;
        $generator->tassiliFields[$field]['fields'][$this->field]['type'] = $this->type;
        $generator->tassiliFields[$field]['fields'][$this->field]['value'] = $this->defaultValue;
        $generator->tassiliFields[$field]['fields'][$this->field]['options']['label'] = $this->label;
        $generator->tassiliFields[$field]['fields'][$this->field]['options']['defaultValue'] = $this->defaultValue;
        $generator->tassiliFields[$field]['fields'][$this->field]['options']['noDatabase'] = $this->noDatabase;
        $generator->tassiliFields[$field]['fields'][$this->field]['options']['min'] = $this->max;
        $generator->tassiliFields[$field]['fields'][$this->field]['options']['max'] = $this->min;

        $generator->tassiliFields[$field]['schemaFields'][$this->field] = $this->defaultValue;


        $generator->tassiliFields[$field]['value'] = [] ;

                  for ($i=0; $i < $generator->tassiliFields[$field]['numberOflines'] ; $i++) { 
             
                    array_push($generator->tassiliFields[$field]['value'], $generator->tassiliFields[$field]['schemaFields']);
  
                }

    }


    public function repeteurToUpdate($generator , $field): void
    {
      $this->repeteurTo($generator , $field);
  
    } 

     public function registerToCustomAction($generator): void
    {

        $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['field'] = $this->field;
        $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['type'] = $this->type;
        $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['value'] = $this->defaultValue;
        $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['label'] = $this->label;
        $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['defaultValue'] = $this->defaultValue;
        $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['noDatabase'] = $this->noDatabase;
        $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['max'] = $this->max;
        $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['min'] = $this->min;
    }   


     public function repeteurToCustomAction($generator , $field) {
    $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$field]['fields'][$this->field]['field'] = $this->field;
    $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$field]['fields'][$this->field]['type'] = $this->type;
    $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$field]['fields'][$this->field]['value'] = $this->defaultValue;
    $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$field]['fields'][$this->field]['options']['label'] = $this->label ;
    $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$field]['fields'][$this->field]['options']['defaultValue'] = $this->defaultValue ;
    $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$field]['fields'][$this->field]['options']['noDatabase'] = $this->noDatabase ;
    $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$field]['fields'][$this->field]['options']['min'] = $this->min ;
    $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$field]['fields'][$this->field]['options']['max'] = $this->max ;
    $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$field]['schemaFields'][$this->field] = $this->defaultValue;


    $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$field]['value'] = [] ;

                  for ($i=0; $i < $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$field]['numberOflines'] ; $i++) { 
             
                    array_push($generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$field]['value'],
                     $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$field]['schemaFields']);
  
                }


}

}