<?php   

require_once 'Tool.php';

class ManualTool extends Tool {

    public function getPrecioDia(): float {
        
        return $this->precioDia;
    }
}

?>