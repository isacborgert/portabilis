<?php
include 'conexao.php';
include 'php/dominio.php';
$versao = "Versao 1.0 em fase de testes";
session_start();
if ($_POST["login"]){
  $sql = "SELECT p.nm_pessoa,u.cd_usuario, p.email,u.senha FROM usuarios as u, pessoas as p WHERE p.cd_pessoa = u.cd_pessoa and p.email='".$_POST["email"]."'";
  $select = mysql_query($sql);
  $result = mysql_fetch_array($select);
  if ($_POST["email"] === $result['email'] and $_POST["senha"] === $result['senha']){
    $_SESSION['cd_usuario'] = $result['cd_usuario'];
    $_SESSION['nome_user'] = $result['nm_pessoa'];
    //echo "<meta HTTP-EQUIV='refresh'CONTENT='0;URL=$PHP_SELF'>";
  }else{
      echo "<b>
        <center>
          <script language='javascript' title='Ola' type='text/javascript'>
            alert('Login invalido, tente novamente');window.location.href='$dominio'
          </script>    
        </center></b>";
   }
}
if ($_POST['logoff']){
      unset($_SESSION['cd_usuario']); // Deleta uma variável da sessão
      session_destroy(); // Destrói toda sessão
    }
if ($_SESSION['cd_usuario']){
    $nome_user = $_SESSION['nome_user'];
  echo "<form background-color='transparent' align='right' action='$dominio/' method='POST'>
             <font color='#32CD99' size='3' face='Verdana'> Ol&aacute; $nome_user 
             <input type='hidden' value='Logoff' title='Sair' name='logoff'><!-- para funcinar no firefox -->
             <input type='image' src='botoes/del.png' width='25' height='25' value='Logoff' title='Sair' name='logoff'>
          </form><br />$versao";
 }
$menu = "
<html>
  <head>
    <title>
      Avaliacao Portabilis
    </title> 
    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" /> <!-- pra funcionar caracteres especiais no firefox -->
    <link href=\"favicon.ico\" rel='shortcut icon' type='image/x-icon'/> 
  </head>
  <body background=\"imagens/background.jpg\">
  <center> <style>
     *
{
	border: 0;
	margin: 0;
	padding: 3;
}
      .divlink a{
          color: #215E21;
          text-decoration:none;
          font-size:18;
          text-shadow:
            0 0 18px #FFF, 
            0 0 18px #FFF, 
            0 0 18px #FFF, 
            0 0 18px #FFF, 
            0 0 18px #FFF, 
            0 0 18px #FFF, 
            0 0 18px #FFF;
        } 
        .divlink a:hover{ 
          color: #32CD99;
          text-decoration:none;
          font-size:18;
          text-shadow: 
            0 0 25px #FFF, 
            0 0 25px #FFF, 
            0 0 25px #FFF, 
            0 0 25px #FFF, 
            0 0 25px #FFF, 
            0 0 25px #FFF, 
            0 0 25px #FFF; 
        }
         .contorno1 {
      text-shadow: 1px 0px 0px black, 
                   -1px 0px 0px black, 
                   0px 1px 0px black, 
                   0px -1px 0px black;
      font-size: 25;
      font-weight: bold;
      color: #FFF;
     }
     .contorno2 {
      text-shadow: 1px 0px 0px black, 
                   -1px 0px 0px black, 
                   0px 1px 0px black, 
                   0px -1px 0px black;
      font-size: 16;
      font-weight: bold;
      color: #C0D9D9;
     }
     a:hover{color:#32CD99;}//muda cor da fonte ao passar o mouse
    </style>
      <div class='divlink'>
        <a href='$dominio/' title='Ir para Pagina Inicial'>HOME </a>";
if (!$_SESSION['cd_usuario']){
  $menu .= "";
}else{

    $menu .="<a href='$dominio/pessoas.php' title= 'Pessoas'> PESSOAS</a>
             <a href='$dominio/cursos.php' title= 'Cursos'> CURSOS</a>
             <a href='$dominio/alunos.php' title= 'Alunos'> ALUNOS</a>";
}
$menu .= "
      </div>
      <br />
    </center>
  </body>
</html>";
echo $menu;
?>
