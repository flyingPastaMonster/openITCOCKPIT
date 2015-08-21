<?php
// Copyright (C) <2015>  <it-novum GmbH>
// 
// This file is dual licensed
// 1.
//     This program is free software: you can redistribute it and/or modify
//     it under the terms of the GNU General Public License as published by
//     the Free Software Foundation, version 3 of the License
// 
//     This program is distributed in the hope that it will be useful,
//     but WITHOUT ANY WARRANTY; without even the implied warranty of
//     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//     GNU General Public License for more details.
// 
//     You should have received a copy of the GNU General Public License
//     along with this program.  If not, see <http://www.gnu.org/licenses/>.
// 2.
//     If you purchased a openITCOCKPIT 'License key' you can use this file
//     under the terms of the it-novum licence
//     You can find a copy of the licence at
//     /usr/share/openitcockpit/commercial/it-novum-LICENCE.txt
//     on your system

class MapviewsController extends MapModuleAppController {

	public $layout = 'Admin.default';

	public function index(){
		//debug('Map views Controller index funzt');
	}


	/*
		@TODO implement data refreshing of objects which are used on a map
	 */
	public function refreshNagiosObjects(){
		$this->autoRender = false;
	}
}