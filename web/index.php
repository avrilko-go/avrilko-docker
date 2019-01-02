<?php
for ($i=0; $i < 10; $i++) {
    $pid = pcntl_fork();
    if ($pid == -1) {
        exit('fork进程失败');
    } elseif ($pid) {
        $status = 0;
        pcntl_wait($status, 1);
    } else {
        $pid = getmypid();
        var_dump($pid);
        sleep(3);
        exit();
    }
}