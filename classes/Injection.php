<?php



class Injection {
    public $injectionid, $injectiontype, $injectiondate, $injectionfinish, $id, $breedingid, $injectionstatus;

    //public function __construct($injectionid, $injectiontype, $injectiondate, $injectionfinish, $id, $breedingid, $injectionstatus) {
        
    //}






    public function getHTML(){
        $injection_sign = $this->injectionstatus == 'on' ? '&#10004;' : '';
        return "<tr>
                <td><a href='index.php?str=inj&action=mod&id='{$this->injectionid}'>{$this->injectionid}</a></td>
                <td>{$this->injectiontype}</td>
                <td>{$this->injectiondate}</td>

                <td>{$this->injectionfinish}</td>
                <td>{$this->id}</td>
                <td>{$this->breedingid}</td>
                <td>{$injection_sign}</td>
                </tr>";
    }
}




?>