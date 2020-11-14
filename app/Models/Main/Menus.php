<?php

namespace App\Models\Main;

class Menus extends \App\Models\BaseModel {
	protected $fillable = array('parent_id','title','url', 'icon', 'order');

	public function parent() {
		return $this->belongsTo('App\Models\Menus', 'parent_id');
	}

	public function children() {
		return $this->hasMany('App\Models\Menus', 'parent_id');
	}
}
