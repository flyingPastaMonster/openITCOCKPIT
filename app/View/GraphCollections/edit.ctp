<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//	This program is free software: you can redistribute it and/or modify
//	it under the terms of the GNU General Public License as published by
//	the Free Software Foundation, version 3 of the License.
//
//	This program is distributed in the hope that it will be useful,
//	but WITHOUT ANY WARRANTY; without even the implied warranty of
//	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//	GNU General Public License for more details.
//
//	You should have received a copy of the GNU General Public License
//	along with this program.  If not, see <http://www.gnu.org/licenses/>.
//

// 2.
//	If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//	under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//	License agreement and license key will be shipped with the order
//	confirmation.
?>

<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-area-chart fa-fw"></i>
			<?php echo __('Monitoring'); ?>
			<span>>
				<?php echo __('Graph Collections'); ?>
			</span>
		</h1>
	</div>
</div>

<div class="overlay" style="display: none;">
	<div id="nag_longoutput_loader"
		 style="position: absolute; top: 50%; left: 50%; margin-top: -29px; margin-left: -23px; z-index: 20; font-size: 40px; color: #fff;">
		<i class="fa fa-cog fa-lg fa-spin"></i>
	</div>
</div>

<section id="widget-grid" class="">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false">
				<header>
					<div class="widget-toolbar" role="menu"></div>
					<div class="jarviswidget-ctrls" role="menu"></div>
					<span class="widget-icon"><i class="fa fa-area-chart"></i></span>

					<h2 class="hidden-mobile hidden-tablet"><?php echo __('Graph Collections'); ?></h2>
					<ul class="nav nav-tabs pull-right padding-left-20" id="widget-tab-1">
						<?php if($this->Acl->hasPermission('index', 'graphcollections')): ?>
							<li>
								<a href="/graph_collections/index">
									<i class="fa fa-lg fa-save"></i>
									<span class="hidden-mobile hidden-tablet"> <?php echo __('List'); ?></span>
								</a>
							</li>
						<?php endif; ?>
						<li class="active">
							<a href="/graph_collections/edit">
								<i class="fa fa-lg fa-plus"></i>
								<span class="hidden-mobile hidden-tablet"> <?php echo __('New'); ?></span>
							</a>
						</li>
						<?php if($this->Acl->hasPermission('display', 'graphcollections')): ?>
							<li>
								<a href="/graph_collections/display">
									<i class="fa fa-lg fa-list-alt"></i>
									<span class="hidden-mobile hidden-tablet"> <?php echo __('View'); ?></span>
								</a>
							</li>
						<?php endif;?>
					</ul>
				</header>

				<div>
					<div class="widget-body">
						<div class="tab-content">
							<div id="new-edit" class="tab-pane fade active in">
								<?php echo $this->Form->create('GraphCollection', [
									'class' => 'form-horizontal clear',
								]); ?>

								<div class="padding-top-10"></div>
								<div class="row">
									<div class="col-xs-12 col-md-9 col-lg-7">
										<?php
										if($collection_id > 0){
											echo $this->Form->input('id', [
												'value' => $collection_id,
												'hiddenField' => true,
											]);
										}
										echo $this->Form->input('name', [
											'label' => __('Name'),
											'value' => $current_name,
											'wrapInput' => 'col col-xs-8',
											'div' => [
												'class' => 'form-group required',
											]
										]);
										echo $this->Form->input('description', [
											'label' => __('Description'),
											'value' => $current_description,
											'wrapInput' => 'col col-xs-8',
										]);
										echo $this->Form->input('GraphgenTmpl', [
											'options' => $this->Html->chosenPlaceholder($saved_templates),
											'value' => $current_templates,
											'class' => 'chosen',
											'multiple' => 'multiple',
											'wrapInput' => 'col col-xs-8',
											'label' => __('Templates'),
											'div' => [
												'class' => 'form-group required',
											]
										]);
										?>
									</div>
								</div>
								<?php echo $this->Form->formActions(); ?>

							</div>

						</div>
						<!-- close tab content -->
					</div>
					<div class="padding-top-20"></div>
					<div class="padding-top-20"></div>
					<!-- close widget body -->
				</div>
			</div>
		</article>
	</div>
</section>

