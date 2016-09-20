<?php
// require_once("../bd/connect.php");
	


	//AQUI É CONTABILIZADO TODOS OS VISITANTES MENSAL

function Visitantes(){
	require_once("bd/connect.php");
	$pdo = Database::conexao();

	$data = date('m');
	$ano = date('Y');
	$dia = date('d');

	$meses = array(1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril', 5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto', 9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro');
	for($i=0;$i<=12;$i++)
	{
		if ($data == $i){
		$data_completa = $meses[$i].$ano;
		$data_mensal = $meses[$i];
		} 
	}


	$query = $pdo->prepare('SELECT * from tb_visitantes order by cod_vis desc');  
 	$query->execute();
	$row = $query->fetch(PDO::FETCH_OBJ);

	$mes = $row->mes_vis;
	$qtd = $row->qtd_vis;
	$cod = $row->cod_vis;
	$quantidade = ($qtd + 1);
	$mes_comparacao = $mes.$ano;


		//VISITANTES MENSAL

	if ($data_completa == $mes_comparacao){
		
		if(isset($_COOKIE["contador"])){
			//Nada acontece
		} else {
		setcookie('contador', 'visitantes', (time() + (30 * 24 * 3600)));
		$query = $pdo->prepare('UPDATE tb_visitantes SET qtd_vis="'.$quantidade.'" WHERE cod_vis ="'.$cod.'"');  
	 	$query->execute();
		}
		
	} else {
		$query = $pdo->prepare('INSERT INTO tb_visitantes(mes_vis, qtd_vis, ano) VALUES ("'.$data_mensal.'","1","'.$ano.'")');  
	 	$query->execute();
	}
}


	//AQUI É O ANUAL
function Anual()
{
	
}
?>