<?php 
$str = <<<EOT
    $("#$idName").select2({
        tags: $targetArray
    });       
EOT;

$ranNum = rand(1, 100000);
$this->registerJs($str, \yii\web\View::POS_READY, 'tag-cloud'.$ranNum);