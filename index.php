<?php
define('INCLUDE_CHECK',true);
header('Content-Type: text/html;charset=UTF-8');
include "./incl/init.php";
?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="<?=$config['domain']?>/css/style.css">
		<link rel="stylesheet" type="text/css" href="<?=$config['domain']?>/css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="<?=$config['domain']?>/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="<?=$config['domain']?>/css/bootstrap-responsive.css">
		<script type="text/javascript" src="<?=$config['domain'] ?>/js/jquery-1.10.2.js"></script>
		<script type="text/javascript" src="<?=$config['domain'] ?>/js/jquery-ui-1.10.4.custom.js"></script>
		<script type="text/javascript" src="<?=$config['domain'] ?>/js/libs.js"></script>
		<title><?=$config['title']?></title>
	</head>
	<body><br />
		<center><h3>Покупка услуг</h3>
		<form method="POST" action="" class="form-pay">
			<div><input type="text" name="nikname" placeholder="Ник на сервере" class="form-control" /></div><br />
			<?php if ( count($config['servers']) > 1 ) : ?>
				<select name="servers" id="sel-servers" class="form-control">
				<?php foreach ( $config['servers'] as $key => $val ) : ?>
					<option value="<?=$key?>"><?=$val['name']?></option>
				<?php endforeach; ?>
				</select>
				<br />
				<?php foreach ( $config['servers'] as $key => $nope ) : ?>
					<div id="view-server" class="class-<?=$key?>" style="display: none">
						<select name="server" class="form-control">
							<?php foreach ( $nope['groups'] as $val ) : ?>
							<option value="<?=$val?>">[<?=$config['groups'][$val]['name']?>] <?=$config['groups'][$val]['price']?> Р. \ Сроком: <?php echo $config['groups'][$val]['time'] == 0 ? "Вечно" : $config['groups'][$val]['time'] ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				<?php endforeach; ?>
			<?php else : ?>
				<select name="group" class="form-control">
					<?php foreach ( $config['groups'] as $val => $key ) : ?>
					<option value="<?=$val?>">[<?=$key['name']?>] <?=$key['price']?> Р. \ Сроком: <?php echo $key['time'] == 0 ? "Вечно" : $key['time'] ?></option>
					<?php endforeach; ?>
				</select>
			<?php endif; ?><br />
			<div><input type="submit" class="btn btn-success" name="buy" value="Купить" /></div>
		</form></center>
		
		<center style="margin-top: 100px;">
			<?php foreach ( $monitoring as $key => $val ) : ?>
				<?php if ( $val['status'] ) : ?>
				<div style="width: 400px; text-align: center;">IP: <?php echo ! empty ( $val['server']['port'] ) ? $val['server']['host'].":".$val['server']['port'] : $val['server']['host'] ?></div>
				<div style="width: 400px">Кол-во: <?=$val['numpl']?>, слотов: <?=$val['maxpl']?>, рекрод - <?=$val['record']?></div>
				<div class="progress" style="width: 400px">
					<div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="<?=($val['numpl']/$val['maxpl'])*100?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=($val['numpl']/$val['maxpl'])*100?>%">
						<span class="sr-only"></span>
					</div>
				</div>
				<?php else: ?>
				<div style="width: 400px; text-align: center;">Сервер Offline</div>
				<div class="progress" style="width: 400px">
					<div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
						<span class="sr-only"></span>
					</div>
				</div>
				<?php endif; ?>
			<?php endforeach; ?>
		</center>
	</body>
</html>