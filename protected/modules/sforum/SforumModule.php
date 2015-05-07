<?php
// 1845 488 10

class SforumModule extends CWebModule
{
	/**
	 * @var string the default layout for the views.
	 */
	public $layout='//layouts/main';
	
	public $version = '1.0';
	
	public $publicRead = true;
	public $ananymousComments = true;
	public $commentsPerPage = 30;
	public $autoApproveComments = false;
	public $topicsPerPage = 30;
	public $emailAlerts = true;
	
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'sforum.models.*',
			'sforum.components.*',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}
	
	private $_assetsUrl;
 
    public function getAssetsUrl()
    {
        if ($this->_assetsUrl === null)
            $this->_assetsUrl = Yii::app()->getAssetManager()->publish( Yii::getPathOfAlias('sforum.assets'), false, -1, true );
        return $this->_assetsUrl;
    }
}
