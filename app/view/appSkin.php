<?php
class appSkin {
	
	function loadRedirect($text="", $url="", $css="") {
		global $config;
		$BWHTML .= <<<EOF
			<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/html40/loose.dtd">
			<html>
				<head>
					<title>Redirecting...</title>
					<meta http-equiv='refresh' content='2; url={$url}' />
					<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
					{$css[0]} {$css[1]}
				</head>
			  	<body >
					<center>
						<p class="text">{$text}</p>
						<a href='$url' title="{$url}" class="title">(Click here if you do not want to wait)</a>
					</center>
				</body>
			</html>
EOF;
		return $BWHTML;
	}

	function accessDenied($error = "") {
		$BWHTML .= <<<EOF
			<div class="global-error">{$error}</div>
EOF;
		return $BWHTML;
	}

	function displayFatalError($message = "", $line = "", $file = "", $trace = "") {
		$BWHTML .= <<<EOF
			<div class="global-error">
			Error: {$message}<br />
			Line: {$line}<br />
			File: {$file}<br />
			Trace: <pre>{$trace}</pre><br />
			</div>
EOF;
		return $BWHTML;
	}
	
	function displayQueryLogs($query = array()){
		$BWHTML .= <<<EOF
			<table class='cached-queries' width="95%" cellspacing="0" cellpadding="6" border="1" bgcolor="#FEFEFE" align="center" style='margin: 10px auto;'>
			<td bgcolor="#FFC5Cb" style="font-size:14px" colspan="2"><b>Query</b></td>
			<foreach=" $query as $key => $q ">
				<tr>
					<td width='50' align='center'>{$key}</td>
					<td>{$q['query']}</td>
				</tr>
			</foreach>
			</table>
EOF;
		return $BWHTML;
	}
	
	function displayQueryLogExplain($query = array()){
		$BWHTML .= <<<EOF
			<foreach=" $query as $key => $q ">
			<table width='95%' border='1' cellpadding='6' cellspacing='0' bgcolor='#FFE8F3' align='center'>
				<tr>
					<td colspan='8' style='font-size:14px' bgcolor='#FFC5Cb'><b>{$q['query_type']}</b></td>
				</tr>
				<tr>
					<td colspan='8' style='font-family:courier, monaco, arial;font-size:14px;color:black'>{$q['query']}</td>
				</tr>
				<if=" $q['query_type'] == 'SQL Query' ">
				<tr bgcolor='#FFC5Cb'>
					<td><b>table</b></td>
					<td><b>type</b></td>
					<td><b>possible_keys</b></td>
					<td><b>key</b></td>
					<td><b>key_len</b></td>
					<td><b>ref</b></td>
					<td><b>rows</b></td>
					<td><b>extra</b></td>
				</tr>
				
				<tr bgcolor='#FFFFFF'>
					<td>{$q['table']}&nbsp;</td>
					<td bgcolor='{$type_col}'>{$q['type']}&nbsp;</td>
					<td>{$q['possible_keys']}&nbsp;</td>
					<td>{$q['key']}&nbsp;</td>
					<td>{$q['key_len']}&nbsp;</td>
					<td>{$q['ref']}&nbsp;</td>
					<td>{$q['rows']}&nbsp;</td>
					<td>{$q['extra']}&nbsp;</td>
				</tr>
				</if>
				<tr>
					<td colspan='8' bgcolor='#FFD6DC' style='font-size:14px'><b>MySQL time</b>: </b></td>
				</tr>
				</table>
				<br />
		</foreach>
EOF;
		return $BWHTML;
	}
}