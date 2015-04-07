<h3>Manage Users</h3>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'itemsCssClass' => 'table table-striped',
	'enableSorting' => true,
    'dataProvider'=>$model->search(),
    'columns' => array(
    	'id',
        'email',
        'name',
    	array(
            'class'=>'CButtonColumn',
            'template' => '{view}{update}{delete}',
            'viewButtonOptions' => array(
                'class' => 'fa fa-search'
            ),
            'viewButtonLabel' => false,
            'viewButtonImageUrl' => false,
            'viewButtonUrl' => 'Yii::app()->createUrl("user/view", array("id" => "$data->id"))',
            'updateButtonOptions' => array(
            	'class' => 'fa fa-pencil'
            ),
            'updateButtonLabel' => false,
            'updateButtonImageUrl' => false,
            'updateButtonUrl' => 'Yii::app()->createUrl("user/save", array("id" => "$data->id"))',

            'deleteButtonOptions' => array(
                'class' => 'fa fa-trash-o'
            ),
            'deleteButtonLabel' => false,
            'deleteButtonImageUrl' => false,
            'deleteButtonUrl' => 'Yii::app()->createUrl("user/delete", array("id" => "$data->id"))'
        ),
    )
));