<?php
/**
 * @category  App
 * @package   App_Models
 */

/**
 * Manages the ACL roles for authetications
 *
 * @category App
 * @package  App_Models
 */
class AclRoles extends Base_Db_Table {

	/**
	 * Datebase table name used for this model.
	 *
	 * @var string
	 */
	protected $_name = 'acl_roles';

	/**
	 * Specify a custom Row to be used by default in all instances of a Table class
	 *
	 * @var string $_rowClass Custom row name
	 */
	protected $_rowClass = 'AclRoles_Row';

	/**
	 * Specify a custom Row to be used by default in all instances of a Table class
	 *
	 * @var string $_rowClass Custom row name
	 */
	protected $_rowsetClass = 'AclRoles_Rowset';


	/**
	 * List of models that are dependent on this model.
	 *
	 * @var array Model names
	 */
	protected $_dependentTables = array('Users_AclRoles');

	/*
	 * Array of selected ACL resources. Used when assigning ACL Resources to ACL
	 * roles via form.
	 *
	 * @var array
	 */
	protected $_selectedResources = null;

	/**
	 * Model profile
	 *
	 * @return array $profile Validation profile
	 */
	public function profile() {

		$data      = $this->getData();
		$aclRoleId = isset($data['id']) ? $data['id'] : null;

		$profile = array(
			'insert' => array(
				'required' => array(
					'name'        => 'Enter role name',
					'description' => 'Enter description',
				),
				'optional'    => array(),
				'constraints' => array(
					'name'	=> array(
						array('Alnum', 'Invalid role name', true),
						array('Unique', 'Role name already exists', array('AclRoles', 'name', $aclRoleId)),
					),
				),
			),
			'update' => array(
				'required' => array(
					'id'          => 'Missing ID',
					'name'        => 'Enter role name',
					'description' => 'Enter description',
				),
				'optional' => array(),
				'constraints' => array(
					'name'	=> array(
						array('Alnum', 'Invalid name', true),
						array('Unique', 'Role name already exists', array('AclRoles', 'name', $aclRoleId)),
					),
				),
			),
		);

		return $profile;
	}

	/**
	 * Delete a record using primary key. We'd want to make sure first that the ACL
	 * role has no assosiated user.
	 *
	 * @param string ACL Role ID
	 * @return boolean True on success otherwise false
	 */
	public function postDelete($field = null, $value = null) {
		/**
		if ($field == 'id') {
			$aclRoleId = $value;
			$aclRole   = $this->fetchById($aclRoleId);
			if ($aclRole) {
				$errMsg   = 'ACL Role has active users, delete users first.';
				$depCount = $aclRole->findDependentRowset('Users_AclRoles')->count();
				if ($depCount > 0) {
					$this->setError('delete', $errMsg, 'action');
					return false;
				}
			}
		}
		**/
		return true;
	}

	/**
	 * Callback to perform before saving new ACL Role. Validates ACL resources.
	 *
	 * @param array
	 * @return boolean True to continue otherwise false
	 */
	public function preProcess() {
		return $this->validateAclResource($this->getData());
	}

	/**
	 * Callback to perform after saving new ACL Role. Saves new ACL resources.
	 *
	 * @param array Form data
	 * @param string ACL Role ID
	 * @return boolean True on success otherwise false
	 */
	public function postProcess() {
		return $this->saveAclResource($this->getData(), $this->getId());
	}

	/**
	 * Validate ACL resources.
	 *
	 * @param array $data Data array of ACL resources to validate
	 * @return boolena True on success otherwise false
	 */
	public function validateAclResource($data) {

		// we'll do some validation here to assure the
		// the resources are valids
		Zend_Loader::loadClass('AclRules');
		$aclRules  = new AclRules;
		$resources = $this->getResources($data);
		foreach ($resources as $resource) {
			// add addtional fields to satisfy
			// the model validation
			$resource['access'     ] = 'allow';
			if (!$aclRules->validate($resource, 'insert')) {
				$errorMsg = 'Invalid ACL resource';
				$this->setError('aclResources', $errorMsg, 'invalid');
				return false;
			}
		}
		return true;
	}

	/**
	 * Save associated ACL resources to an ACL role.
	 *
	 * @param array $data
	 * @param string $recordId ACL role ID
	 * @return boolean True
	 */
	public function saveAclResource($data, $aclRoleId) {

		Zend_Loader::loadClass('AclRules');
		$aclRules  = new AclRules;
		$resources = $this->getResources();
		$deleted   = $aclRules->deleteByAclRoleId($aclRoleId);

		// add addtional fields to satisfy the model validation

		foreach ($resources as $resource) {
			$resource['acl_role_id'] = $aclRoleId;
			$resource['access'     ] = 'allow';
			$aclRules->process($resource, 'insert');
		}

		return true;
	}

	/**
	 * Retrieve array of modules,controller, and action list from param. Used to determine
	 * selected actions for ACL Roles. Form format must be in:
	 *
	 * Format: resource::{moduleName}::{ControllerName}::{actionName}
	 * Sample: resource::admin::users::add
	 *
	 * @param array $data
	 * @return array $results
	 * @access protected
	 */
	protected function getResources($data = null) {

		if (!$this->_selectedResources)  {
			$results = array();
			foreach ($data as $name => $value) {
				if (preg_match('/^resource::/', $name) && $value == "1") {
					$parts = explode('::', $name);
					$results[] = array(
						'module'     => $parts[1],
						'controller' => $parts[2],
						'action'     => $parts[3]
					);
				}
			}
			$this->_selectedResources = $results;
		}

		return $this->_selectedResources;
	}
}