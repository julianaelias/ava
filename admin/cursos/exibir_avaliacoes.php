<?php
require_once("../../acesso_restrito/authSession.php");
require_once("../../conf/confBD.php");

	$cursoA = $_POST['curso'];
?> 
     <div class="container" style="width:90%; text-align:center;" >
		<div class="panel panel-primary">
  			<div class="panel-heading">Avaliações</div>
            <div class="panel-body">
				<?php 
                try{
                // instancia objeto PDO, conectando no mysql
                $conexao = conn_mysql();
                
                $SQLSelect = "SELECT 
                                A.AVALIACAO, 
                                A.COMENTARIO, 
                                A.CURSO, 
                                date_format(A.DATA,'%d/%m/%Y') AS DATA,
                                A.INSCRICAO,
                                A.NOTA,
                                A.USUARIO,
                                U.NOME,
                                U.TIPO,
                                U.DEPARTAMENTO,
                                U.EMAIL,
                                C.TITULO
                                FROM AVALIACOES A
                                JOIN USUARIOS U ON U.USUARIO = A.USUARIO
                                JOIN CURSOS C ON C.CURSO = A.CURSO
                                WHERE A.CURSO = :CURSO
                                AND U.STATUS = 1";
                                
                //prepara a execução da sentença
                $operacao = $conexao->prepare($SQLSelect);
                $operacao->bindParam(':CURSO', $cursoA, PDO::PARAM_INT);
                
                
                $pesquisar = $operacao->execute();
            
                //captura TODOS os resultados obtidos
                $resultados = $operacao->fetchAll();
                
                // fecha a conexão (os resultados já estão capturados)
                $conexao = null;
            
                // se há resultados, os escreve em uma tabela
                if (count($resultados)> 0){?>
                                        
                                        
                    <table class="table table-striped text-center">        
                        <thead>
                            <tr>
                                <th class="text-center">Usuário / Departamento</th>
                                <th class="text-center">Data</th>
                                <th class="text-center">Nota</th>
                                <th class="text-center">Comentário</th>
                            </tr>
                        </thead>
                        <tbody>
                <?php
                                            
            
                    foreach($resultados as $valor){
                        $avaliacao = utf8_encode($valor['AVALIACAO']);
						$departamento = utf8_encode($valor['DEPARTAMENTO']);
                        $comentario= utf8_encode($valor['COMENTARIO']);
                        $nota = utf8_encode($valor['NOTA']);
                        $data = utf8_encode($valor['DATA']);
                        $usuario = utf8_encode($valor['USUARIO']);
                        $nome = utf8_encode($valor['NOME']);?>
                            <tr>
                                <td class="text-left" style="width:30%;"><?php echo $usuario.' - '.$nome.' / '.$departamento;?></td>
                                <td class="text-left" style="width:10%;"><?php echo $data;?></td>
                                <td class="text-left" style="width:10%;"><?php echo $nota;?></td>
                                <td style="width:40%;"><?php echo $comentario;?></td>
                                
                            </tr>
                        <?php 
                        } 
                    
                    }else{?>
                        <table class="table table-striped"> 
                            <tr>
                                <td>Nenhuma avaliação encontrada.</td>
                            </tr>
                <?php }?>
                
            </table>
            <?php	
                    
            } //try
            catch (PDOException $e)
            {
                // caso ocorra uma exceção, exibe na tela
                echo "Erro!: " . $e->getMessage() . "<br>";
                die();
            }?>      
        </div> 
    </div> 
</div>
    
	