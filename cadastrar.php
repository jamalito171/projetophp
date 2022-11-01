<?php

require __DIR__.'/vendor/autoload.php';
/**
 * TESTE 
 * echo "<pre>";
 *print_r($_POST);
 *echo "</prev>";
 *exit;
 */
define('TITLE','Cadastrar Vaga');
use \App\Entity\Vaga; //uso da classe Vaga



if(isset($_POST['titulo'],$_POST['descricao'],$_POST['ativo'])){
/**
 * VALIDAÇÃO DO POST
 *  Verifica se os campos estão preenchidos
*/
    $obVaga = new Vaga;
    $obVaga->titulo = $_POST['titulo'];
    $obVaga->descricao = $_POST['descricao'];
    $obVaga->ativo = $_POST['ativo'];
    $obVaga->cadastrar(); //cadastrar vaga (método da classe vaga)
   
    header('location: index.php?status=success');
    exit;


}

include __DIR__.'/includes/header.php';
include __DIR__.'/includes/formulario.php';
include __DIR__.'/includes/footer.php';