<?php
include_once('database_setup.php');
include_once('queries.php');

$message = ''; $alr_exi = ''; $skipstep = 'style="display:none;'; $busy = ''; $busy2 = ''; $start = 'Start';
if(file_exists('../db.inc.php')) {
    $alr_exi = '<span class="red">The file you are going to create already exists. Do you want to create a new file or use the existing one?</span>';
    $start = 'Create new file';
    $skipstep = '';
}

if(isset($_POST['create'])) {
    if(!empty($_POST['database']) && !empty($_POST['host']) && !empty($_POST['username']) && !empty($_POST['password'])) {
    #echo $_POST['writethis'];
        $file = '../db.inc.php';
        $fc = fopen($file,'w') or die('somethine went wrong');
        $putin  = "<?php\r\n";
        $putin .= '$database = \'' . $_POST['database'] . "'; \r\n";
        $putin .= '$host = \'' . $_POST['host'] . "'; \r\n";
        $putin .= '$user = \'' . $_POST['username'] . "'; \r\n";
        $putin .= '$password = \'' . $_POST['password'] . "'; \r\n";
        $putin .= '$link = mysqli_connect($host, $user, $password); $db_selected = mysqli_select_db($link,$database);';
        $putin .= "?>";
        $write = fwrite($fc,$putin);
        fclose($fc); 

        if($write === false) {
        $message .=  '<span class="red">Something went wrong. Please check if you have writing rights on the server or contact a system administrator.</span>'; }
        else {
            $alr_exi = '';
            $skipstep = 'style="display:none;';
            $busy = 'style="display:none;'; $busy2 = 'disabled';
            include_once('../db.inc.php');
            if($link && $db_selected) {
            $message .= create_tables($link,$queries);
            }
            else {
                $message .= '<span class="red">Something went wrong. No worries. Just try again.</span>';
                    if(!$link) { $message .= '<br /><span class="red">You probably entered the wrong credentials.</span>';}
                    elseif(!$db_selected) {$message .= '<br /><span class="red">That database doesn\'t seem to exist.</span></p>';}
                $busy = ''; $busy2 = '';
            }        
        }
    }
    else {
    $message .= 'Please fill out all required fields';
    }
}
if(isset($_POST['skipped'])) {
    $alr_exi = '';
            $skipstep = 'style="display:none;';
            $busy = 'style="display:none;'; $busy2 = 'disabled';
            include_once('../db.inc.php');
            if($link && $db_selected) {
            $message .= 'creating tables...';                       
            $message .= create_tables($link,$queries);

            }
            else {
                $message .= '<span class="red">Something went wrong. No worries. Just try again.</span>';
                    if(!$link) { $message .= '<br /><span class="red">You probably entered the wrong credentials.</span>';}
                    elseif(!$db_selected) {$message .= '<br /><span class="red">That database doesn\'t seem to exist.</span></p>';}
                $busy = ''; $busy2 = '';
            }
}
?>
<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>setup rezervejo</title>
        <link type="text/css" href="../style.css" rel="stylesheet" />
    </head>
    <body> 
        <p>
            Welcome. In 3 simple steps you will install rezervejo to your server.<br />
            First, let make your database ready. It is important that you have already created the database you want to use for rezervejo.<br />
            Then, just enter in all the credentials you should have received.
        </p>
        <form method="post">
        <h3>Enter database credentials:</h3>
            <table>
                <tr><td><span class="required">*</span> Database:</td><td><input name="database" <?= $busy2; ?>></td></tr>
                <tr><td><span class="required">*</span> Host:</td><td><input  name="host" <?= $busy2; ?>></td></tr>
                <tr><td><span class="required">*</span> User:</td><td><input  name="username" <?= $busy2; ?>></td></tr>
                <tr><td><span class="required">*</span> Password:</td><td><input  type="password" name="password" <?= $busy2; ?>></td></tr>
                <tr><td><input type="submit" name="create" <?= $busy; ?> value="<?= $start; ?>"></td><td><input name="skipped" type="submit" <?= $skipstep; ?> value="Use existing file"></td></tr>
            </table>        
        <?= $alr_exi; ?>
        </form>
        <?php
            echo $message;
        ?>
    </body>
</html>

