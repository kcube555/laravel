<?php

namespace App\Models\Main;

class Menus extends \App\Models\BaseModel {
	protected $table = 'menus';

	protected $fillable = array('parent_id','title','url', 'icon', 'order');

	public function parent() {
		return $this->belongsTo('App\Models\Main\Menus', 'parent_id');
	}

	public function children() {
		return $this->hasMany('App\Models\Main\Menus', 'parent_id');
	}
}
