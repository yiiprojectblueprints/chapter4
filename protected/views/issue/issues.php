<?php $this->widget('zii.widgets.grid.CGridView', array(
	'itemsCssClass' => 'table table-striped',
	'enableSorting' => true,
    'dataProvider'=>$model->search(),
    'columns' => array(
    	'id',
    	'customer_id' => array(
    		'name' => 'Customer',
    		'value' => '$data->customer->name'
    	),
    	'title',
        'status_id' => array(
            'name' => 'Status',
            'value' => '$data->status->name'
        ),
    	'updated' => array(
    		'name' => 'Last Updated',
    		'value' => 'date("F m, Y @ H:i", $data->updated) . " UTC"'
    	),
    	array(
            'class'=>'CButtonColumn',
            'template' => '{update}',
            'updateButtonOptions' => array(
            	'class' => 'fa fa-pencil'
            ),
            'updateButtonLabel' => false,
            'updateButtonImageUrl' => false
        ),
    )
));