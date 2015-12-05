<?php

class buckets extends controller {
	public $layout = 'layouts/column2';
	
	public function filter($action) {
		if (sq::auth()->level == 'user') {
			return true;
		} else {
			sq::response()->redirect(sq::base());
		}
	}
	
	public function indexAction() {
		$this->layout->categories = sq::model('categories')->read();
		$this->layout->content = sq::view('buckets/manage');
		$this->layout->actions = array(
			'Actions',
			'Create Bucket' => 'buckets/create'
		);
	}
	
	public function editPostAction($categories) {
		$validator = sq::validator($categories, array(
			'name' => 'required'
		));
		
		if ($validator->isValid) {
			$categories->update();
		} else {
			sq::response()->review();
		}
		
		sq::response()->redirect(sq::base().'buckets');
	}
	
	public function editGetAction($id) {	
		$category = sq::model('categories')
			->find($id);
		
		$this->layout->category = $category;
		$this->layout->content = sq::view('forms/bucket');
		$this->layout->actions = array(
			'Actions',
			'Delete this Bucket' => sq::route()->to(array(
				'controller',
				'action' => 'delete',
				'id' => $category->id
			))
		);
	}
	
	public function createPostAction($categories) {
		$categories->create();
		
		sq::response()->redirect(sq::base().'buckets');
	}
	
	public function createGetAction() {
		$this->layout->category = sq::model('categories')->schema();
		$this->layout->content = sq::view('forms/bucket');
	}
	
	public function deleteAction() {
		if (sq::request()->isPost) {
			sq::model('categories')
				->where(sq::request()->post('id'))
				->delete();
				
			sq::response()->redirect(sq::base().'buckets');
		}
		
		$this->layout->entry = sq::model('categories')
			->where(sq::request()->get('id'))
			->read();
			
		$this->layout->content = sq::view('forms/delete');
	}
}

?>