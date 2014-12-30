<?php

/**
 * Created by PhpStorm.
 * User: Zivorad Antonijevic
 * Date: 30.12.14.
 * Time: 10.15
 */
class DummyImageAction extends CAction
{
    public $defaultType = 'png';
    public $defaultBackground = 'ffffff';
    public $defaultColor = '000000';
    public $defaultText = null;

    public function run()
    {
        /** @var EDummyImage $dummy */
        $dummy = Yii::createComponent(
            array(
                'class'             => 'ext.dummyImage.EDummyImage',
                'defaultType'       => $this->defaultType,
                'defaultText'       => $this->defaultText,
                'defaultBackground' => $this->defaultBackground,
                'defaultColor'      => $this->defaultColor
            )
        );
        $dummy->image();
    }
} 