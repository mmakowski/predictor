{config_load file="test.conf" section="setup"}
{include file="header.tpl" title="Predictor -- logowanie"}

<center>
<table height="100%"><tr><td vAlign="middle" align="center">
<form method="post" action="login.php">
<table>
<tr><td>Login:</td><td><input type="text" name="name" value="{$name}"></td><td><font class="error">{$nameErr}</td></tr>
<tr><td>Has³o:</td><td><input type="password" name="pass"></td><td><font class="error">{$passwordErr}</td></tr>
</table>
<input type="submit" value="Zaloguj">
</form>
</center>
</table>

{include file="footer.tpl"}
