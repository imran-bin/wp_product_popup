
<section class="Fme_QIFW">
		<div class="row">
			<div class="col-md-12"> 
				<!-- Nav tabs -->
				<div class="card">
					<ul class="subsubsub" role="tablist">
						<li role="presentation" class="active"><a href="#fme_qifw_general_setting" aria-controls="profile" role="tab" data-toggle="tab"><span><?php echo esc_html__('General Settings', 'FME_QIFW'); ?></span></a></li> |
						<li role="presentation"><a href="#fme_qifw_Settings" aria-controls="profile" role="tab" data-toggle="tab"><span><?php echo esc_html__('Manage Rule', 'FME_QIFW'); ?></span></a></li> 
					</ul>
					<!-- Tab panes -->
					<div class="tab-content">
						<div role="tabpanel" class="tab-pane active" id="fme_qifw_general_setting">
							<!-- Quantity Increment settings -->
							<?php require_once FMEQIFW_PLUGIN_DIR . 'admin/view/templates/fme-qifw-general-setting.php' ; ?>
						</div>
						<div role="tabpanel" class="tab-pane" id="fme_qifw_Settings">
							<!-- Quantity Increment settings -->
							<?php require_once FMEQIFW_PLUGIN_DIR . 'admin/view/templates/fme-qifw-manage-rule.php' ; ?>
						</div>
				</div>
			 </div>
	</section>
</div>
