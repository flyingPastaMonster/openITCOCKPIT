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

class MapsController extends MapModuleAppController {

	public $layout = 'Admin.default';
	public $components = ['Paginator', 'ListFilter.ListFilter','RequestHandler','CustomValidationErrors'];
	public $helpers = ['CustomValidationErrors', 'ListFilter.ListFilter'];
	//public $uses = ['Tenant'];
	
	public $listFilters = ['index' => [
		'fields' => [
			'Map.name' => ['label' => 'Name', 'searchType' => 'wildcard'],
			'Map.title' => ['label' => 'Title', 'searchType' => 'wildcard'],
			//'Tenant.name' => array('label' => 'Contact', 'searchType' => 'wildcard'),
		],
	]];

	public function index(){
		if(!isset($this->Paginator->settings['conditions'])){
			$this->Paginator->settings['conditions'] = [];
		}
		$this->Paginator->settings['order'] = ['Map.name' => 'asc'];
		$this->set('all_maps', $this->Paginator->paginate());
		//Aufruf für json oder xml view: /nagios_module/hosts.json oder /nagios_module/hosts.xml
		$this->set('_serialize', array('all_maps'));
		$this->set('isFilter', false);
		if(isset($this->request->data['Filter']) && $this->request->data['Filter'] !== null){
			$this->set('isFilter', true);
		}
	}


	public function add(){
		$this->loadModel('Container');

		$tenants = $this->Container->find('list',[
			'conditions' => [
				'containertype_id' => CT_TENANT
			]
		]);

		$this->set(compact('tenants'));

		if($this->request->is('post') || $this->request->is('put')){
			$this->request->data['Container'] = $this->request->data['Map']['container_id'];
			if($this->Map->saveAll($this->request->data)){
				$this->setFlash(__('Map properties successfully saved'));
				$this->redirect(['action' => 'index']);
			}else{
				$this->setFlash(__('could not save data'), false);
			}
		}
	}

	public function edit($id = null){
		if(!$this->Map->exists($id)) {
			throw new NotFoundException(__('Invalid map'));
		}
		$this->loadModel('Tenant');
		$this->Map->recursive = -1;
		$this->Map->autoFields = false;
	
	
		$this->Map->contain([
			'Container' => [
				'fields' => ['id', 'name']
			]
		]);
	
		$map = $this->Map->findById($id);	
		//'Tenant.description' <-> Container.name
		$tenants = Set::combine($this->Tenant->find('all',[

				'fields' => [
						'Container.id', 
						'Container.name'
					],
					'order' => 'Container.name ASC'
				]
			),
			'{n}.Container.id', '{n}.Container.name'
		);
		$this->set(compact('map', 'tenants'));

		if($this->request->is('post') || $this->request->is('put')){
			$this->request->data['Container'] = $this->request->data['Map']['container_id'];
			if($this->Map->saveAll($this->request->data)){
				$this->setFlash(__('Map properties successfully saved'));
				$this->redirect(['action' => 'index']);
			}else{
				$this->setFlash(__('could not save data'), false);
			}
		}
	}

	public function delete($id = null){
		if(!$this->Map->exists($id)){
			throw new NotFoundException(__('Invalid Map'));
		}

		if(!$this->request->is('post')){
			throw new MethodNotAllowedException();
		}

		if($this->Map->delete($id)){
			$this->setFlash(__('Map deleted'));
			$this->redirect(array('action' => 'index'));
		}

		$this->setFlash(__('Could not delete map'), false);
		$this->redirect(array('action' => 'index'));
	}

	public function loadUsersForTenant($tenantId = []){
		//$users = $this->Admin->Users->contactsByContainerId($this->MY_RIGHTS, 'list', 'id');
		
		
		//debug(gettype(urldecode($tenantId)));
		//die('ende');
		foreach ($tenantId as $key => $value) {
			debug($key);
			debug($value);
		}
		//$users = $this->Admin->Users->find();

		echo 'response vom MapsController';
	}
}