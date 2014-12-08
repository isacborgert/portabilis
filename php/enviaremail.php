<?php
include 'dominio.php';
$para = "isacborgert@gmail.com";
$nome = $_POST['nome'];
$email = $_POST['email'];
$assunto = $_POST['assunto'];
$mensagem = "<strong>Nome: </strong>".$nome;
$mensagem .= "<br> <strong>Email: </strong>".$email;
$mensagem .= "<br> <strong>Mensagem: </strong>".$_POST['mensagem'];
$headers = "Content-Type:text/html; charset=UTF-8\n"; 
$headers .= "From: websport<$email>\n";
$headers .= "X-Sender: <sistema@websport.esy.es>\n"; 
$headers .= "X-Mailer: PHP v".phpversion()."\n"; 
$headers .= "X-IP: ".$_SERVER['REMOTE_ADDR']."\n"; 
$headers .= "Return-Path: <sistema@websport.esy.es>\n";
$headers .= "MIME-Version: 1.0\n"; mail($para, $assunto, $mensagem, $headers);

echo "<b><br /> <p>
  <center>
    <script language='javascript' type='text/javascript'>
      alert('Email enviado com sucesso!');window.location.href='$dominio/'
    </script>
  </center></b>";
//echo "<meta HTTP-EQUIV='refresh'CONTENT='2;URL=$dominio/'>";
?>
