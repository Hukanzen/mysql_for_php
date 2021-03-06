<?php
/* 
    サンプルは動いた 2017/09/22 6:04
            */

class mysqli_connection
{
	/* mysqli 関連の自作関数 */
	/* リンクid */
	private $pri_lid;

	/* コンストラクタ．全て埋まる指定があれば，DBと接続する */
	public function __construct($server,$user,$pass,$db_name){
		if(isset($server) and isset($user) and isset($pass) and isset($db_name)){
			$this->db_connect($server,$user,$pass,$db_name);	
		}
	}

	/* DBと接続 */
	public function db_connect($server,$user,$pass,$db_name){
		$linkid=mysqli_connect($server,$user,$pass,$db_name);
		if(!$linkid) die("Failure mysqli_connect".mysqli_error($linkid));
		/* 文字コードの指定 */
		mysqli_set_charset($linkid,"utf8");
		//return $linkid;
		$this->pri_lid=$linkid;
	}

	/* エスケープ */
	public function escape_string($SQL){
		$linkid=$this->pri_lid;
		$SQL=mysqli_real_escape_string($linkid,$SQL);
		return $SQL;
	}

	/* クエリの作成 */
	public function db_query($SQL){
		$linkid=$this->pri_lid;
		$rslt=mysqli_query($linkid,$SQL);
		if(!$rslt) die("$SQL is Failure".mysqli_error($linkid));
		return $rslt;
	}

	/* SQL文で取得できるデータの個数を返す．*/
	public function db_num_rows($SQL){
		$rslt=$this->db_query($SQL);
		$num=mysqli_num_rows($rslt);
		return $num;
	}

	/* SQL文を投げて，データを取得し，配列で返す */
	public function db_fetch($SQL){
		$data=array();
		$rslt=$this->db_query($SQL);
		while($fet=mysqli_fetch_assoc($rslt)){
			array_push($data,$fet);
		}
		return $data;
	}

	/* 配列のSQL文を投げて，クエリを取得し，配列で返す */
	public function db_a_query($aSQL){
		$aRSLT=array();
		foreach($aSQL as $sql){
			$rslt=$this->db_query($sql);
			/* db_queryで行う */
			/*
			if(!$rslt){
				die("$sql is Failure.".mysqli_error($linkid));
				break;
			}
			*/
			array_push($aRSLT,$rslt);
		}
		return $aRSLT;
	}

	/* 配列のSQL文を投げて，データを取得し，配列で返す*/
	public function db_a_fetch_assoc($aSQL){
		$aRSLT=$this->db_a_query($aSQL);
		$aFET=array();
		$i=0;
		foreach($aRSLT as $rslt){
			array_push($aFET,array());
			while($fet=mysqli_fetch_assoc($rslt)){
				array_push($aFET[$i],$fet);
			}
			$i++;
		}
		return $aFET;
	}

	/* DBとの接続を切断する */
	public function db_close(){
		mysqli_close($this->pri_lid);
	}

	public function __destruct(){
		$this->db_close();
	}
}
?>

