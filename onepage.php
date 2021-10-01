<?php
if ( ! defined ( "INCLUDE_CHECK" ) ) {
	die ( "access error" );
}

class onepage {
	var $config;
	var $mysql;
	function __construct ($config, $mysql) {
		$this->config = $config;
		$this->mysql = $mysql;
		$query = $mysql->query("DELETE FROM `transication` WHERE `time` < '".(time() - 86400)."'");
	}
	
	function monitoring () {
		$list = array ();
		$record = array ();
		if ( file_exists($this->config['record_file']) ) {
			$file = file_get_contents($this->config['record_file']);
			$record = json_decode($file,true);
		}
		
		foreach ( $this->config['servers'] as $key => $val ) {
			$server = $this->mcraftQuery_SE($val['host'], $val['port']);
			$list [] = array (
				'numpl' => $server['numpl'],
				'maxpl' => $server['maxpl'],
				'status' => $server['maxpl'] > 0 ? true : false,
				'server' => $val,
				'record' => ! empty ( $record[$key] ) ? $record[$key] : $server['numpl']
			);
			
			if ( ! empty ( $record[$key] ) ) {
				if ( $server['numpl'] > $record[$key] ) {
					$record[$key] = $server['numpl'];
				}
			} else $record[$key] = $server['numpl'];
			
			$text = json_encode ($record);
			if ( $text != $file ) {
				file_put_contents($this->config['record_file'], $text);
			}
		}
		return $list;
	}
	
	function mcraftQuery_SE( $host, $port = 25565, $timeout = 1 ) {

		$fp = @fsockopen($host, $port, $errno, $errstr, $timeout);
		if(!$fp) return false;

		stream_set_timeout($fp, $timeout);
		
		fwrite($fp, "\xFE\x01");
		$data = fread($fp, 1024);
		fclose($fp);

		if(!$data or substr($data, 0, 1) != "\xFF") return false;
			
		$data = substr( $data, 3 );
		$data = iconv( 'UTF-16BE', 'UTF-8', $data );

		// ver 1.4 >
		if( $data[1] === "\xA7" && $data[2] === "\x31" ) {
				
			$data = explode( "\x00", $data );

			return Array(
				'hostname'   => $data[3 ],
				'numpl'    => (int)$data[4],
				'maxpl' => (int)$data[5],
				'protocol'   => (int)$data[1],
				'version'    => $data[2]
			);
		}
		
		$data = explode("\xA7", $data );
		return Array(
			'hostname'   => substr( $data[0], 0, -1 ),
			'numpl'      => isset( $data[1] ) ? (int)$data[1] : 0,
			'maxpl' => isset( $data[2] ) ? (int)$data[2] : 0
		);
	}
	
	function pay ( $id, $price, $check ) {
		$id = (int)$id;
		$price = (int)$price;
		$return = false;
		if ( $id > 0 && $price > 0 ) {
			$query = $this->mysql->query("SELECT * FROM `transication` WHERE `id` = '{$id}' AND `status` = '0'");
			$row = $this->mysql->get_row($query);
			if ( $row ) {
				$gid = $this->config['groups'][$row['gid']];
				if ( ! empty ( $gid['name'] ) ) {
					if ( $gid['price'] <= $price ) {
						if ( $row['server'] != '0' ) {
							$gdb = $this->config['servers'][$row['server']]['cart'];
						} else {
							$gdb = $this->config['servers'][0]['cart'];
						}
						if ( $check ) {
							$this->mysql->query("UPDATE `transication` SET `status` = '1' WHERE `id` = '{$row['id']}'");
							$group = $gid['permname'];
							if ( $gid['time'] > 0 ) $group .= "?lifetime=".($gid['time'] * 24 * 60 * 60);
							$this->mysql->query("INSERT INTO `{$gdb['table']}` ( `{$gdb['player']}`, `{$gdb['type']}`, `{$gdb['item']}`, `{$gdb['amount']}` ) VALUES ( '{$row['name']}', 'permgroup', '{$group}', '1')");
						}
						$return = true;
					}
				}
			}
		}
		
		return $return;
	}
	
	public function up_json_reply($type = "error", $params) {
	
		if ($type == "check" || $type == "pay") $type = "success";
		$reply = array(
			'error' => array(
				"jsonrpc" => "2.0",
				"error" => array("code" => -32000, "message" => $this->config['message']['fail']),
				'id' => $params['projectId']
			),
			'success' => array(
				"jsonrpc" => "2.0",
				"result" => array("message" => $this->config['message']['success']),
				'id' => $params['projectId']
			)
		);
		return json_encode($reply[$type]);
    }
	
	function send ( $nickname, $server, $gid ) {
		$nickname = $this->mysql->safesql(trim ( strip_tags ( $nickname ) ));
		$server = $this->mysql->safesql((int)$server);
		$gid = $this->mysql->safesql((int)$gid);
		$send = false;
		$grow = $this->config['groups'][$gid];
		if ( count ($this->config['servers']) > 1 ) {
			$srv = $this->config['servers'][$server];
			if ( ! empty ( $srv['host'] ) ) {
				if ( ! empty ( $grow['name'] ) && in_array($gid,$srv['groups']) ) {
					if ( preg_match ( "/^[a-zA-Z0-9_-]{3,11}$/", $nickname ) ) {
						$send = true;
					}
				}
			}
		} else {
			$server = 0;
			if ( ! empty ( $grow['name'] ) ) {
				if ( preg_match ( "/^[a-zA-Z0-9_-]{3,11}$/", $nickname ) ) {
					$send = true;
				}
			}
		}
		if ( $send ) {
			$desc = "Покупка привелегии {$grow['name']} сроком ".($grow['time'] == 0 ? "вечно" : $grow['time']."дн.") . ( count($this->config['servers']) > 1 ? " на сервере ".$srv['name'] : null );
			$this->mysql->query("INSERT INTO `transication` ( `name`,`gid`, `status`, `time`, `server` ) VALUES ( '{$nickname}', '{$gid}', '".time()."', '{$server}' )");
			header("Location:https://unitpay.ru/pay/{$this->config['unitpay']['project_id']}/mc?sum={$grow['price']}&account={$this->mysql->insert_id()}&desc={$desc}");
		}
	}
	
	public function up_sign($reply, $check = false) {
		ksort($reply);
		$exp = explode("-", $this->config['unitpay']['project_id']);
		$Sign = $reply['sign'];
		unset($reply['sign']);
		$reply['projectId'] = $exp[0];
		$return = (md5(join(null, $reply).$this->config['unitpay']['key']) != $Sign) ? "error" : "success";
		if ( $return == "success" ) {
			$return = $this->pay($reply['account'], $reply['sum'],$check) ? "success" : "error";
		}
		return $this->up_json_reply($return, $reply);
	}
	
}