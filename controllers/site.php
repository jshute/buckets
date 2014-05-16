<?php

class site extends controller {
	public $layout = 'layouts/column2';
	
	public function filter($action) {
		if (auth::check('user') || $action == 'login' || $action == 'getaccount' || $action == 'confirmaccount') {
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
			->read()
			->belongsTo('categories');
		
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
		
		if (url::post()) {
			$entry->set(url::post('save'));
			$entry->created = date('c');
			$entry->updated = date('c');
			$entry->create();
			
			sq::redirect(sq::base().'edit-entry?id='.$entry->id);
		} else {
			$entry->schema();
		}
		
		$this->layout->entries = sq::model('entries')
			->read()
			->belongsTo('categories');
			
		$this->layout->entry = $entry;
		$this->layout->content = sq::view('forms/entry');
	}
	
	public function deleteEntryAction() {
		if (url::post()) {
			sq::model('entries')
				->where(url::post('id'))
				->delete();
				
			sq::model('relations')
				->where(array(
					'related_from' => url::post('id'),
					'related_to' => url::post('id'
				)), 'OR')
				->delete();
				
			sq::redirect(sq::base());
		} else {
			$this->layout->entry = sq::model('entries')
				->where(url::get('id'))
				->read();
			$this->layout->content = sq::view('forms/delete');
		}
	}
	
	public function editEntryAction() {
		$entry = sq::model('entries')
			->where(url::request('id'));
			
		$this->layout->entries = sq::model('entries')
			->read()
			->belongsTo('categories');
			
		if (url::post()) {
			$entry->set(url::post('save'));
			$entry->updated = date('c');
			$entry->update();
			
			sq::redirect(sq::base().'details?id='.url::request('id'));
		} else {
			$entry->read();
		}
		
		$this->layout->entry = $entry;
		$this->layout->content = sq::view('forms/entry');
	}
	
	public function searchAction() {
		$query = url::get('q');
		$cat   = url::get('cat');
		$id    = url::get('id');
		
		if (url::get('id') && url::get('listId') == 'list') {
			$entries = $this->getRelatedEntries();
		} else {
			$sql = $this->makeQuery();
			
			$entries = sq::model('entries')
				->query($sql)
				->belongsTo('categories');
		}
		
		return sq::view('list', array(
			'id' => url::get('listId'),
			'entries' => $entries
		));
	}
	
	private function makeQuery($id = false) {
		$query = url::get('q');
		$cat = url::get('cat');
		
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
	
	public function detailsAction() {
		$id = url::get('id');
		
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
		if (auth::check()) {
			sq::redirect(sq::base());
		}
		
		$this->layout->content = sq::view('login');
	}
	
	public function getAccountAction() {
		$email = url::post('email');
		$validEmail = substr($email, -strlen('@deckers.com')) == '@deckers.com';
		
		if (url::post() && $validEmail) {
			$password = url::post('password');
			
			$hash = auth::hash($email);
			
			$user = sq::model('users')
				->where(array('email' => $email))
				->limit()
				->read();
				
			$data = array(
				'email' => $email,
				'hashkey' => $hash,
				'password' => auth::hash($password),
				'first' => url::post('first'),
				'last' => url::post('last'),
				'level' => ''
			);
			
			if (!isset($user->email)) {
				$user->create($data);
			} else {
				$user->update($data);
			}
			
			$mailer = sq::component('mailer');
			$mailer->options['format'] = 'text';
			$mailer->to = $email;
			$mailer->subject = 'Confirm Buckets Account';
			$mailer->link = sq::base().'confirm-account?hash='.$hash;
			$mailer->textView = 'email/account';
			$mailer->from = 'noreply@deckers.com';
			$mailer->send();
			
			$this->layout->message = 'Activation email sent.';
		}
		
		if (url::post() && !$validEmail) {
			$this->layout->message = 'Email Needs to be an @deckers.com address.';
		}
		
		$this->layout->content = sq::view('get-account');
	}
	
	public function confirmAccountAction() {
		$user = sq::model('users')
			->where(array('hashkey' => url::get('hash')))
			->limit()
			->read();
			
		$user->level = 'user';
		$user->update();
		
		auth::login($user->email);
		
		sq::redirect(sq::base());
	}
	
	public function toggleRelationAction() {
		if (url::post('value') == 'true') {
			sq::model('relations')
				->where(array('related_from' => url::post('relatedId'), 'related_to' => url::post('currentId')), 'AND')
				->delete();
				
			sq::model('relations')
				->where(array('related_from' => url::post('currentId'), 'related_to' => url::post('relatedId')), 'AND')
				->delete();
		} else {
			$relation = sq::model('relations');
			$relation->options['prevent-duplicates'] = false;
			$relation->create(array(
				'related_from' => url::post('currentId'),
				'related_to' => url::post('relatedId')
			));
		}
		
		return url::post('value');
	}
	
	private function getRelatedEntries() {
		$id = url::request('id');
		
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