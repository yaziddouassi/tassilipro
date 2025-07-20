<?php
namespace Tassili\Tassili\Filters;

class FilterDate
{
    protected string $field;
    protected string $label;
    protected string $type = 'Date';
    protected array $options = [];
   
    public static function make(string $field): self
    {
        $instance = new self();
        $instance->field = $field;
        $instance->options['min'] = 'infinite';
        $instance->options['max'] = 'infinite';
        return $instance;
    }

    public function min($min): self
    {
        $this->options['min'] = $min;
        return $this;
    }

    public function max($max): self
    {
        $this->options['max'] = $max;
        return $this;
    }

    
    public function registerTo($generator): void
    {
  
        $b = [] ;
        $b['field'] = $this->field ;
        $b['min'] =  $this->options['min'];
        $b['max'] =  $this->options['max'];
       
        $generator->tabFilterFields[$this->field] =  $this->field;
        $generator->tabFilterLabels[$this->field] = $this->field;
        $generator->tabFilterTypes[$this->field] = $this->type;
        $generator->tabFilterOptions[$this->field] = $b;
    
    }

    


    


}