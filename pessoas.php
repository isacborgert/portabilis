<?php
include 'menu.php';
// 1.0 inicio linha de pesquisa front-end
if ($_SESSION['cd_usuario']){
  $tabela = "
    <html>
      <center>
       <form background-color='transparent' action='$PHP_SELF' method=POST><br />
        <div class='contorno2'>
          Pesquisar por 
          <select name='pesquisa_por'>
            <option value='nm_pessoa'>Nome</option>
            <option value='email'>Email</option>  
          </select>
          <input type='text' 
            onfocus=\"if(this.value=='Digite aqui sua pesquisa')this.value='';\" 
            onblur=\"if(this.value=='')this.value='Digite aqui sua pesquisa';\" 
            size='25' value ='Digite aqui sua pesquisa' maxlength='25' name='pesquisa' /> 
          <input type='submit' name ='pesquisa_filtro' title='Pesquisar' value='Pesquisar'>
        </div>
       </form>
      </center>
    </html>";
  print $tabela;
}
// 1.0 fim linha de pesquisa front-end
// 2.0 inicio bloco de pesquisa back-end
if ($_POST['pesquisa_filtro']){
    if ($_POST['pesquisa']=== 'Digite aqui sua pesquisa')
        $pesquisa = "";
    else
        $pesquisa = "".$_POST['pesquisa']."";
    $tabela = "
      <html>
        <center>
        <link rel=\"stylesheet\" type=\"text/css\" href=\"CSS/tabela.css\" /> <!-- incluindo arquivo tabela.css -->
          <table border='1'bgcolor=#666600>
            <th bgcolor=#333333>Nome</th>
            <th bgcolor=#333333>Sexo</th>
            <th bgcolor=#333333>Telefone</th>
            <th bgcolor=#333333>Email</th>
            <th bgcolor=#333333>Ver</th>";
    $sql = "
      select * 
      from pessoas
      where ".$_POST['pesquisa_por']." like '%$pesquisa%' limit 30";
    $select = mysql_query($sql);
    while ($linha = mysql_fetch_array($select)){
      $tabela .="
            <tr>
              <td>
                <input type='text' size='15' value='".$linha['nm_pessoa']."' maxlength='30' readonly='readonly' />
              </td><td>
                <input type='text'  size='8' value='".$linha['sexo']."' maxlength='8' readonly='readonly' />
              </td><td>
                <input type='text'  size='14' value='".$linha['telefone']."' maxlength='14' readonly='readonly' name='telefone' />
              </td><td>
                <input type='text'  size='20' value='".$linha['email']."' maxlength='20' readonly='readonly' />
              </td><td>
                <form background-color='transparent' action='$PHP_SELF' method=POST>
                  <input type='hidden' name='ver_pessoa' value='".$linha['cd_pessoa']."' /> <!-- para funcinar no firefox -->
                  <input type='image' src='botoes/go.png' width='25' height='25' title='Ver cadastro' value='".$linha['cd_pessoa']."' name='ver_pessoa'/>
                  </form> 
              </td>
            </tr>";
    }
    $tabela .= "
          </table>
        </center>
      </html>";
    print $tabela;  
}
// 2.0 fim bloco de pesquisa back-end
// 3.0 inicio bloco de remover back-end
/* Nao esta pronto
  if ($_POST['del_pessoa']){
  $sql = "DELETE FROM `alunos` WHERE cd_pessoa = '".$_POST['cd_pessoa']."'";
  mysql_query($sql) or die ("
    <center><b><font color='green' size='5' face='Verdana'>Não foi possível , 
    <input type='submit' value='Voltar' title='Voltar' 
    onclick=\"location. href= '$PHP_SELF'\">
    <br /> O banco diz -</b> " .mysql_error());
  $sql2 = "DELETE FROM `pessoas` WHERE cd_pessoa = '".$_POST['cd_pessoa']."'";
  mysql_query($sql2) or die ("
    <center><b><font color='green' size='5' face='Verdana'>Não foi possível e, 
    <input type='submit' value='Voltar' title='Voltar' 
    onclick=\"location. href= '$PHP_SELF'\">
    <br /> O banco diz -</b> " .mysql_error());
  unlink('imagens/usuarios/'.$_POST['cd_pessoa'].'.jpg');
  unset($_POST['cd_pessoa']); // Deleta uma variável da sessão
  session_destroy(); // Destrói toda sessão
}*/
// 3.0 fim bloco de remover back-end
// 4.0 inicio bloco de inserir e atualizar registro no banco back-end
if($_POST['pessoa_insert'] or $_POST['pessoa_update']){
  $procura_dt = strripos($_POST['dt_nascimento'],"/");
  $procura_email = strripos($_POST['email'],"@");
  if ($procura_dt == true and $procura_email == true){
    $data = explode('/', $_POST['dt_nascimento']);
    $data = $data[2].'-'.$data[1].'-'.$data[0];
    if ($_POST['pessoa_insert'])
      $sql = "
        INSERT INTO `pessoas`(`nm_pessoa`, `telefone`, `email`, `cd_cidade`, `dt_nascimento`, `sexo`) 
        VALUES ('".$_POST['nm_pessoa']."','".$_POST['telefone']."','".$_POST['email']."','".$_POST['cd_cidade']."','$data','".$_POST['sexo']."')";
    else
       $sql = "
       UPDATE `pessoas` SET `nm_pessoa` = '".$_POST['nm_pessoa']."', `telefone` = '".$_POST['telefone']."', `email` = '".$_POST['email']."', `cd_cidade` = ".$_POST['cd_cidade'].", `dt_nascimento` = '$data', `sexo` = '".$_POST['sexo']."' where cd_pessoa = ".$_POST['cd_pessoa']."";
    mysql_query($sql) or die ("
      <center><b><font color='green' size='5' face='Verdana'>Não foi possível!
      <br /> O banco diz -</b></font> " .mysql_error());
    echo "<b><center>
      <script language='javascript' title='Ola' type='text/javascript'>
        alert('Sucesso!');window.location.href='$PHP_SELF'
      </script>    
      </center></b>";
    if ($_POST['pessoa_insert']){
     $sql2 = "select * from pessoas where email = '".$_POST['email']."'";
        $sql2 = mysql_query($sql2);
        $sql2 = mysql_fetch_array($sql2);
        $destino_foto = 'imagens/usuarios/'.$sql2['cd_pessoa'].'.jpg';
    }else
      $destino_foto = 'imagens/usuarios/'.$_POST['cd_pessoa'].'.jpg';
    $arquivo_tmp = $_FILES['foto_perfil']['tmp_name'];
    move_uploaded_file( $arquivo_tmp, $destino_foto  );
  }else{
  echo "<b><center>
      <script language='javascript' title='Ola' type='text/javascript'>
        alert('Dados invalidos, preencha adequadamente conforme solicitado e tente novamente!');window.location.href='$PHP_SELF'
      </script>    
      </center></b>";
  }
// 4.0 fim bloco de inserir e atualizar registro no banco back-end
// 5.0 inicio bloco de inserir e atualizar registro no banco front-end
}elseif ($_SESSION['cd_usuario'] and !$_POST['pesquisa_filtro']){
  if ($_POST['ver_pessoa']){
    $sql = "SELECT * FROM pessoas where cd_pessoa = '".$_POST['ver_pessoa']."'";
    $select = mysql_query($sql);
    $fetch = mysql_fetch_array($select);
    $data = explode('-', $fetch['dt_nascimento']);
    $data = $data[2].'/'.$data[1].'/'.$data[0];
    $sql_city = "SELECT * FROM `cidades` WHERE cd_cidade = '".$fetch['cd_cidade']."'";
    $nm_pessoa = $fetch['nm_pessoa'];
    $telefone = $fetch['telefone'];
    $sexo = $fetch['sexo'];
    $dt_nascimento = $fetch['dt_nascimento'];
    $email = $fetch['email'];
  }else{
    $data = "dd/mm/aaaa";
    if ($_POST['escolhe_estado'])
      $sql_city = "SELECT * FROM `cidades` WHERE cd_estado = '".$_POST['escolhe_estado']."'";
    else
      $sql_city = "SELECT * FROM `cidades` WHERE cd_cidade = 1";
    $nm_pessoa = "Nome Completo";
    $telefone = "Telefone com DDD";
    $sexo = "Masculino";
    $email = "Email";
  }
  $select_city = mysql_query($sql_city);
  $fetch_city = mysql_fetch_array($select_city);
  $foto_user = "imagens/usuarios/".$fetch['cd_pessoa'].".jpg";
  if (!file_exists($foto_user))
    $foto_user = "imagens/usuarios/sem_foto.jpg";
  $tabela = "
    <html>
      <center>
        <img src='$foto_user' width='200' height='200' />
        <div class='contorno2'><b><font color='green' size='5' face='Verdana'>Cadastro de pessoas!</font></b> <br /><br />";
  // 5.1 Inicio do bloco escolhe estado /////////
  $tabela .= "
        <form action='$PHP_SELF' method=POST enctype='multipart/form-data'> 
        <table width='500' border='0' align=\"center\" cellpadding='0' cellspacing='2'>
            <tr>
            <td align=\"right\"><div class='contorno2'>Estado </td>";
  if(!$_POST['escolhe_estado'] or $_POST['ver_pessoa']){
    if ($_POST['ver_pessoa'])
       $tabela .="<td>
         <input type='hidden' value= '".$_POST['ver_pessoa']."' name = 'ver_pessoa' />
         <select name='escolhe_estado' onchange='this.form.submit()'>
         <option value='".$fetch_city['cd_estado']."'>".$fetch_city['uf_estado']."</option>";
    else
      $tabela .="<td>
        <select name='escolhe_estado' onchange='this.form.submit()'>";   
    $sql = "SELECT * FROM estados ORDER BY nm_estado ASC";
    $qr = mysql_query($sql) or die(mysql_error());
    while($ln = mysql_fetch_assoc($qr)){
      $tabela .= "<option value=".$ln['cd_estado'].">".$ln['nm_estado']."</option>";
    }
  }else{
    $tabela .="  <td><select name='escolhe_estado' onchange='this.form.submit()'>";
    $sql = "SELECT * FROM estados where cd_estado = ".$_POST['escolhe_estado']." ORDER BY nm_estado ASC";
    $qr = mysql_query($sql) or die(mysql_error());
    while($ln = mysql_fetch_assoc($qr)){
      $tabela .= "<option value=".$ln['cd_estado'].">".$ln['nm_estado']."</option>";
    }
    $sql = "SELECT * FROM estados ORDER BY nm_estado ASC";
    $qr = mysql_query($sql) or die(mysql_error());
    while($ln = mysql_fetch_assoc($qr)){
      $tabela .= "<option value=".$ln['cd_estado'].">".$ln['nm_estado']."</option>";
    }
  }      
  $tabela .="
        </select></td></tr><tr>
       </table>
       </form>";    
  // 5.1 Fim do bloco escolhe estado      ///////////
  $tabela .= "
        <form action='$PHP_SELF' method=POST enctype='multipart/form-data'>
          <input type='hidden' value='".$fetch['cd_pessoa']."' name='cd_pessoa' /><br />
          <table width='500' border='0' align=\"center\" cellpadding='0' cellspacing='2'>
            <tr>";
  if ($_POST['escolhe_estado'])
    $cd_estado = $_POST['escolhe_estado'];
  else
    $cd_estado = $fetch_city['cd_estado'];
  $tabela .= "
       <td align=\"right\"><div class='contorno2'>Cidade  </td>
       <td>
       <select name='cd_cidade'>
         <option value='".$fetch_city['cd_cidade']."'>".$fetch_city['nm_cidade']."</option>";
  $sql = "SELECT * FROM cidades where cd_estado = $cd_estado ORDER BY nm_cidade ASC";
  $qr = mysql_query($sql) or die(mysql_error());
  while($ln = mysql_fetch_assoc($qr)){
    $tabela .= "<option value=".$ln['cd_cidade'].">".$ln['nm_cidade']."</option>";
  }
  $tabela .= "
     </select></td></tr>
     <tr>
       <td align=\"right\"><div class='contorno2'>Nome </td>
       <td>
         <input type='text'size='30' value ='$nm_pessoa' maxlength='30' name='nm_pessoa'
         onfocus=\"if(this.value=='Nome Completo')this.value='';\" 
         onblur=\"if(this.value=='')this.value='Nome Completo';\"  />
       </td>
     </tr>
     <tr>
       <td align=\"right\"><div class='contorno2'>Foto </td>
       <td><input type='file' name='foto_perfil' /></td></tr>
     <tr>
       <td align=\"right\"><div class='contorno2'>Email </td>
       <td>
          <input type='text' size='30' value ='$email' maxlength='30' name='email'
          onfocus=\"if(this.value=='Email')this.value='';\" 
          onblur=\"if(this.value=='')this.value='Email';\"  />
       </td>
     </tr>  
     <tr>
       <td align=\"right\"><div class='contorno2'>N&uacutemero Telefone</td>
       <td>
         <input type='text' size='20' value ='$telefone' maxlength='20' name='telefone'
         onfocus=\"if(this.value=='Telefone com DDD')this.value='';\" 
         onblur=\"if(this.value=='')this.value='Telefone com DDD';\"/>
       </td>
     </tr>
     <tr>
       <td align=\"right\"><div class='contorno2'>Data de Nascimento </td>
       <td>
         <input type='text' size='15' value ='$data' maxlength='15' name='dt_nascimento'
         onfocus=\"if(this.value=='dd/mm/aaaa')this.value='';\" 
         onblur=\"if(this.value=='')this.value='dd/mm/aaaa';\"  />
       </td>
     </tr>
     <tr>
       <td align=\"right\"><div class='contorno2'>Sexo  </td>
       <td>
          <select name='sexo'>";
  if ($sexo == 'Masculino')
    $tabela .="
       <option value='Masculino'>Masculino</option>
       <option value='Feminino'>Feminino</option>";
  else
    $tabela .="<option value='Feminino'>Feminino</option>
       <option value='Masculino'>Masculino</option>";
    $tabela .="   
       </select></td></tr>
       <tr>
       <td colspan='2' align=\"center\">";
  if ($_POST['ver_pessoa']){
    $tabela .= "
        <input type='hidden' title='Atualizar dados' value='Atualizar' name='pessoa_update'>
        <input type='image' src='botoes/go.png' width='25' height='25' title='Atualizar dados' value='Atualizar' name='pessoa_update'/>
      </form>
      <!--  Não esta pronto  form background-color='transparent' action='$PHP_SELF' method=POST enctype='multipart/form-data'> 
         <br /><br />
         <input type='hidden' title='del' value='del' name='del_pessoa'>
         <input type='image' src='botoes/del.png' align=\"right\" width='25' height='25' title='Excluir pessoa' value='".$fetch['cd_pessoa']."' onclick=\"return confirm('Confirma exclusao?')\" name='del_pessoa'>
      </form-->";
  }else{
    $tabela .= "
         <input type='hidden' title='Cadastrar pessoa' value='cadastrar' name='pessoa_insert'>
         <input type='image' src='botoes/go.png' width='25' height='25' title='Cadastrar pessoa' value='Atualizar' name='pessoa_insert'/>
       </form>";
   }
   $tabela .="
        </td></tr>
         </table>
       </center>
     </html>";
  print $tabela;
}
// 5.0 fim bloco de inserir e atualizar registro no banco front-end
elseif (!$_SESSION['cd_usuario'])
echo "<meta HTTP-EQUIV='refresh'CONTENT='0;URL=$dominio'>";
?>
