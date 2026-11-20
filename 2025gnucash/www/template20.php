<div> <!-- min pay conf outer -->
<?php 
    require_once(__DIR__ . '/payconf/payconf.php');
?>
</div> <!-- min pay conf outer END -->

<div> <!-- balance / top-level info -->
<div><?php if (false) echo($this->svg); ?></div> <!-- payment confirmation checkmark -->
<table>
    <tr><td>$<?php echo(number_format($this->calcs['avCr'], 2)); ?></td> <td>available credit</td>    </tr>
    <tr><td>$<?php echo(number_format($this->calcs['minPaymentEst'])); ?></td><td>min payment (ca.)</td></tr>
    <tr><td>$<?php echo(number_format($this->calcs['payments'])); ?></td> <td>payments</td>    </tr>
</table>
</div> <!-- balance / top-level info END -->
