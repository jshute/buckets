<?php

class site extends controller {
	public $layout = 'layouts/column2';
	
	public function filter($action) {
		if (sq::auth()->level == 'user' || in_array($action, array('login', 'get-account', 'confirm-account', 'debug', 'error'))) {
			return true;
		} else {
			$this->layout->content = sq::view('content/home');
			$this->layout->actions = array(
				'Actions',
				'Login' => 'login',
				'Get Account' => 'get-account'
			);
		}
	}
	
	public function indexAction() {
		$this->layout->entries = sq::model('entries')
			->order('name', 'ASC')
			->belongsTo('categories')
			->read();
		
		$this->layout->content = sq::view('search-list', array('id' => 'list'));
		$this->layout->actions = array(
			'Actions',
			'Create Entry' => 'create-entry',
			'Manage Buckets' => 'buckets',
			'User',
			'Logout' => 'auth/logout'
		);
	}
	
	public function createEntryAction() {
		$entry = sq::model('entries');
		
		if (sq::request()->isPost) {
			$entry->set(sq::request()->post('save'));
			$entry->created = date('c');
			$entry->updated = date('c');
			$entry->create();
			
			sq::response()->redirect(sq::base().'edit-entry?id='.$entry->id);
		} else {
			$entry->schema();
		}
		
		$this->layout->entries = sq::model('entries')
			->read()
			->belongsTo('categories');
			
		$this->layout->entry = $entry;
		$this->layout->content = sq::view('forms/entry');
	}
	
	public function deleteEntryPostAction() {
		sq::model('entries')
			->where(sq::request()->post('id'))
			->delete();
			
		sq::model('relations')
			->where(array(
				'related_from' => sq::request()->post('id'),
				'related_to' => sq::request()->post('id'
			)), 'OR')
			->delete();
			
		sq::response()->redirect(sq::base());
	}
	
	public function deleteEntryGetAction() {
		$this->layout->entry = sq::model('entries')
			->where(sq::request()->get('id'))
			->read();
		$this->layout->content = sq::view('forms/delete');
	}
	
	public function editEntryAction() {
		$entry = sq::model('entries')
			->where(sq::request()->any('id'));
			
		$this->layout->entries = sq::model('entries')
			->read()
			->belongsTo('categories');
			
		if (sq::request()->isPost) {
			$entry->set(sq::request()->post('save'));
			$entry->updated = date('c');
			$entry->update();
			
			sq::response()->redirect(sq::base().'details?id='.sq::request()->any('id'));
		} else {
			$entry->read();
		}
		
		$this->layout->entry = $entry;
		$this->layout->content = sq::view('forms/entry');
	}
	
	public function searchAction() {
		$query = sq::request()->get('q');
		$cat   = sq::request()->get('cat');
		$id    = sq::request()->get('id');
		
		if (sq::request()->get('id') && sq::request()->get('listId') == 'list') {
			$entries = $this->getRelatedEntries();
		} else {
			$sql = $this->makeQuery();
			
			$entries = sq::model('entries')
				->query($sql)
				->belongsTo('categories');
		}
		
		return sq::view('list', array(
			'id' => sq::request()->get('listId'),
			'entries' => $entries
		));
	}
	
	private function makeQuery($id = false) {
		$query = sq::request()->get('q');
		$cat = sq::request()->get('cat');
		
		$sql = 'SELECT name, id, categories_id FROM entries';
		if ($query) {
			$sql .= ' WHERE name LIKE "%'.$query.'%"';
		}
		
		if ($cat) {
			if ($query) {
				$sql .= ' AND';
			} else {
				$sql .= ' WHERE';
			}
			
			$sql .= ' categories_id = "'.$cat.'"';
		}
		
		if ($id) {
			if ($query || $cat) {
				$sql .= ' AND';
			} else {
				$sql .= ' WHERE';
			}
			
			$sql .= ' id = "'.$id.'"';
		}
		
		$sql .= ' ORDER BY name ASC';
		
		return $sql;
	}
	
	public function detailsAction($id) {
		$entry = sq::model('entries')
			->where($id)
			->read()
			->belongsTo('categories');
		
		$this->layout->entry = $entry;
		$this->layout->related = $this->getRelatedEntries();
		
		$this->layout->content = sq::view('details');
		$this->layout->actions = array(
			'Actions',
			'Edit' => 'edit-entry?id='.$entry->id,
			'Delete' => 'delete-entry?id='.$entry->id,
			'Create Entry' => 'create-entry'
		);
	}
	
	public function loginAction() {
		if (sq::auth()->isLoggedIn) {
			sq::response()->redirect(sq::base());
		}
		
		$this->layout->content = sq::view('login');
	}
	
	public function getAccountAction() {
		$email = sq::request()->post('email');
		$validEmail = substr($email, -strlen('@deckers.com')) == '@deckers.com';
		
		if (sq::request()->isPost && $validEmail) {
			$password = sq::request()->post('password');
			
			$hash = auth::hash($email);
			
			$user = sq::model('users')
				->find(array('email' => $email))
				->save(array(
					'email' => $email,
					'hash' => $hash,
					'password' => auth::hash($password),
					'first' => sq::request()->post('first'),
					'last' => sq::request()->post('last'),
					'level' => ''
				));
			
			sq::mailer(array(
				'to' => $email,
				'subject' => 'Confirm Buckets Accont',
				'from' => 'noreply@deckers.com',
				'test' => sq::view('email/account', array(
					'link' => sq::base().'confirm-account?hash='.$hash
				))
			))->send();
			
			$this->layout->message = 'Activation email sent.';
		}
		
		if (sq::request()->isPost && !$validEmail) {
			$this->layout->message = 'Email Needs to be an @deckers.com address.';
		}
		
		$this->layout->content = sq::view('get-account');
	}
	
	public function confirmAccountAction($hash) {
		$user = sq::model('users')
			->find(array('hash' => $hash));
			
		$user->level = 'user';
		$user->update();
		
		sq::auth()->login($user->email);
		
		sq::response()->redirect(sq::base());
	}
	
	public function toggleRelationAction() {
		if (sq::request()->post('value') == 'true') {
			sq::model('relations')
				->where(array('related_from' => sq::request()->post('relatedId'), 'related_to' => sq::request()->post('currentId')), 'AND')
				->delete();
				
			sq::model('relations')
				->where(array('related_from' => sq::request()->post('currentId'), 'related_to' => sq::request()->post('relatedId')), 'AND')
				->delete();
		} else {
			$relation = sq::model('relations');
			$relation->options['prevent-duplicates'] = false;
			$relation->create(array(
				'related_from' => sq::request()->post('currentId'),
				'related_to' => sq::request()->post('relatedId')
			));
		}
		
		return sq::request()->post('value');
	}
	
	private function getRelatedEntries() {
		$id = sq::request()->get('id');
		
		$related = sq::model('relations')
			->where(array('related_from' => $id, 'related_to' => $id), 'OR')
			->read();
			
		$entries = sq::model('entries');
		$ids = array();
		foreach ($related as $key => $item) {
			$entry = sq::model('entries')
				->limit()
				->query($this->makeQuery($item->related_to));
				
			if (isset($entry->name) && $entry->id != $id && !in_array($entry->id, $ids)) {
				$entries[] = $entry;
				$ids[] = $entry->id;
			}
			
			$entry = sq::model('entries')
				->limit()
				->query($this->makeQuery($item->related_from));
				
			if (isset($entry->name) && $entry->id != $id && !in_array($entry->id, $ids)) {
				$entries[] = $entry;
				$ids[] = $entry->id;
			}
		}
		
		return $entries->belongsTo('categories');
	}
}

?>