<form action="history.php" method="post">
    <fieldset>
        <div class="form-group">
            <table align="center">
                <tr>
                    <td align="left" width="22%">Date & Time:</td>
                    <td align="left" width="22%">Symbol:</td>
                    <td align="left" width="22%">No Of Shares:</td>
                    <td align="left" width="22%">Transaction:</td>
                    <td align="left" width="22%">Remaining Balance:</td>
                </tr>
                <?php foreach ($rows as $row): ?>
    		    <tr>
            		<td align="left" width="22%"><?= $row["date_time"] ?></td>
            		<td align="left" width="22%"><?= $row["symbol"] ?></td>
            		<td align="left" width="22%"><?= $row["no_shares"] ?></td>
            		<td align="left" width="22%"><?= $row["trans_type"] ?></td>
            		<td align="left" width="22%">$<?=number_format(($row["cur_bal"]), 2)?></td>
    		    </tr>
		<?php endforeach; ?>
            </table>
        </div>
        <div class="form-group">
            <button class="btn btn-default" type="submit" name="Submit1">
                <span aria-hidden="true" class="glyphicon glyphicon-log-in"></span>
                Clear history
            </button>
        </div>
    </fieldset>
</form>