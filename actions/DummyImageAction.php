<?php

/**
 * Created by PhpStorm.
 * User: Zivorad Antonijevic
 * Date: 30.12.14.
 * Time: 10.15
 */
class DummyImageAction extends CAction
{
    public function run()
    {
        /** @var EDummyImage $dummy */
        $dummy = Yii::createComponent(
            array(
                'class' => 'ext.dummyImage.EDummyImage'
            )
        );
        $dummy->image();
    }
} 