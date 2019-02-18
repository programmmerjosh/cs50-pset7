<form action="index.php" method="get">
	<div>
	<table  align="center" style="text-align:left; font-size:120%" border="3px blue" width="20%">
		<tr>
			<td style="font-weight: bold"> User: </td>
			<td><?=$username?></td>
		</tr>
		<tr>
			<td style="font-weight: bold">Available Funds: </td>
			<td>$<?=number_format($cash, 2)?></td>
		</tr>
	</table>
	<br/>
	<?php  if ($temp == false) { ?>
	<table align="center" border="3px blue" width="60%" style="font-size:120%">
		<tr style="font-weight: bold">
			<td>Share Name:</td>
			<td>Share Symbol:</td>
			<td>Share Current Price:</td>
			<td>No Of These Shares You Own:</td>
		</tr>
		<?php foreach ($positions as $position): ?>
    		<tr>
        		<td><?= $position["name"] ?></td>
        		<td><?= $position["symbol"] ?></td>
        		<td>$<?=number_format(($position["price"]), 2)?></td>
        		<td><?= $position["shares"] ?></td>
    		</tr>
		<?php endforeach; } ?>
	</table>
	<br/>
</div>


