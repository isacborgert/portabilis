<?php
include 'menu.php';
// 1.0 Inicio do bloco remove aluno back-and
if ($_POST['remove_aluno_cd_curso']){
  $sql = "
    DELETE FROM `alunos` 
    WHERE cd_curso = ".$_POST['remove_aluno_cd_curso']." 
    and cd_pessoa = ".$_POST['cd_pessoa']."";
  mysql_query($sql) or die ("
      <center><b>
        <font color='green' size='5' face='Verdana'>Não foi possível remover o aluno! 
        <br /> O banco diz -
      </b></center> " .mysql_error());
}
// 1.0 Fim do bloco remove aluno back-and
// 2.0 Inicio do bloco cadastra aluno back-and
if($_POST['aluno_insert']){
  if ($_POST['cd_pessoa'] != '0' and $_POST['cd_curso'] != '0' and $_POST['responsavel'] != '0'){
    $sql = "
      INSERT INTO `alunos`(`cd_pessoa`, `cd_curso`, responsavel) 
      VALUES ('".$_POST['cd_pessoa']."','".$_POST['cd_curso']."', ".$_POST['responsavel'].")";
    mysql_query($sql) or die ("
      <center><b><font color='green' size='5' face='Verdana'>Não foi possível cadastrar!
      <br /> O banco diz -</b> " .mysql_error());
    echo "
      <b><center>
          <script language='javascript' title='Ola' type='text/javascript'>
            alert('Aluno cadastrado com sucesso!');window.location.href='$PHP_SELF'
          </script>    
      </center></b>";
  }else{
      echo "<b><center>
        <script language='javascript' title='Ola' type='text/javascript'>
          alert('Dados invalidos, preencha adequadamente conforme solicitado e tente novamente!');window.location.href='$PHP_SELF'
        </script>    
        </center></b>";  
  
  }
}
// 2.0 Fim do bloco cadastra aluno back-and
// 3.0 Inicio do bloco cadastra aluno front-and
if ($_SESSION['cd_usuario']){
  $tabela = "
    <html>
      <center>
         <div class='contorno2'>
          <b><font color='green' size='5' face='Verdana'>Cadastre um aluno!</font></b>  
          <form background-color='transparent' action='$PHP_SELF' method=POST>
              Curso ";  
              $tabela .="
                <select name='cd_curso'>
                  <option value='0'>Escolha um curso</option>";
                    $sql = "SELECT * FROM cursos ORDER BY nm_curso ASC limit 30";
                    $qr = mysql_query($sql) or die(mysql_error());
                    while($ln = mysql_fetch_assoc($qr)){
                      $tabela .= "<option value=".$ln['cd_curso'].">".$ln['nm_curso']."</option>";
                    }
              $tabela .= "
                </select>
                aluno  
                <select name='cd_pessoa'>
                  <option value='0'>Selecione a pessoa</option>";
                    $sql = "SELECT * FROM pessoas ORDER BY nm_pessoa ASC";
                    $qr = mysql_query($sql) or die(mysql_error());
                    while($ln = mysql_fetch_assoc($qr)){
                      $tabela .= "<option value=".$ln['cd_pessoa'].">".$ln['nm_pessoa']."</option>";
                    }
              $tabela .= "
                </select>
                respons&aacute;vel
                <select name='responsavel'>
                  <option value='0'>Selecione a pessoa</option>";
                    $sql = "SELECT * FROM pessoas ORDER BY nm_pessoa ASC";
                    $qr = mysql_query($sql) or die(mysql_error());
                    while($ln = mysql_fetch_assoc($qr)){
                      $tabela .= "<option value=".$ln['cd_pessoa'].">".$ln['nm_pessoa']."</option>";
                    }
              $tabela .= "
                </select>
                <input type='hidden' title='Cadastrar' value='Cadastrar' name='aluno_insert'> <!-- para funcinar no firefox -->
                <input type='image' src='botoes/go.png' width='25' height='25' title='Cadastrar aluno' value='Cadastrar' name='aluno_insert'/>
             </div>
          </form>";
  print $tabela;
  // 3.0 Fim do bloco cadastra aluno front-and
  // 4.0 Inicio do bloco pesquisa e visualiza aluno fron-and e back-and. Remove aluno front-and 
  $tabela = "
    <form background-color='transparent' action='$PHP_SELF' method=POST><br />
      <div class='contorno2'>
        Pesquisar por 
        <select name='pesquisa_por'>
          <option value='c.nm_curso'>Curso</option>
          <option value='p.nm_pessoa'>Aluno</option>
          <option value='ci.nm_cidade'>Cidade</option>
          <option value='p.email'>Email</option>
          <option value='p.sexo'>Sexo</option>   
        </select>
        <input type='text' 
          onfocus=\"if(this.value=='Digite aqui sua pesquisa')this.value='';\" 
          onblur=\"if(this.value=='')this.value='Digite aqui sua pesquisa';\" 
          size='25' value ='Digite aqui sua pesquisa' maxlength='25' name='pesquisa' /> 
        <input type='submit' name ='pesquisa_filtro' title='Pesquisar' value='Pesquisar'>
      </div>
    </form>
    <div class='contorno1'>Tabela de alunos vs cursos</div>
    <link rel=\"stylesheet\" type=\"text/css\" href=\"CSS/tabela.css\" /> <!-- incluindo arquivo tabela.css -->
        <table border='1'bgcolor=#666600>
          <th bgcolor=#333333>Nome</th>
          <th bgcolor=#333333>Curso</th>
          <th bgcolor=#333333>Sexo</th>
          <th bgcolor=#333333>Telefone</th>
          <th bgcolor=#333333>Email</th>
          <th bgcolor=#333333>Cidade</th>
          <th bgcolor=#333333>Res</th>
          <th bgcolor=#333333>Del</th>";
  if (!$_POST['pesquisa_filtro']){
    $sql = "
      select a.responsavel, a.cd_curso,a.cd_pessoa, p.nm_pessoa, c.nm_curso, p.sexo, p.telefone, p.email, ci.nm_cidade, ci.uf_estado 
      from pessoas as p, alunos as a, cursos as c, cidades as ci
      where p.cd_pessoa = a.cd_pessoa
      and a.cd_curso = c.cd_curso
      and p.cd_cidade = ci.cd_cidade limit 30";
  }else{
    if ($_POST['pesquisa']=== 'Digite aqui sua pesquisa'){
        $pesquisa = "";
    }else{
        $pesquisa = "".$_POST['pesquisa']."";
    }
    $sql = "
      select a.responsavel, a.cd_curso,a.cd_pessoa, p.nm_pessoa, c.nm_curso, p.sexo, p.telefone, p.email, ci.nm_cidade, ci.uf_estado 
      from pessoas as p, alunos as a, cursos as c, cidades as ci
      where p.cd_pessoa = a.cd_pessoa
      and a.cd_curso = c.cd_curso
      and p.cd_cidade = ci.cd_cidade
      and ".$_POST['pesquisa_por']." like '%$pesquisa%' limit 30";
  }
  $select = mysql_query($sql);
  while ($linha = mysql_fetch_array($select)){
    $tabela .= "
         <tr>
             <td>
               <input type='text' size='15' value='".$linha['nm_pessoa']."' maxlength='30' readonly='readonly' />
             </td><td>
               <input type='text' size='15' value='".$linha['nm_curso']."' maxlength='20' readonly='readonly'  /> 
             </td><td>
               <input type='text'  size='8' value='".$linha['sexo']."' maxlength='8' readonly='readonly' />
             </td><td>
               <input type='text'  size='14' value='".$linha['telefone']."' maxlength='14' readonly='readonly' name='telefone' />
             </td><td>
               <input type='text'  size='20' value='".$linha['email']."' maxlength='20' readonly='readonly' />
             </td><td>
               <input type='text'  size='15' value='".$linha['nm_cidade']." ".$linha['uf_estado']."' maxlength='15' readonly='readonly' />
             </td>
             </td><td>
              <form background-color='transparent' action='$dominio/pessoas.php' method=POST>
                <input type='hidden' name='ver_pessoa' value='".$linha['responsavel']."' /> <!-- para funcinar no firefox -->
                <input type='image' src='botoes/go.png' width='25' height='25' title='Ver respons&aacute;vel' value='".$linha['responsavel']."' name='ver_pessoa'/>   
              </form>
             </td><td>
              <form background-color='transparent' action='$PHP_SELF' method=POST>
               <input type='hidden' name='cd_pessoa' value='".$linha['cd_pessoa']."' />
               <input type='hidden' name='remove_aluno_cd_curso' value='".$linha['cd_curso']."' /> <!-- para funcinar no firefox -->
               <input type='image' src='botoes/del.png' width='25' height='25' title='Remover aluno desse curso' value='".$linha['cd_curso']."'
                onclick=\"return confirm('Confirma exclusao do aluno nesse curso?')\" name='remove_aluno_cd_curso'/>
              </form>";
  }
  $tabela .= "
             </td> 
         </tr>
       </table>
      </center> 
    </html>";
  print $tabela;
  // 4.0 Fim do bloco pesquisa e visualiza aluno fron-and e back-and. Remove aluno front-and 
}else
  echo "<meta HTTP-EQUIV='refresh'CONTENT='0;URL=$dominio'>";
?>
