<?php

class buckets extends controller {
	public $layout = 'layouts/column2';
	
	public function filter($action) {
		if (auth::check('user')) {
			return true;
		} else {
			sq::redirect(sq::base());
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
		$categories->update();
		
		sq::redirect(sq::base().'buckets');
	}
	
	public function editGetAction($id) {	
		$category = sq::model('categories')
			->where($id)
			->read();
		$this->layout->category = $category;
		$this->layout->content = sq::view('forms/bucket');
		$this->layout->actions = array(
			'Actions',
			'Delete this Bucket' => 'buckets/delete?id='.$category->id
		);
	}
	
	public function createPostAction($categories) {
		$categories->create();
		
		sq::redirect(sq::base().'buckets');
	}
	
	public function createGetAction() {
		$this->layout->category = sq::model('categories')->schema();
		$this->layout->content = sq::view('forms/bucket');
	}
	
	public function deleteAction() {
		if (url::post()) {
			sq::model('categories')
				->where(url::post('id'))
				->delete();
				
			sq::redirect(sq::base().'buckets');
		}
		
		$this->layout->entry = sq::model('categories')
			->where(url::get('id'))
			->read();
			
		$this->layout->content = sq::view('forms/delete');
	}
}

?>