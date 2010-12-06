

<?php
	$cmdDir = "C:\\xampplite\\htdocs\\sandbox\\octave\\";
	$cmdFile = $cmdDir . "1output.m";
	$fo = fopen($cmdFile, 'r');
	$cmdScript = fread($fo, filesize($cmdFile));
	fclose($fo);
?>



<div id="teste">
<!-- INÍCIO DO INPUT PARA O WEBOCTAVE//-->
	<form name="octaveForm" action="http://hara.mimuw.edu.pl/weboctave/web/index.php#menus" method="post"> 
		<textarea name="commands" rows="6" cols="72">
			<?php echo $cmdScript; ?>
		</textarea> 
		<input type="submit" value="Submit to Octave"> 
		<script type="text/javascript"> 
			document.octaveForm.submit();
		</script> 
	</form>
<!-- FINAL DO INPUT PARA O WEBOCTAVE//-->
</div>


<!-- PARA FECHAR A JANELA 
<script type="text/javascript">window.close();</script>-->