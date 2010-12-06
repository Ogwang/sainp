<?php
	$cmdFile = "C:\\xampplite\\htdocs\\SAINP\\tests\\octave\\1output.m";
	$fo = fopen($cmdFile, 'r');
	$cmdScript = fread($fo, filesize($cmdFile));
	fclose($fo);
?>



<!-- INÍCIO DO INPUT PARA O WEBOCTAVE//-->
	<form action="http://hara.mimuw.edu.pl/weboctave/web/index.php#menus" method="post"> 
		<textarea name="commands" rows="6" cols="72">
			<?php echo $cmdScript; ?>
		</textarea> 
		<input type="submit" value="Submit to Octave"> 
		<script type="text/javascript"> 
			<!--
			document.write("<input type=\"button\" value=\"Clear\" onclick=\"this.form.elements['commands'].value=''\">");
			//-->
		</script> 
	</form>
<!-- FINAL DO INPUT PARA O WEBOCTAVE//-->