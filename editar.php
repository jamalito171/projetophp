<?php

require __DIR__.'/vendor/autoload.php';

define('TITLE','Editar Vaga');
/**
 * TESTE 
 * echo "<pre>";
 *print_r($_POST);
 *echo "</prev>";
 *exit;
 */
use \App\Entity\Vaga; //uso da classe Vaga

//VALIDAÇÃO DO ID 

if(!isset($_GET['id']) or !is_numeric($_GET['id'])){
    header('location: index.php?status=error');
    
    exit;
}

//CONSULTA A VAGA

$obVaga = Vaga::getVaga($_GET['id']);

//VALIDAÇAO DA VAGA

if(!$obVaga instanceof Vaga){
    header('location: index.php?status=error');    
    exit;
}

if(isset($_POST['titulo'],$_POST['descricao'],$_POST['ativo'])){
/**
 * VALIDAÇÃO DO POST
 *  Verifica se os campos estão preenchidos
*/
    
    $obVaga->titulo = $_POST['titulo'];
    $obVaga->descricao = $_POST['descricao'];
    $obVaga->ativo = $_POST['ativo'];
    $obVaga->atualizar(); //atualizar vaga (método da classe vaga)
   
    header('location: index.php?status=success');
    exit;


}

include __DIR__.'/includes/header.php';
include __DIR__.'/includes/formulario.php';
include __DIR__.'/includes/footer.php';