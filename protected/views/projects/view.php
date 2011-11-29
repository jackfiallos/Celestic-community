<?php
$this->breadcrumbs=array(
	Yii::t('projects', 'TitleProjects')=>array('index'),
	$model->project_name,
);
$this->pageTitle = Yii::app()->name." - ".Yii::t('projects', 'TitleProjects')." :: ".CHtml::encode($model->project_name);
$cs = Yii::app()->getClientScript();
$cs->registerCssFile(Yii::app()->request->baseUrl.'/css/menu.css'); 
?>

<div class="portlet">
	<div class="portlet-content">
		<h1 class="ptitleinfo projects"><?php echo $model->project_name; ?></h1>
		<?php if (Yii::app()->user->isManager):?>
		<div class="portlet-tab-nav">
			<?php echo CHtml::link(Yii::t('projects', 'UpdateProjects'), Yii::app()->controller->createUrl('update',array('id'=>$model->project_id)),array('class'=>'button primary')); ?>
		</div>
		<?php endif;?>
		<?php if(Yii::app()->user->checkAccess('viewBudgets')): ?>
		<div class="subcolumns">
			<div class="c66l">
				<?php
				$this->widget('zii.widgets.CDetailView', array(
					'data'=>$model,
					'attributes'=>array(
						array(
							'name'=>'project_startDate',
							'type'=>'raw',
							'value'=>CHtml::encode(Yii::app()->dateFormatter->formatDateTime($model->project_startDate, "medium", false)),
						),
						array(
							'name'=>'project_endDate',
							'type'=>'raw',
							'value'=>CHtml::encode(Yii::app()->dateFormatter->formatDateTime($model->project_endDate, "medium", false)),
						),
						array(
							'name'=>'company_id',
							'type'=>'raw',
							'value'=>CHtml::link(CHtml::encode($model->Company->company_name),Yii::app()->controller->createUrl("companies/view",array("id"=>$model->Company->company_id))),
						),
						array(
							'name'=>'project_active',
							'type'=>'raw',
							'value'=>($model->project_active == 1) ? Yii::t("site", "yes") : Yii::t("site", "no"),
						),
					),
				));
				?>
			</div>
			<div class="c33r" style="text-align:center">
				<div class="avcash section">
					<div class="sidebar-box">
						<div class="sidebar-box-wrap">
							<div class="avcashtop">
								<h2><?php echo Yii::t('projects','ProjectCost'); ?></h2>
								<strong>
									<span>
										<em>
											<?php echo Yii::app()->NumberFormatter->formatCurrency(CHtml::encode($ProjectCost->total), $model->Currency->currency_code); ?>
										</em>
										<?php echo $model->Currency->currency_code; ?>
									</span>
								</strong>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<br />
		<?php endif; ?>
		
		<?php
		$itemTab = array(
			Yii::t('projects','project_description')=>$model->project_description,
			Yii::t('projects','project_scope')=>$model->project_scope,
			Yii::t('projects','project_restrictions')=>$model->project_restrictions,
			Yii::t('projects','project_responsibilities')=>$model->project_responsibilities,
			Yii::t('projects','project_additionalCosts')=>$model->project_additionalCosts,
		);

		$tabs = array();
		foreach($itemTab as $key => $value)
		{
			if(strlen($value) > 0)
				$tabs[$key] = CHtml::tag('div',array('class'=>'slidetoogleContent'),Yii::app()->format->html(nl2br(ECHtml::createLinkFromString($value)))); 
		}
		
		if (count($tabs)>0):
		?>
		<div class="portlet grid_12">
			<div class="portlet-content">
				<h1 class="ptitle projectpart"><?php echo Yii::t('projects', 'ProjectAnalisisParameters'); ?></h1>
				<?php
					$this->widget('zii.widgets.jui.CJuiTabs', array(
					'tabs'=>$tabs,
					// additional javascript options for the tabs plugin
					'options'=>array(
						'fxAutoHeight'=>true
					),
				));
				?>
			</div>
		</div>
		<?php endif; ?>
		
		<?php
		$itemTab = array(
			Yii::t('projects','project_plataform')=>$model->project_plataform,
			Yii::t('projects','project_swRequirements')=>$model->project_swRequirements,
			Yii::t('projects','project_hwRequirements')=>$model->project_hwRequirements,
		);

		$tabs = array();
		foreach($itemTab as $key => $value)
		{
			if(strlen($value) > 0)
				$tabs[$key] = CHtml::tag('div',array('class'=>'slidetoogleContent'),Yii::app()->format->html(nl2br(ECHtml::createLinkFromString($value)))); 
		}
		
		if (count($tabs)>0):
		?>
		<div class="portlet grid_12">
			<div class="portlet-content">
				<h1 class="ptitle projectpart"><?php echo Yii::t('projects', 'ProjectDesignParameters'); ?></h1>
				<?php
					$this->widget('zii.widgets.jui.CJuiTabs', array(
						'tabs'=>$tabs,
						// additional javascript options for the tabs plugin
						'options'=>array(
							'fxAutoHeight'=>true
						),
					));
				?>
			</div>
		</div>
		<?php endif; ?>
		
		<?php
		$itemTab = array(
			Yii::t('projects','project_userInterfaces')=>$model->project_userInterfaces,
			Yii::t('projects','project_hardwareInterfaces')=>$model->project_hardwareInterfaces,
			Yii::t('projects','project_softwareInterfaces')=>$model->project_softwareInterfaces,
			Yii::t('projects','project_communicationInterfaces')=>$model->project_communicationInterfaces,
		);

		$tabs = array();
		foreach($itemTab as $key => $value)
		{
			if(strlen($value) > 0)
				$tabs[$key] = CHtml::tag('div',array('class'=>'slidetoogleContent'),Yii::app()->format->html(nl2br(ECHtml::createLinkFromString($value))));
		}
		
		if (count($tabs)>0):
		?>
		<div class="portlet grid_12">
			<div class="portlet-content">
				<h1 class="ptitle projectpart"><?php echo Yii::t('projects', 'ExternalInterfaces'); ?></h1>
				<?php
					$this->widget('zii.widgets.jui.CJuiTabs', array(
						'tabs'=>$tabs,
						// additional javascript options for the tabs plugin
						'options'=>array(
							'fxAutoHeight'=>true
						),
					));
				?>
			</div>
		</div>
		<?php endif; ?>
		
		<?php
		$itemTab = array(
			Yii::t('projects','project_functionalReq')=>$model->project_functionalReq,
			Yii::t('projects','project_performanceReq')=>$model->project_performanceReq,
			Yii::t('projects','project_additionalComments')=>$model->project_additionalComments,
		);

		$tabs = array();
		foreach($itemTab as $key => $value)
		{
			if(strlen($value) > 0)
				$tabs[$key] = CHtml::tag('div',array('class'=>'slidetoogleContent'),Yii::app()->format->html(nl2br(ECHtml::createLinkFromString($value))));
		}
		
		if (count($tabs)>0):
		?>
		<div class="portlet grid_12">
			<div class="portlet-content">
				<h1 class="ptitle projectpart"><?php echo Yii::t('projects', 'SpecificRequirements'); ?></h1>
				<?php
					$this->widget('zii.widgets.jui.CJuiTabs', array(
						'tabs'=>$tabs,
						// additional javascript options for the tabs plugin
						'options'=>array(
							'fxAutoHeight'=>true
						),
					));
				?>
			</div>
		</div>
		<?php endif; ?>
		
		<?php
		$itemTab = array(
		Yii::t('projects','project_backupRecovery')=>$model->project_backupRecovery,
			Yii::t('projects','project_dataMigration')=>$model->project_dataMigration,
			Yii::t('projects','project_userTraining')=>$model->project_userTraining,
			Yii::t('projects','project_installation')=>$model->project_installation,
		);

		$tabs = array();
		foreach($itemTab as $key => $value)
		{
			if(strlen($value) > 0)
				$tabs[$key] = CHtml::tag('div',array('class'=>'slidetoogleContent'),Yii::app()->format->html(nl2br(ECHtml::createLinkFromString($value))));
		}
		
		if (count($tabs)>0):
		?>
		<div class="portlet grid_12">
			<div class="portlet-content">
				<h1 class="ptitle projectpart"><?php echo Yii::t('projects', 'SpecialUserRequirements'); ?></h1>
				<?php
					$this->widget('zii.widgets.jui.CJuiTabs', array(
						'tabs'=>$tabs,
						// additional javascript options for the tabs plugin
						'options'=>array(
							'fxAutoHeight'=>true
						),
					));
				?>
			</div>
		</div>
		<?php endif; ?>
		
		<?php
		$itemTab = array(
			Yii::t('projects','project_assumptions')=>$model->project_assumptions,
			Yii::t('projects','project_warranty')=>$model->project_warranty,
			Yii::t('projects','project_outReach')=>$model->project_outReach,
		);

		$tabs = array();
		foreach($itemTab as $key => $value)
		{
			if(strlen($value) > 0)
				$tabs[$key] = CHtml::tag('div',array('class'=>'slidetoogleContent'),Yii::app()->format->html(nl2br(ECHtml::createLinkFromString($value)))); 
		}
		
		if (count($tabs)>0):
		?>
		<div class="portlet grid_12">
			<div class="portlet-content">
				<h1 class="ptitle projectpart"><?php echo Yii::t('projects', 'SpecialConsiderations'); ?></h1>
				<?php
					$this->widget('zii.widgets.jui.CJuiTabs', array(
						'tabs'=>$tabs,
						// additional javascript options for the tabs plugin
						'options'=>array(
							'fxAutoHeight'=>true
						),
					));
				?>
			</div>
		</div>
		<?php endif; ?>
		
		<div class="portlet grid_12">
			<div class="portlet-content">
				<h1 class="ptitle users"><?php echo Yii::t('projects', 'ProjectsManagers'); ?></h1>
				
				<?php if(Yii::app()->user->checkAccess('createProjects')):?>
				<div style="width:150px; display:<?php echo (count($availablesManagers)>0) ? "block" : "none"; ?>" class="tzSelect">
					<div class="selectBox"><?php echo Yii::t('projects','addManagers');?></div>
					<div class="dropdown_1column align_right corners">
			            <div class="col_1" id="managersList">
			                <ul class="availablesManagers" link="<?php echo Yii::app()->createUrl('projects/addmanager'); ?>">
			                	<?php
			                		$this->renderPartial('_dropdownUsersList',array(
										'availablesManagers'=>$availablesManagers
									));
			                	?>
			                </ul>
			            </div>
			        </div>
				</div>
				<?php endif; ?>
				
				<?php $this->widget('zii.widgets.CListView', array(
					'id'=>'users-gallery',
					'dataProvider'=>$managers,
					'template'=>'{items}',
					'itemView'=>'_projectsManagers',
					'afterAjaxUpdate'=>'js:function(){ $("_users-gallery").removeClass("loading"); }',
					'beforeAjaxUpdate'=>'js:function(){ $("_users-gallery").addClass("loading"); }',
					'htmlOptions'=>array(
						'style'=>'padding-left:10px'
					),
				));?>
			</div>
		</div>
		
		<?php if(Yii::app()->user->checkAccess('viewInvoices')) :?>
		<div class="portlet grid_6">
			<div class="portlet-content">
				<h1 class="ptitle budgets"><?php echo Yii::t('projects', 'InvoiceHistory'); ?></h1>
				<?php $this->widget('zii.widgets.grid.CGridView', array(
					'id'=>'invoices-grid',
					'cssFile'=>'css/screen.css',
					'dataProvider'=>$Invoices,
					'summaryText'=>Yii::t('site','summaryText'),
					'emptyText'=>Yii::t('site','emptyText'),
					'columns'=>array(
						array(
							'type'=>'raw',
							'header'=>Yii::t('projects','InvoiceTitle'),
							'value'=>'"Invoice _ ".CHtml::link(CHtml::encode($data->invoice_number), array("invoices/view", "id"=>$data->invoice_id))',
							'headerHtmlOptions'=>array(
								'style'=>'width:60%',
							),
						),
						array(
							'type'=>'raw',
							'header'=>Yii::t('projects','ProjectCost'),
							'value'=>'Yii::app()->NumberFormatter->formatCurrency(CHtml::encode($data->Cost), $data->Projects->Currency->currency_code)',
							'headerHtmlOptions'=>array(
								'style'=>'width:30%',
							),
						),
						array(
							'type'=>'raw',
							'name'=>'Projects.currency_id',
							'value'=>'$data->Projects->Currency->currency_code',
							'headerHtmlOptions'=>array(
								'style'=>'width:10%',
							),
						),
					),
				)); ?>
			</div>
		</div>
		<?php endif; ?>
		
		<div class="portlet grid_6">
			<div class="portlet-content">
				<h1 class="ptitle milestones"><?php echo Yii::t('projects', 'ProjectsMilestones'); ?></h1>
				<?php $this->widget('zii.widgets.grid.CGridView', array(
					'id'=>'milestones-grid',
					'cssFile'=>'css/screen.css',
					'dataProvider'=>$Milestones,
					'summaryText'=>Yii::t('site','summaryText'),
					'emptyText'=>Yii::t('site','emptyText'),
					'columns'=>array(
						array(
							'name'=>'milestone_title',
							'type'=>'raw',
							'value'=>'CHtml::link(CHtml::encode($data->milestone_title), array("milestones/view", "id"=>$data->milestone_id))',
							'headerHtmlOptions'=>array(
								'style'=>'width:60%',
							),
						),
						array(
							'name'=>'milestone_duedate',
							'type'=>'raw',
							'value'=>'CHtml::encode(Yii::app()->dateFormatter->formatDateTime($data->milestone_duedate, "medium", false))',
							'headerHtmlOptions'=>array(
								'style'=>'width:40%',
							),
						),
					),
				)); ?>
			</div>
		</div>
		
		<div class="portlet grid_6">
			<div class="portlet-content">
				<h1 class="ptitle users"><?php echo Yii::t('projects', 'UsersInProject'); ?></h1>
				<?php if(Yii::app()->user->isManager): ?>
				<div style="width:150px; display:<?php echo (count($UsersList)>0) ? "block" : "none"; ?>" class="tzSelect">
					<div class="selectBox"><?php echo Yii::t('projects','addManagers');?></div>
					<div class="dropdown_1column align_right corners">
			            <div class="col_1" id="usersList">
			                <ul class="availablesUsers" link="<?php echo Yii::app()->createUrl('projects/adduser'); ?>">
			                	<?php
			                		$this->renderPartial('_dropdownUsersList',array(
										'availablesManagers'=>$UsersList
									));
			                	?>
			                </ul>
			            </div>
			        </div>
				</div>
				<?php endif; ?>
				
				<?php $this->widget('zii.widgets.grid.CGridView', array(
					'id'=>'users-grid',
					'cssFile'=>'css/screen.css',
					'dataProvider'=>$Users,
					'summaryText'=>Yii::t('site','summaryText'),
					'emptyText'=>Yii::t('site','emptyText'),
					'columns'=>array(
						array(
							'name'=>'user_name',
							'type'=>'raw',
							'value' =>'CHtml::link($data->CompleteName,Yii::app()->controller->createUrl("users/view",array("id"=>$data->user_id)))',
							'headerHtmlOptions'=>array(
								'style'=>'width:90%',
							),
						),
						array(
							'class'=>'CButtonColumn',
							'template' => '{deleting}',
							'buttons' => array(
								'deleting' => array(
									'label'=> 'Delete',
									'visible'=>'($data->user_admin) ? false : true',
									'url' =>'Yii::app()->controller->createUrl("projects/removeuser",array("ajax"=>true,"uid"=>$data->user_id,"action"=>"user"))',
									'imageUrl'=>Yii::app()->request->baseUrl."/images/delete.png",
									'click' => 'js:function(e){					
	                                    e.preventDefault();
	                                    var update = $(this).closest(".grid-view");
										$.ajax({
											type:"POST",
											data:{YII_CSRF_TOKEN:"'.Yii::app()->request->csrfToken.'"},
											dataType:"json",
											url:$(this).attr("href"),
											success:function(data) {
												$.fn.yiiGridView.update(update.attr("id"));
												if (data.hasreg) {
													update.prev().show();
													update.prev().find("ul").html(data.html);
													$(".availablesManagers").html(data.htmlManager);
												}
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
		<div class="portlet grid_6">
			<div class="portlet-content">
				<h1 class="ptitle clients"><?php echo Yii::t('projects', 'ClientsInProject'); ?></h1>
				<?php if(Yii::app()->user->isManager): ?>
				<div style="width:150px; display:<?php echo (count($ClientsList)>0) ? "block" : "none"; ?>" class="tzSelect">
					<div class="selectBox"><?php echo Yii::t('projects','addManagers');?></div>
					<div class="dropdown_1column align_right corners">
			            <div class="col_1" id="clientsList">
			                <ul class="availablesClients" link="<?php echo Yii::app()->createUrl('projects/addclient'); ?>">
			                	<?php
			                		$this->renderPartial('_dropdownUsersList',array(
										'availablesManagers'=>$ClientsList
									));
			                	?>
			                </ul>
			            </div>
			        </div>
				</div>
				<?php endif; ?>
			
				<?php $this->widget('zii.widgets.grid.CGridView', array(
					'id'=>'clients-grid',
					'cssFile'=>'css/screen.css',
					'dataProvider'=>$Clients,
					'summaryText'=>Yii::t('site','summaryText'),
					'emptyText'=>Yii::t('site','emptyText'),
					'columns'=>array(
						array(
							'name'=>'Users.user_name',
							'type'=>'raw',
							'value' =>'CHtml::link($data->Users->CompleteName,Yii::app()->controller->createUrl("clients/view",array("id"=>$data->client_id)))',
							'headerHtmlOptions'=>array(
								'style'=>'width:90%',
							),
						),
						array(
							'class'=>'CButtonColumn',
							'template' => '{deleting}',
							'buttons' => array(
								'deleting' => array(
									'label'=> 'Delete',
									'visible'=>'($data->Users->user_admin) ? false : true',
									'url' =>'Yii::app()->controller->createUrl("projects/removeuser",array("ajax"=>true,"uid"=>$data->user_id,"action"=>"client"))',
									'imageUrl'=>Yii::app()->request->baseUrl."/images/delete.png",
									'click' => 'js:function(e){					
	                                    e.preventDefault();
	                                    var update = $(this).closest(".grid-view");
										$.ajax({
											type:"POST",
											data:{YII_CSRF_TOKEN:"'.Yii::app()->request->csrfToken.'"},
											dataType:"json",
											url:$(this).attr("href"),
											success:function(data) {
												$.fn.yiiGridView.update(update.attr("id"));
												if (data.hasreg){
													update.prev().show();
													update.prev().find("ul").html(data.html);
													$(".availablesManagers").html(data.htmlManager);
												}
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
		
		<div class="portlet grid_12">
			<div class="portlet-content">
				<h1 class="ptitle documents"><?php echo Yii::t('projects', 'LastDocumentsUploaded'); ?></h1>
				<?php $this->widget('zii.widgets.grid.CGridView', array(
					'id'=>'documents-grid',
					'cssFile'=>'css/screen.css',
					'dataProvider'=>$Documents,
					'summaryText'=>Yii::t('site','summaryText'),
					'emptyText'=>Yii::t('site','emptyText'),
					'columns'=>array(
						array(
							'type'=>'raw',
							'name'=>'document_type',
							'value'=>'CHtml::image(Yii::app()->request->baseUrl."/images/filetypes/file_extension_".end(explode("/", $data->document_type)).".png")',
							'headerHtmlOptions'=>array(
								'style'=>'width:5%',
							),
						),
						array(
							'type'=>'raw',
							'name'=>'document_name',
							'value'=>'CHtml::link(CHtml::encode($data->document_name), array("documents/download", "id"=>$data->document_id), array("target"=>"_blank","class"=>(in_array($data->document_type,array("image/png","image/jpeg","image/gif","image/bmp")))?"lnkdownloadimage":"lnkdownloadfile"))',
							'headerHtmlOptions'=>array(
								'style'=>'width:20%',
							),
						),
						array(
							'type'=>'raw',
							'name'=>'document_description',
							'value'=>'CHtml::encode($data->document_description)',
							'headerHtmlOptions'=>array(
								'style'=>'width:60%',
							),
						),
						array(
							'type'=>'raw',
							'name'=>'document_uploadDate',
							'value'=>'CHtml::encode(Yii::app()->dateFormatter->formatDateTime($data->document_uploadDate, "medium", false))',
							'headerHtmlOptions'=>array(
								'style'=>'width:15%',
							),
						),
						array(
							'class'=>'CButtonColumn',
							'template' => '{view}',
							'buttons' => array(
								'view' => array(
									'label'=> 'Details',
									'url' =>'Yii::app()->controller->createUrl("documents/view",array("id"=>$data->document_id))',
								)
							),
						),
					),
				)); ?>
			</div>
		</div>
		<div class="clear">&nbsp;</div>
		
		<div class="result">
			<?php $this->widget('widgets.ListComments',array(
				'resourceid'=>$model->project_id,
				'moduleid'=>Yii::app()->controller->id,
				'htmlOptions' => array(
					'class'=>'portlet'
				),
			)); ?>
		</div>
	</div>
</div>
<?php		
	$this->widget('application.extensions.YiiColorBox.Colorbox', array(
		'element'=>'.lnkdownloadimage',
		'options'=>array(
			'width'=>'800px',
			'height'=>'450px',
		),
	));
?>
<?php
Yii::app()->clientScript->registerScript('jquery.dropdownflyer','
	$(".selectBox").click(function(e) {
		$(this).next().slideToggle("fast");
		$(this).next().hover(function() {
		}, function(){
			$(this).slideUp("fast");
		});
	});

	$(".deleteUser").live("click",function(e){
		e.preventDefault();
		var el=$(this); 
		$.ajax({
			url:el.attr("href"),
			data: ({YII_CSRF_TOKEN: \''.Yii::app()->request->csrfToken.'\', uid: $(this).attr("data")}),
			type: "POST",dataType:"json",
			success:function(data) {
				if (data.saved){
					$(".availablesManagers").html(data.html);
					$.fn.yiiListView.update("users-gallery");
				}
				if(data.hasreg)$(".tzSelect").show();
			},
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
				url:dropd.attr("link"),
				data: ({YII_CSRF_TOKEN: \''.Yii::app()->request->csrfToken.'\', uid: $(this).attr("data")}),
				type: "POST",dataType:"json",
				success:function(data) {
					if (data.saved){
						dropd.html(data.html);
						$.fn.yiiListView.update("users-gallery");
						$.fn.yiiGridView.update(toHide.next().attr("id"));
						$(".availablesManagers").html(data.htmlManager);
					}
					if(!data.hasreg)toHide.hide();
				},
			});
	    }
	});
');
?>