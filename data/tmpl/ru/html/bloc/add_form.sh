#!/bin/bash
touch 0_new.form.tmpl

echo '{{back}}
<form action="{{post}}" method="POST">
<p><input type="text" name="code_ua" value="{{code_ua}}" autofocus/>&nbsp;ID UA</p>
<input type="submit" name="doEdit1" value="Записать"/>
</form>' >> 0_new.form.tmpl
