<?php
class DataAccess{
    private $connection;
	
    function connect(){
        $bd= "gpsipt_diariofct";
        $user = "gpsipt_admin";
        $pwd = "T1VEF]2%Wq6H";
        $server = "localhost";
		$this->connection = mysql_connect($server,$user,$pwd,$bd);

		// Check connection
        if($this->connection<0 || mysql_select_db($bd, $this->connection) == false)
		{
			die('Could not connect: '.mysql_error());
		}else{
            mysql_query("set names 'utf8'");
            mysql_query("set character_set_connection=utf8");
            mysql_query("set character_set_client=utf8");
            mysql_query("set character_set_results=utf8");
        }
    }
    
    function execute($query){
        $res = mysql_query($query);
        if(!$res){
            die("Comando inválido".mysql_error());
        }else
            return $res;
    }
    
    function disconnect(){
        mysql_close($this->connection);
    }
	
	function getTiposUtilizadores(){
		$query = "select * from TiposDeUtilizadores order by id desc";
		$this->connect();
		$res = $this->execute($query);
		$this->disconnect();
		return $res;
	}
     
	function inserirUtilizador($nome, $email, $pwd, $idTipo, $turma){
		$query = "insert into Utilizadores(nome, email, password, idTipoUtilizador, idTurma) values('$nome', '$email','$pwd',$idTipo, $turma)";
		$this->connect();
        $this->execute($query);
        $this->disconnect();
	}
	
	function updatePwdUtilizador($email, $pwd){
		$query = "select * from Utilizadores where email = '$email'";
		$this->connect();
		$res = $this->execute($query);
		if (mysql_num_rows($res)>0){
			$query = "update Utilizadores set password = '$pwd' where email = '$email'";
			$this->execute($query);
			$query = "select nome from Utilizadores where email = '$email'";
			$res = $this->execute($query);
			$row = mysql_fetch_object($res);
			$res = $row->nome;
		}else{
			$res = -1;
		}
		$this->disconnect();
		return $res;
	}
	
	function login($email, $password){
		$query = "select * from Utilizadores where email ='$email' and password = '$password'";
		$this->connect();
		$res = $this->execute($query);
		$this->disconnect();
		return $res;
	}
		
	function inserirRegistoDiario($diario,$idUser){
		$diario = str_replace("'", "´", $diario);
		$diario = str_replace('"', "´", $diario);
		$query = "insert into diario(diario, idUtilizador) values ('$diario',$idUser)";
		$this->connect();
		$this->execute($query);
		$res = mysql_insert_id();
		$this->disconnect();	
		return $res;
	}
	
	function inserirFicheiro($ficheiro, $idDiario){
		$query = "insert into ficheiros(ficheiro, idDiario) values ('$ficheiro', $idDiario)";
		$this->connect();
		$this->execute($query);
		$this->disconnect();		
	}
	
	function getRegistos($idUser){
		$query = "select U.nome, D.* from diario D inner join Utilizadores U 
						where U.id = D.idUtilizador and U.idTipoUtilizador = 2 and U.id = $idUser order by D.dia desc";
		
		$this->connect();
		$res = $this->execute($query);
		$this->disconnect();
		return $res;
	}
	
	function getAlunos($idUser){
		$query = "select idTurma from Utilizadores where id = $idUser";
		
		$this->connect();
		$res = $this->execute($query);
		$row = mysql_fetch_object($res);
		$idTurma = $row->idTurma;
		
		$query = "select * from Utilizadores where idTurma = $idTurma and idTipoUtilizador = 2 order by nome asc";
		
		$res = $this->execute($query);
		$this->disconnect();
		return $res;
	}
	
	function getNomeUser($id){
		$query = "select nome from Utilizadores where id = $id";
		$this->connect();
		$res = $this->execute($query);
		$row = mysql_fetch_object($res);
		$this->disconnect();
		return $row->nome;
	}
	
	function getNextIDAluno($idAluno){
		$query = "select idTurma from Utilizadores where id = $idAluno";
		
		$this->connect();
		$res = $this->execute($query);
		$row = mysql_fetch_object($res);
		$idTurma = $row->idTurma;
		
		$query = "select * from Utilizadores where idTurma = $idTurma and idTipoUtilizador = 2 order by nome asc";
		//echo $query;
		$res = $this->execute($query);
		$primeiro = -1;
		while($row = mysql_fetch_object($res)){
			if ($primeiro == -1)
				$primeiro = $row->id;
				
			if ($row->id == $idAluno){
				$row = mysql_fetch_object($res);
				if ($row->id == "") break;
				$this->disconnect();
				return $row->id;
			}
		}
		$this->disconnect();
		return $primeiro;
	}
	
	function getPreviousIDAluno($idAluno){
		$query = "select idTurma from Utilizadores where id = $idAluno";
		
		$this->connect();
		$res = $this->execute($query);
		$row = mysql_fetch_object($res);
		$idTurma = $row->idTurma;
		
		$query = "select * from Utilizadores where idTurma = $idTurma and idTipoUtilizador = 2 order by nome asc";
		
		$res = $this->execute($query);
		$ultimo = -1;
		$previous = -1;
		$conta=0;
		$encontrou = -1;
		while($row = mysql_fetch_object($res)){
			$conta++;
			
			if ($row->id == $idAluno && $conta != 0){
				$encontrou = 1;
			}else{
				if ($encontrou == -1)
					$previous = $row->id;
			}
			/*echo "conta".$conta;
			echo "numrows".mysql_num_rows($res);
			echo "encontrou".$encontrou;
			echo "<br/>";*/
			if ($conta == mysql_num_rows($res) && $encontrou == -1)
				$previous = $row->id;
		}
		
		$this->disconnect();
		return $previous;
	}
	
	function getStats($idTurma){	
		$query = "select U.id, U.nome, count(D.idUtilizador) as conta from Utilizadores U inner join diario D 
					where U.id = D.idUtilizador and U.idTurma = $idTurma and U.idTipoUtilizador = 2 group by U.nome order by U.nome asc";
		$this->connect();
		$res = $this->execute($query);
		$this->disconnect();
		return $res;
	}
	
	function getFicheirosDiario($idDiario){
		$query = "select * from ficheiros where idDiario = $idDiario";
		$this->connect();
		$res = $this->execute($query);
		$this->disconnect();
		return $res;
	}
	
	function getUltimos10Registos(){
		$query = "select U.nome, D.* from diario D inner join Utilizadores U where U.id = D.idUtilizador and U.idTipoUtilizador = 2 order by dia desc limit 10";
		$this->connect();
		$res = $this->execute($query);
		$this->disconnect();
		return $res;
	}
	
	function PesquisarRegistos($aluno, $curso){
		if ($aluno != "")
			$query = "select U.nome, D.* from diario D inner join Utilizadores U where U.id = D.idUtilizador and U.idTipoUtilizador = 2 and 
						U.nome like '%$aluno%' and U.idTurma = $curso order by U.nome asc, dia desc";
		else{
			$query = "select U.nome, D.* from diario D inner join Utilizadores U where U.id = D.idUtilizador and U.idTipoUtilizador = 2 and 
						U.idTurma = $curso order by U.nome asc, dia desc";
		}
		$this->connect();
		$res = $this->execute($query);
		$this->disconnect();
		return $res;
	}
	
	function updateRegistoDiario($idDiario, $texto){
		$texto = str_replace("'", "´", $texto);
		$texto = str_replace('"', "´", $texto);
		$query = "update diario set diario='$texto' where id = $idDiario";
		$this->connect();
		$this->execute($query);
		$this->disconnect();
	}
	
	function apagarFicheirosDeRegisto($id){
		$this->connect();
		$query = "select * from ficheiros where idDiario = $id";
		$res = $this->execute($query);
		
		while($row = mysql_fetch_object($res)){
			unlink("ficheiros/".$row->ficheiro);
		}
		
		$query = "delete from ficheiros where idDiario = $id";
		$this->execute($query);
		$this->disconnect();
	}
	
	function eliminarRegisto($id){
		$this->apagarFicheirosDeRegisto($id);
		$query = "delete from diario where id = $id";
		$this->connect();
		$this->execute($query);
		$this->disconnect();		
	}
    
	function getUser($id){
        $query = "select * from Utilizadores where id = $id";
		$this->connect();
        $res = $this->execute($query);
		$this->disconnect();
		return $res;
    }
	
	function editarUtilizador($id, $nome, $email, $turma){
		$query = "update Utilizadores set nome='$nome', email='$email', idTurma=$turma
		where id = $id";
		
		$this->connect();
		$this->execute($query);
		$this->disconnect();
	}
	
	function editarAluno($id, $nome, $email, $password, $turma){
		$query = "update Utilizadores set nome='$nome', email='$email', password='$password', idTurma=$turma where id = $id";
		
		$this->connect();
		$this->execute($query);
		$this->disconnect();
	}
	
	function editarPassUtilizador($id, $pass, $pwdOld){
		$query = "select password from Utilizadores where id = $id";
		$this->connect();
		$res = $this->execute($query);
		$row = mysql_fetch_object($res);
		if ($row->password == $pwdOld){
			$query = "update Utilizadores set password='$pass' where id = $id";
			$this->execute($query);
			$res = 1;
		}else
			$res = -1;
		$this->disconnect();
		return $res;
	}
	
	
	function getTurmas(){
		$query = "select * from Turmas";
		$this->connect();
        $res = $this->execute($query);
		$this->disconnect();
		return $res;
	}

	function getUtilizadores(){
	
		$query = "select MAX(id) as m from Utilizadores";
	
		$this->connect();
        $res = $this->execute($query);
		$this->disconnect();
		return $res;
	
	}
}
?>