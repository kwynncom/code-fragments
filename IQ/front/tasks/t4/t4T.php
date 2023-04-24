<script>
	var KWIQT4A = '<?php echo($this->quaps->correctAnswer . ''); ?>';
</script>
<?php $this->dbidjs(); ?>

<style>
	.e4p10 { width: 40vw;	 }
	.e4p20 { 
		width:  11vw;
		height: 2.5em; 
		font-size: 100%;
	}

	
</style>
</head>
<body class='e34'>
	
<div>
	<div class='thomasOuterColor' style='top: 0; margin: 2em auto 0 auto; width: 40vw; height: 10em; position: relative; '>
		<div class='e34p10 e4p10' >
			<?php for ($i=0; $i < count($this->quaps->q); $i++) { 
				$num = $this->quaps->q[$i];
				$t = '';
				$t .= '<div class="e34p20 e4p20" onclick="t4inter.onclick(this);" data-iamn="' . $num . '" >';
				$t .= '<div class="e34p30" >';
				$t .= $num;
				$t .= '</div>';
				$t .= '</div>';				
				echo($t);
			} ?>
			
		</div>
	</div>
</div>
	
	<?php menuSide(); ?>
</body>
</html>
