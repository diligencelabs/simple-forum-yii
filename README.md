# Simple Forum Yii Extension


## Prerequisites

- PHP 5.4</b> (or greater) required.

- Yii Framework 1.1.10</b> (or greater) required.


User component must be enabled in your config.  i.e.  **config/main.php**

```php
	return array(
		//........
		"components" => array(
			//........
			"user" => array(
				"class" => "WebUser",
			),
			//........
		),
		//...........
	);
```


And your "WebUser" class must have following methods and variables.  i.e.  **protected/components/WebUser.php**
	
Here I am assuming that you have a model named "User" and that tells whether a user is admin or not.



```php
	// WebUser class
	class WebUser extends CWebUser {
		// DB User record
		private $_user;
		
		/**
		* check whether logged in user a admin
		*/
		public function getIsAdmin() {
			if(Yii::app()->user->isGuest) {
				return false;
			}
			
			if( !$this->_user && Yii::app()->user->id ) {
				$this->_user = \User::model()->find(\'id=:id\', 
				              array(\':id\' => Yii::app()->user->id));
			}
			
			if(!$this->_user)
				return false;
			
			if($this->_user->user_type == \'A\')
				return true;
			
			return false;
		}
	}
```

## Installtion

- Download the Simple Forum.

- Unzip the file and copy "protected/modules/sforum"  directory into your yii application's "modules" directory. after this step you must see "modules/sforum" directory in your application.

- Run the <b>protected/data/schema.mysql.sql</b> against your database. You will see the list of new tables created.

- Enable this module on the configuration file.

```php
	//............
	return array(
		//........
		"modules" => array(
			//........
			"sforum" => array(
				/* Allow this forum to the public, so that anyone can read this forum. */
				"publicRead" => true,
				
				/* Allow write comments to the public. 
				          so that anyone can comment on any topic. */
				"ananymousComments" => true,
				
				/* Do you want all comments to be auto approved ?  
				      set it to false so that Admin can approve the comments. */
				"autoApproveComments" => false,
				
				/* How many comments should be displayed per page */
				"commentsPerPage" => 30,
				
				/* How many topics should be displayed per page */
				"topicsPerPage" => 30,
				
				/* Alert topic owner and admin users when a new comment/topic created. */
				"emailAlerts" => true,
			),
			//........
		),
		//...........
	);
	//............
	');
```
	
	
	
(Following is optional)

If you want to enable email alerts, you should have to install below mailer extension from the yii extension site <a href="http://www.yiiframework.com/extension/mailer/">http://www.yiiframework.com/extension/mailer/</a>
