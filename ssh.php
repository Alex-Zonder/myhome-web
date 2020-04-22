<?php

echo 'Installing ssh';
//echo shell_exec('sudo apt-get install ssh -y');


echo 'Enable ssh';
//echo shell_exec('sudo service ssh enable 2>&1');
//echo shell_exec('sudo service ssh start 2>&1');

echo shell_exec('sudo systemctl enable ssh 2>&1');
echo shell_exec('sudo systemctl start ssh 2>&1');


echo '<hr>OK';

