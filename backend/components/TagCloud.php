<?php

namespace backend\components;

use yii\base\Widget;
use common\models\Content;
use app\TagsAlgo;

class TagCloud extends Widget {

    public $idName = 'tag_cloud';
    public $turnBack = 7;
    public function run() {
        //$targetArray = TagsAlgo::getTagResult();
        $tagObj = new TagsAlgo();
        $targetArray = $tagObj->getTagResult();
        echo $this->render('TagCloud', 
        [   
            'idName' => $this->idName,
            'targetArray'=>json_encode($targetArray),
        ]);
    }

}