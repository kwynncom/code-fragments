<div>

<!-- I did not create the SVG.  It comes from one of what Clark Howard would call a "giant, monster mega-bank" and should more properly be called 
evil in a profound, Satanic sense.   It's the check mark that means that one does not have a minimum payment due.  I fished it out of the bank's HTML.
Note that inspecting the HTML caused Firefox to crash, but I got what I wanted. -->
<div><?php if (false) echo($this->svg); ?></div>

<table>

    <tr><td>$<?php echo(number_format($this->calcs['avCr'], 2)); ?></td> <td>available credit</td>    </tr>

    <tr><td>$<?php echo(number_format($this->calcs['minPayment'])); ?></td><td>min payment (ca.)</td></tr>
    <tr><td>$<?php echo(number_format($this->calcs['payments'])); ?></td> <td>payments</td>    </tr>


</table>
</div>



