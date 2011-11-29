<?php
$this->breadcrumbs=array(
	Yii::t('configuration', 'TitleConfiguration')=>array('configuration/admin'),
	Yii::t('companies', 'TitleCompanies')=>array('index'),
	$model->company_name,
);

$this->pageTitle = Yii::app()->name." - ".Yii::t('companies', 'TitleCompanies');
$cs = Yii::app()->getClientScript();
$cs->registerCssFile(Yii::app()->request->baseUrl.'/css/menu.css'); 
?>

<div class="portlet">
	<div class="portlet-content">
		<h1 class="ptitleinfo companies"><?php echo $model->company_name; ?></h1>
		<div class="button-group portlet-tab-nav">
			<?php echo CHtml::link(Yii::t('companies', 'ListCompanies'), Yii::app()->controller->createUrl('index'),array('class'=>'button')); ?>
			<?php echo CHtml::link(Yii::t('companies', 'CreateCompany'), Yii::app()->controller->createUrl('create'),array('class'=>'button')); ?>
			<?php echo CHtml::link(Yii::t('companies', 'UpdateCompany'), Yii::app()->controller->createUrl('update',array('id'=>$model->company_id)),array('class'=>'button')); ?>
		</div>
		<div class="subcolumns">
			<div class="c50l">
				<?php $this->widget('zii.widgets.CDetailView', array(
					'data'=>$model,
					'attributes'=>array(
						array(
							'label'=>'URL',
							'type'=>'raw',
							'value'=>CHtml::link(CHtml::encode($model->company_url),CHtml::encode($model->company_url)),
						),
						array(
							'name'=>'company_uniqueId',
							'type'=>'raw',
							'value'=> (isset($model->company_uniqueId)) ? CHtml::encode($model->company_uniqueId) : null,
						),
						array(
							'name'=>'Address.address_text',
							'type'=>'raw',
							'value'=> (isset($model->Address->address_text)) ? CHtml::encode($model->Address->address_text) : null,
						),
						array(
							'name'=>'Address.address_postalCode',
							'type'=>'raw',
							'value'=> (isset($model->Address->address_postalCode)) ? CHtml::encode($model->Address->address_postalCode) : null,
						),
						array(
							'name'=>'Address.address_phone',
							'type'=>'raw',
							'value'=> (isset($model->Address->address_phone)) ? CHtml::encode($model->Address->address_phone) : null,
						),
						array(
							'name'=>'Address.City.city_id',
							'type'=>'raw',
							'value'=> (isset($model->Address->city_id)) ? CHtml::encode($model->Address->City->city_name):  null,
						),
						array(
							'name'=>'Address.City.Country.country_id',
							'type'=>'raw',
							'value'=> (isset($model->Address->city_id)) ? CHtml::encode($model->Address->City->Country->country_name) : null,
						),
					),
				));?>
			</div>
			<div class="c50r">
				<div class="row" align="center" style="margin:auto;">
					<div id="map_canvas" style="width:300px; height:300px"></div>
				</div>
			</div>
		</div>
		<br />
		<div class="portlet">
			<div class="portlet-content">
				<h1 class="ptitle users"><?php echo Yii::t('companies', 'UsersInCompany'); ?></h1>
				<ul class="menuSelection" id="lnkaddusers">
				    <li class="menu_right corners">
						<div style="width:150px;<?php echo (count($UsersList)>0) ? "display:block" : "display:none"; ?>" class="tzSelect userlist">
							<div class="selectBox"><?php echo Yii::t('companies','AddUserToCompany'); ?></div>
							<div class="dropdown_1column align_right corners">
					            <div class="col_1" id="usersList">
					                <ul class="availablesUsers" source="0" link="<?php echo Yii::app()->createUrl('companies/addusers',array('action'=>'useradd','owner'=>$model->company_id)); ?>">
					                	<?php
					                		$this->renderPartial('_dropdownUsersList',array(
												'availableUsers'=>$UsersList
											));
					                	?>
					                </ul>
					            </div>
					        </div>
						</div>
				    </li>
				</ul>
				<?php $this->widget('zii.widgets.grid.CGridView', array(
					'id'=>'users-grid',
					'cssFile'=>'css/screen.css',
					'dataProvider'=>$Users,
					'summaryText'=>'',
					'afterAjaxUpdate'=>'js:function(){ $("#users-grid").removeClass("loading"); }',
					'beforeAjaxUpdate'=>'js:function(){ $("#users-grid").addClass("loading"); }',
					'columns'=>array(
						array(
							'name'=>'user_name',
							'type'=>'raw',
							'header'=>'Name',
							'value' =>'$data->CompleteName',
							'headerHtmlOptions'=>array(
								'style'=>'width:60%',
							),
						),
						array(
							'name'=>'user_email',
							'type'=>'raw',
							'header'=>'Email',
							'value' =>'$data->user_email',
							'headerHtmlOptions'=>array(
								'style'=>'width:30%',
							),
						),
						array(
							'class'=>'CButtonColumn',
							'template' => '{view} {deleting}',
							'buttons' => array(
								'view' => array(
									'label'=> 'Details',
									'url' =>'Yii::app()->controller->createUrl("users/view",array("id"=>$data->user_id))',
								),
								'deleting' => array(
									'label'=> 'Delete',
									'visible'=>'($data->user_admin) ? false : true',
									'url' =>'Yii::app()->controller->createUrl("companies/deleteuser",array("id"=>$data->user_id,"ajax"=>true))',
									'imageUrl'=>Yii::app()->request->baseUrl."/images/delete.png",
									'click' => 'js:function(e){					
	                                    e.preventDefault();
										$.ajax({
											type:"POST",
											data:{
												YII_CSRF_TOKEN:"'.Yii::app()->request->csrfToken.'",
												iclstyp: 0,
												cid:"'.$model->company_id.'",
											},
											dataType:"json",
											url:$(this).attr("href"),
											success:function(data) {
												$.fn.yiiGridView.update("users-grid");
												if (data.hasreg) $("#lnkaddusers").find(".tzSelect").show();
												$.ajax({
													type:"POST",
													data:{YII_CSRF_TOKEN:"'.Yii::app()->request->csrfToken.'", action:"useradd"},
													url:"'.Yii::app()->controller->createUrl("companies/addusers",array("owner"=>$model->company_id,"ajax"=>true)).'",
													success:function(html) {
														$("#lnkaddusers").find("ul").html(html);
													}
												});
											}
	                                    });
	                                }',
								),
							),
						),
					),
				)); ?>
			</div>			
		</div>
		<br />
		<div class="portlet">
			<div class="portlet-content">
				<h1 class="ptitle clients"><?php echo Yii::t('companies', 'ClientsInCompany'); ?></h1>
				<ul class="menuSelection" id="lnkaddclients">
				    <li class="menu_right corners">
						<div class="tzSelect clientlist" style="width:150px;<?php echo (count($ClientsList)>0) ? "display:block" : "display:none"; ?>">
							<div class="selectBox"><?php echo Yii::t('companies','AddClientToCompany'); ?></div>
							<div class="dropdown_1column align_right corners">
								<div class="col_1" id="clientsList">
									<ul class="availablesUsers" source="1" link="<?php echo Yii::app()->createUrl('companies/addusers',array('action'=>'clientadd','owner'=>$model->company_id)); ?>">
										<?php
					                		$this->renderPartial('_dropdownUsersList',array(
												'availableUsers'=>$ClientsList
											));
					                	?>
									</ul>
								</div>
							</div>
						</div>
				    </li>
				</ul>
				<?php $this->widget('zii.widgets.grid.CGridView', array(
					'id'=>'clients-grid',
					'cssFile'=>'css/screen.css',
					'dataProvider'=>$Clients,
					'afterAjaxUpdate'=>'js:function(){ $("#clients-grid").removeClass("loading"); }',
					'beforeAjaxUpdate'=>'js:function(){ $("#clients-grid").addClass("loading"); }',
					'summaryText'=>'',
					'columns'=>array(
						array(
							'name'=>'Users.user_name',
							'type'=>'raw',
							'header'=>'Name',
							'value' =>'$data->Users->CompleteName',
							'headerHtmlOptions'=>array(
								'style'=>'width:60%',
							),
						),
						array(
							'name'=>'Users.user_email',
							'type'=>'raw',
							'header'=>'Email',
							'value' =>'$data->Users->user_email',
							'headerHtmlOptions'=>array(
								'style'=>'width:30%',
							),
						),
						array(
							'class'=>'CButtonColumn',
							'template' => '{view} {deleting}',
							//'deleteConfirmation'=>"js:'Record with ID '+$(this).parent().parent().children(':first-child').text()+' will be deleted! Continue?'",
							'buttons' => array(
								'view' => array(
									'label'=> 'Details',
									'url' =>'Yii::app()->controller->createUrl("clients/view",array("id"=>$data->client_id))',
								),
								'deleting' => array(
									'label'=> 'Delete',
									'url' =>'Yii::app()->controller->createUrl("companies/deleteuser",array("id"=>$data->user_id,"ajax"=>true))',
									'imageUrl'=>Yii::app()->request->baseUrl."/images/delete.png",
									'click' => 'js:function(e){
	                                    e.preventDefault();
										$.ajax({
											type:"POST",
											data:{
												YII_CSRF_TOKEN:"'.Yii::app()->request->csrfToken.'",
												iclstyp: 1,
												cid:"'.$model->company_id.'",
											},
											dataType:"json",
											url:$(this).attr("href"),
											success:function(data) {
												$.fn.yiiGridView.update("clients-grid");
												if (data.hasreg) $("#lnkaddclients").find(".tzSelect").show();
												$.ajax({
													type:"POST",
													data:{YII_CSRF_TOKEN:"'.Yii::app()->request->csrfToken.'", action:"clientadd"},
													url:"'.Yii::app()->controller->createUrl("companies/addusers",array("owner"=>$model->company_id,"ajax"=>true)).'",
													success:function(html) {
														$("#lnkaddclients").find("ul").html(html);
													}
												});
											}
	                                    });
	                                }',
								),
							),
						),
					),
				)); ?>
			</div>
		</div>
		<br />
		<div class="portlet">
			<div class="portlet-content">
				<h1 class="ptitle projects"><?php echo Yii::t('companies', 'ProjectsWithCompany'); ?></h1>
				<div class="portlet-tab-nav">
					<?php echo CHtml::link(Yii::t('companies','AddProject'), Yii::app()->createUrl('configuration/createProject'),array('class'=>'button')); ?>
				</div>
				<?php $this->widget('zii.widgets.grid.CGridView', array(
					'id'=>'projects-grid',
					'cssFile'=>'css/screen.css',
					'dataProvider'=>$Projects,
					'summaryText'=>'',
					'columns'=>array(
						array(
							'name'=>'project_name',
							'type'=>'raw',
							'value'=>'CHtml::link(CHtml::encode($data->project_name), Yii::app()->createUrl("projects/view", array("id"=>$data->project_id)))',
						),
						array(
							'name'=>'project_startDate',
							'type'=>'raw',
							'value'=>'CHtml::encode(Yii::app()->dateFormatter->formatDateTime($data->project_startDate, "medium", false))',
						),
						array(
							'name'=>'project_endDate',
							'type'=>'raw',
							'value'=>'CHtml::encode(Yii::app()->dateFormatter->formatDateTime($data->project_endDate, "medium", false))',
						),
						array(
							'name'=>'project_active',
							'type'=>'raw',
							'value' =>'($data->project_active == 1) ? Yii::t("site","yes") : Yii::t("site","no")',
						),
					),
				)); ?>
			</div>
		</div>
	</div>
</div>
<?php
Yii::app()->clientScript->registerScriptFile('http://maps.google.com/maps?file=api&v=2&key='.Yii::app()->params['gmapsApi'].'&sensor=true');
Yii::app()->clientScript->registerScript('GoogleMapsScript','
if (GBrowserIsCompatible()) {
	var map = new GMap2(document.getElementById("map_canvas"));
	map.addControl(new GLargeMapControl());        
	
	var center = new GLatLng('.$model->company_latitude.', '.$model->company_longitude.');
	map.setCenter(center, 16);
	
	var marker = new GMarker(center, {draggable: false});
	map.addOverlay(marker);
}
');
?>
<?php
Yii::app()->clientScript->registerScript('jquery.dropdownflyer','
	$(".menu_right > a").click(function(e) {
		e.preventDefault();
		$(this).next().slideToggle("fast");
		$(this).next().hover(function() {
		}, function(){
			$(this).slideUp("fast");
		});
	});
');
?>
<?php 
Yii::app()->clientScript->registerScript('jquery.dropdownflyer','
	$(".selectBox").click(function(e) {
		$(this).next().slideDown("fast").show();
		$(this).next().hover(function() {
		}, function(){
			$(this).slideUp("fast");
		});
	});
	
	$(".checkLabel").live("click",function(e){
		e.preventDefault();
		return false;
	});

	$(".checkSelItem").live("change",function(e){
		if ($(this).attr("checked")) {
	        var el=$(this); 
	        var update = el.parents().find(".menuSelection").next();
	        var toHide = el.closest(".tzSelect");
	        var dropd = el.parent().parent();
			$.ajax({
				url:el.parents().find(".availablesUsers").attr("link"),
				data: ({YII_CSRF_TOKEN: \''.Yii::app()->request->csrfToken.'\', icls:$(this).attr("data"), iclstyp:dropd.attr("source"), owner:\''.$model->company_id.'\'}),
				type: "POST",dataType:"json",
				success:function(data) {
					if (data.saved){
						dropd.html(data.html);
						$.fn.yiiGridView.update(update.attr("id"));
					}
					if(!data.hasreg)toHide.hide();
				},
			});
	    }
	});
');
?>