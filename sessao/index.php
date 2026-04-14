<?
    session_start();

    $_SESSION['nome'] = 'João';
    $_SESSION['email'] = 'email@teste.com';

    print_r($_SESSION);  
?>