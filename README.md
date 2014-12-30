yii-dummy-image
===============

YII  Dummy Image extension.

It pretends to be very simple. Just generates empty image with specified text and color attributes.

Accept those parameters (either $_GET, or $params if called directly)

        $params['color'] = '000000';
        $params['bg'] = 'ffffff';
        $params['text'] = 'Hello World';
        $params['size'] = '200x200' ;  or $params['size'] = '200'; 
        $params['type'] = 'png';


How to use:

    /** Using as action **/
    public function actions()
        {
            return array(
                'nophoto' => array('class' => 'ext.dummyImage.actions.DummyImageAction', 
                             'defaultText' => 'No Photo', 
                             'defaultBackground'=>'eeeeee',
                             'defaultType'=>'png',
                             'defaultColor'=>'000000'
                             )
            );
        }



    /** Using with inline component **/
    $dummy = Yii::createComponent(
              array(
                  'class' => 'ext.dummyImage.EDummyImage',
                  'defaultText' => 'No Photo', 
                  'defaultBackground'=>'eeeeee',
                  'defaultType'=>'png',
                  'defaultColor'=>'000000'
              )
          );
      $dummy->image();

        
      /** Using as normal component **/
      ...
      'components'=>array(
      ...
      'dummy'=>array(
                    'class' => 'ext.dummyImage.EDummyImage',
                    'defaultText' => 'No Photo', 
                    'defaultBackground'=>'eeeeee'
                )
      ...
      )
      Yii::app()->dummy()->image()
        
