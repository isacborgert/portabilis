<?php
include 'menu.php';
// 1.0 Inicio do bloco excluindo cursos back-end
if ($_POST['del_curso']){
// 1.1 Inicio bloco verifica se tem aluno cadastrado para o curso
//Obs: Hostinger não aceita banco InnoDB por isso tenho que verificar se tem aluno para o curso
  $verifica_aluno = mysql_query("SELECT * FROM alunos WHERE cd_curso=".$_POST['del_curso']."");
  $verifica_aluno = mysql_num_rows($verifica_aluno);
  if($verifica_aluno == 0){
// 1.1 Fim bloco verifica se tem aluno cadastrado para o curso
    $sql = "DELETE FROM `cursos` WHERE cd_curso = '".$_POST['del_curso']."'";
    mysql_query($sql) or die ("
    <center><b><font color='green' size='5' face='Verdana'>Não foi possível exluir o curso!
    <br /> O banco diz -</b> " .mysql_error());
  }else{
    echo "<b><center>
      <script language='javascript' title='Ola' type='text/javascript'>
        alert('Exclua os alunos primeiro!');window.location.href='$PHP_SELF'
      </script>    
      </center></b>";
  }
}
// 1.0 Fim do bloco excluindo cursos back-end
// 2.0 Inicio do bloco cadastro de curso back-end
if ($_POST['curso_insert']){
  if ($_POST['hora_curso'] != '' and $_POST['nm_curso'] != '' and $_POST['detalhes'] != ''){
    $sql = "
      INSERT INTO `cursos`(`nm_curso`, `carga_horaria`, `detalhes`) 
      VALUES ('".$_POST['nm_curso']."','".$_POST['hora_curso']."','".$_POST['detalhes']."')";
    mysql_query($sql) or die ("
      <center><b><font color='green' size='5' face='Verdana'>Não foi possível!
      <br /> O banco diz -</b> " .mysql_error());
  }else{
     echo "<b><center>
        <script language='javascript' title='Ola' type='text/javascript'>
          alert('Dados invalidos, preencha adequadamente conforme solicitado e tente novamente!');window.location.href='$PHP_SELF'
        </script>    
        </center></b>";  
  }
}
// 2.0 Fim do bloco cadastro de curso back-end
// 3.0 Inicio do bloco cadastro curso front-end
if ($_SESSION['cd_usuario']){
  $tabela = "
    <html>
      <center>
         <div class='contorno2'><b>
          <font color='green' size='5' face='Verdana'>Cadastre um curso!</font></b>  
          <form background-color='transparent' action='$PHP_SELF' method=POST>
              Nome do curso 
              <input type='text' size='10' value='".$linha['nm_curso']."' maxlength='10' name='nm_curso'>
                carga hor&aacute;ria  
                <input type='text' size='5' value='".$linha['nm_curso']."' maxlength='5' name='hora_curso'>
                detalhes 
                <input type='text' size='50' value='".$linha['nm_curso']."' maxlength='140' name='detalhes'>
                <input type='hidden' title='Cadastrar' value='Cadastrar' name='curso_insert'> <!-- para funcinar no firefox -->
                <input type='image' src='botoes/go.png' width='25' height='25' title='Cadastrar aluno' value='Cadastrar' name='curso_insert'/>
             </div>
          </form>";
  print $tabela;
}
// 3.0 Fim do bloco cadastro curso front-end
// 4.0 Inicio do bloco pesquisa e visualiza curso
if ($_SESSION['cd_usuario']){
 $tabela = "
    <form background-color='transparent' action='$PHP_SELF' method=POST><br />
      <div class='contorno2'>
        Pesquisar por 
        <select name='pesquisa_por'>
          <option value='c.nm_curso'>Curso</option>
          <option value='c.carga_horaria'>Carga Hor&aacute;rio</option>
          <option value='c.detalhes'>Detalhes</option>  
        </select>
        <input type='text' 
          onfocus=\"if(this.value=='Digite aqui sua pesquisa')this.value='';\" 
          onblur=\"if(this.value=='')this.value='Digite aqui sua pesquisa';\" 
          size='25' value ='Digite aqui sua pesquisa' maxlength='25' name='pesquisa' /> 
        <input type='submit' name ='pesquisa_filtro' title='Pesquisar' value='Pesquisar'>
      </div>
    </form>
    <div class='contorno1'>Tabela de cursos</div>
    <link rel=\"stylesheet\" type=\"text/css\" href=\"CSS/tabela.css\" /> <!-- incluindo arquivo tabela.css -->
        <table border='1'bgcolor=#666600>
          <th bgcolor=#333333>Nome</th>
          <th bgcolor=#333333>Carga</th>
          <th bgcolor=#333333>Detalhes</th>
          <th bgcolor=#333333>Qtd Alunos</th>
          <th bgcolor=#333333>Excluir</th>";
  if (!$_POST['pesquisa_filtro']){
    $sql = "
      select c.cd_curso, c.nm_curso, c.carga_horaria, c.detalhes, 
      count(a.cd_curso) as 'qtd_alunos' 
      from cursos as c left join alunos as a 
      on c.cd_curso = a.cd_curso 
      group by c.cd_curso limit 30";
  }else{
    if ($_POST['pesquisa']=== 'Digite aqui sua pesquisa'){
        $pesquisa = "";
    }else{
        $pesquisa = "".$_POST['pesquisa']."";
    }
    $sql = "
      select c.cd_curso, c.nm_curso, c.carga_horaria, c.detalhes, 
      count(a.cd_curso) as 'qtd_alunos' 
      from cursos as c left join alunos as a 
      on c.cd_curso = a.cd_curso 
      where ".$_POST['pesquisa_por']." like '%$pesquisa%' 
      group by c.cd_curso limit 30";
  }
  $select = mysql_query($sql);
  while ($linha = mysql_fetch_array($select)){
    $tabela .= "
       <tr>
         <td>
           <input type='text' size='10' value='".$linha['nm_curso']."' maxlength='10' readonly='readonly' />
         </td><td>
           <input type='text' size='6' value='".$linha['carga_horaria']." horas' maxlength='6' readonly='readonly'  /> 
         </td><td>
            <input type='text'  size='100' value='".$linha['detalhes']."' maxlength='100' readonly='readonly' />
         </td><td>
            <div style='text-align:center;font-weight: bold'>
            <input type='text'  size='5' value='".$linha['qtd_alunos']."' maxlength='5' readonly='readonly' />
         </td><td>
            <form background-color='transparent' action='$PHP_SELF' method='POST' enctype='multipart/form-data'>
              <input type='hidden' title='del' value='".$linha['cd_curso']."' name='del_curso'><!-- Para funcionar no Firefox -->
              <input type='image' src='botoes/del.png' align=\"right\" width='25' height='25' title='Excluir curso' value='".$linha['cd_curso']."' onclick=\"return confirm('Confirma exclusao?')\" name='del_curso'>
            </form>
         </td>";
  }
  $tabela .= "
       </tr>
     </table>
    </center> 
  </html>";
  print $tabela;
  // 4.0 Fim do bloco pesquisa e visualiza curso
}else{
echo "<meta HTTP-EQUIV='refresh'CONTENT='0;URL=$dominio'>";
}
?>







