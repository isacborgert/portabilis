<?php
include 'menu.php';
if ($_POST['text_area']){
$sql = "INSERT INTO `time_line`(`texto`, `cd_usuario`) VALUES ('".$_POST['text_area']."',".$_SESSION['cd_usuario'].")";
mysql_query($sql) or die ("
      <center><b><font color='green' size='5' face='Verdana'>Não foi possível gravas a mensagem, 
      <input type='submit' value='Voltar' title='Voltar para home' 
      onclick=\"location. href= '$PHP_SELF'\">
      <br /> O banco diz -</b> " .mysql_error());
}
if (!$_SESSION['cd_usuario']){
  echo"
    <html>
      <body>
        <center>
          <p> <br /><br />
            <div class='contorno1'>
	            <a ref='#'><b>Sistema de teste, user = admin@teste.com, senha = 1234 </b></a>
	          <div class='contorno2'>
              <br /><br />
              <a ref='#'><b>Entre com usu&aacuterio e senha</b></a><br />
              <form background-color='transparent' action='$PHP_SELF' method=POST>
                Email
                <input type='text' size='30' maxlength='30' name='email' /> 
                Senha: 
                <input type='password'  size='15' maxlength='15' name='senha' />
                <input type='submit' name='login' value='Login'>
              </form><br />
            </div>
        </center>
      </body>
    </html>";
}else{
  $tabela = "
    <html>
      <center>
          <form background-color='transparent' action='$PHP_SELF' method=POST>
          Bem vindo ao sistema X <br />
            Deixe um recado para todos os usu&aacuterios na time line!<br />
             <td><input type='text' size='70' maxlength='150' name='text_area' /></td>
             <input type='image' src='botoes/go.png' width='25' height='25' title='Postar mensagem' value='Cadastrar' name='msg_time_line'/>
          </form>
      </center>
    </html>";
  print $tabela; 
  $sql = "
    SELECT tl.texto,p.nm_pessoa 
    FROM time_line as tl, usuarios as us, pessoas as p 
    WHERE tl.cd_usuario = us.cd_usuario
    and p.cd_pessoa = us.cd_pessoa
    order by tl.cd_time_line DESC 
    limit 30";
  $res = mysql_query($sql);
  while ($linha = mysql_fetch_array($res)){
    print "
      <html>
        <div align='left'>
          "."<b>".$linha['nm_pessoa'].": </b>'".$linha['texto']."'
        </div>
      </html>";
  }
}
?>
