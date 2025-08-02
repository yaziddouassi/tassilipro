<?php
namespace Tassili\Tassili\Fields;

class Select
{
    protected string $field;
    protected string $type = 'Select';
    protected $defaultValue = '';
    protected $label = '';
    protected $noDatabase = 'no';
    protected $contents = [];
    protected $labels = [];
    
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


    public function contents(array $value): self
    {
        $this->contents = $value;
        return $this;
    }

    public function labels(array $value): self
    {
        $this->labels = $value;
        return $this;
    }

    
    public function notInDatabase(): self
    {
        $this->noDatabase = 'yes';
        return $this;
    }

    public function registerTo($generator): void
    {

          foreach ($this->contents as $key => $value) {
          $this->contents[$key] = (string) $this->contents[$key] ;
        }

         foreach ($this->labels as $key2 => $value2) {
          $this->labels[$key2] = (string) $this->labels[$key2] ;
        }

          $this->defaultValue = (string) $this->defaultValue ;


        $generator->tassiliFields[$this->field]['field'] = $this->field;
        $generator->tassiliFields[$this->field]['type'] = $this->type;
        $generator->tassiliFields[$this->field]['value'] = $this->defaultValue;
        $generator->tassiliFields[$this->field]['options']['label'] = $this->label;
        $generator->tassiliFields[$this->field]['options']['defaultValue'] = $this->defaultValue;
        $generator->tassiliFields[$this->field]['options']['noDatabase'] = $this->noDatabase;
        $generator->tassiliFields[$this->field]['options']['contents'] = $this->contents;
        $generator->tassiliFields[$this->field]['options']['labels'] = $this->labels;
    }
    
     public function updateTo($generator): void
    {
        $this->registerTo($generator);
   }


   public function repeteurTo($generator , $field): void
    {
   
        foreach ($this->contents as $key => $value) {
          $this->contents[$key] = (string) $this->contents[$key] ;
        }

         foreach ($this->labels as $key2 => $value2) {
          $this->labels[$key2] = (string) $this->labels[$key2] ;
        }

          $this->defaultValue = (string) $this->defaultValue ;

        $generator->tassiliFields[$field]['fields'][$this->field]['field'] = $this->field;
        $generator->tassiliFields[$field]['fields'][$this->field]['type'] = $this->type;
        $generator->tassiliFields[$field]['fields'][$this->field]['value'] = $this->defaultValue;
        $generator->tassiliFields[$field]['fields'][$this->field]['options']['label'] = $this->label;
        $generator->tassiliFields[$field]['fields'][$this->field]['options']['defaultValue'] = $this->defaultValue;
        $generator->tassiliFields[$field]['fields'][$this->field]['options']['noDatabase'] = $this->noDatabase;
        $generator->tassiliFields[$field]['fields'][$this->field]['options']['contents'] = $this->contents;
        $generator->tassiliFields[$field]['fields'][$this->field]['options']['labels'] = $this->labels;

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

          foreach ($this->contents as $key => $value) {
          $this->contents[$key] = (string) $this->contents[$key] ;
        }

         foreach ($this->labels as $key2 => $value2) {
          $this->labels[$key2] = (string) $this->labels[$key2] ;
        }

          $this->defaultValue = (string) $this->defaultValue ; 


        $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['field'] = $this->field;
        $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['type'] = $this->type;
        $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['value'] = $this->defaultValue;
        $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['label'] = $this->label;
        $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['defaultValue'] = $this->defaultValue;
        $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['noDatabase'] = $this->noDatabase;
        $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['contents'] = $this->contents;
        $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['labels'] = $this->labels;

    }   


     public function repeteurToCustomAction($generator , $field) {


    foreach ($this->contents as $key => $value) {
          $this->contents[$key] = (string) $this->contents[$key] ;
        }

         foreach ($this->labels as $key2 => $value2) {
          $this->labels[$key2] = (string) $this->labels[$key2] ;
        }

          $this->defaultValue = (string) $this->defaultValue ;


    $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$field]['fields'][$this->field]['field'] = $this->field;
    $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$field]['fields'][$this->field]['type'] = $this->type;
    $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$field]['fields'][$this->field]['value'] = $this->defaultValue;
    $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$field]['fields'][$this->field]['options']['label'] = $this->label ;
    $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$field]['fields'][$this->field]['options']['defaultValue'] = $this->defaultValue ;
    $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$field]['fields'][$this->field]['options']['noDatabase'] = $this->noDatabase ;
    $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$field]['fields'][$this->field]['options']['contents'] = $this->contents ;
    $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$field]['fields'][$this->field]['options']['labels'] = $this->labels ;
    $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$field]['schemaFields'][$this->field] = $this->defaultValue;


    $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$field]['value'] = [] ;

                  for ($i=0; $i < $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$field]['numberOflines'] ; $i++) { 
             
                    array_push($generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$field]['value'],
                     $generator->tassiliFormList[$generator->customActionUrlTemoin]['fields'][$field]['schemaFields']);
  
                }


   }

}