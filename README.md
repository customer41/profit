# Minimal web-application based on T4 framework

Use 
<code>composer create-project pr-of-it/t4-app-mini --stability="dev"</code>
to install this application

Триггер на установку значений __user_extra:

<code>
DELIMITER $$ <br>
CREATE TRIGGER `set_values` <br>
BEFORE UPDATE ON `__users_extra` <br>
FOR EACH ROW <br>
BEGIN <br>
<pre>SET
        NEW.`debt` = NEW.`borrowed` - NEW.`loaned`,
        NEW.`balance` = NEW.`startSum` + NEW.`profit` - NEW.`costs` + NEW.`debt`;
END;
</pre>
$$ <br>
DELIMITER ;
</code>