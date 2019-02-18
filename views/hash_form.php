<form action="hash.php" method="post" password="confirmation">
    <fieldset>
        <div class="form-group">
            <input autocomplete="off" autofocus class="form-control" name="current" placeholder="Current Password" type="password"/>
        </div>
        <div class="form-group">
            <input class="form-control" name="new" placeholder="New Password" type="password"/>
        </div>
        <div class="form-group">
            <input class="form-control" name="confirm" placeholder="Confirm New Password" type="password"/>
        </div>
        <div class="form-group">
            <button class="btn btn-default" type="submit">
                <span aria-hidden="true" class="glyphicon glyphicon-log-in"></span>
                Change
            </button>
        </div>
    </fieldset>
</form>
