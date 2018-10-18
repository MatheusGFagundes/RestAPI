<?php
class Utilities{
 
    public function getPaging($page, $total_rows, $records_per_page, $page_url){
 
        // pagina array
        $paging_arr=array();
 
        // botao da primeira pagina 
        $paging_arr["first"] = $page>1 ? "{$page_url}page=1" : "";
 
        // conta todos os produtos na base de dados para calcular total de páginas
        $total_pages = ceil($total_rows / $records_per_page);
 
        // intervalo dos links a serem mostrados
        $range = 2;
 
        // exibe links no 'intervalo de páginas' da 'página atual'  
        $initial_num = $page - $range;
        $condition_limit_num = ($page + $range)  + 1;
 
        $paging_arr['pages']=array();
        $page_count=0;
         
        for($x=$initial_num; $x<$condition_limit_num; $x++){
            // certifique que '$x é maior que 0' e 'menor ou igual ao $total_pages'
            if(($x > 0) && ($x <= $total_pages)){
                $paging_arr['pages'][$page_count]["page"]=$x;
                $paging_arr['pages'][$page_count]["url"]="{$page_url}page={$x}";
                $paging_arr['pages'][$page_count]["current_page"] = $x==$page ? "yes" : "no";
 
                $page_count++;
            }
        }
 
        // botao para a ultima pagina
        $paging_arr["last"] = $page<$total_pages ? "{$page_url}page={$total_pages}" : "";
 
        // formato json
        return $paging_arr;
    }
 
}
?>