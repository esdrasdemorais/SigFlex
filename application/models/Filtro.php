<?php
class Filtro
{
    protected $campo;
    protected $operador;
    protected $valor;
    
    public function getFiltro()
    {
        return ($this->campo && $this->valor && $this->operador) ? 
                $this->campo . $this->operador . $this->valor :
                "";
    }
}
?>