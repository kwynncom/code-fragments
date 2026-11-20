<script> <!-- minimum payment confirmation inner -->
    class pcDoCl {
	static pc10() {
	     byid('pcbtn'  ).style.display = 'none';
	     byid('pc20Div').style.display = 'block';
	}
    }
</script>

<div id='pc20Div' style='display: none;'>
    <p>
	Was <span class='numCSCl'>$<?php echo(number_format($this->calcs['payments'], 2)); ?></span> the exact minimum payment?
	<button>Yes</button>
    </p>
</div>
<button id='pcbtn' onclick='pcDoCl.pc10();'>confirm minimum paid</button> <!-- minimum payment confirmation inner END -->
