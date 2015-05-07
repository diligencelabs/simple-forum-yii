<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="container" id="page">

	<div id="header">
		<div id="logo"><a href="http://www.diligencelabs.com/"><img src="http://www.diligenceapps.com/themes/professional_theme/logo.png" height="30" align="bottom" alt="diligencelabs logo" title="Diligence Labs" /></a> <?php echo CHtml::encode(Yii::app()->name); ?></div>
	</div><!-- header -->

	<div id="mainmenu">
		<?php $this->widget('zii.widgets.CMenu',array(
			'items'=>array(
				array('label'=>'Home', 'url'=>array('/site/index')),
				array('label'=>'Installation', 'url'=>array('/site/installation')),
				array('label'=>'Forums', 'url'=>array('/sforum/default/index')),
				array('label'=>'About', 'url'=>'http://www.diligencelabs.com/aboutus.php'),
				array('label'=>'Contact', 'url'=>array('/site/contact')),
				array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
				array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
			),
		)); ?>
	</div><!-- mainmenu -->

	<?php $this->widget('zii.widgets.CBreadcrumbs', array(
		'links'=>$this->breadcrumbs,
	)); ?><!-- breadcrumbs -->
	
	<?php if(!Yii::app()->user->isGuest && Yii::app()->user->isAdmin): ?>
	<div class="two-column">
		<div class="span-18">
			<div id="content">
				<?php
				Yii::import('sforum.components.SforumUtils');
				echo SforumUtils::showFlash();
				?>
				<?php echo $content; ?>
			</div><!-- content -->
		</div>
		<div class="span-6 last">
			<div id="sidebar">
				<?php $this->widget('sforum.components.UserMenu'); ?>
			</div><!-- sidebar -->
		</div>
	</div>
	<?php else: ?>
	<div id="contenst" class="span-22 prepend-1 append-1">
		<?php
		Yii::import('sforum.components.SforumUtils');
		echo SforumUtils::showFlash();
		?>
		<?php echo $content; ?>
	</div>
	<?php endif; ?>
	
	
	<script type="text/javascript">

	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-34530703-1']);
	  _gaq.push(['_setDomainName', 'diligenceapps.com']);
	  _gaq.push(['_trackPageview']);

	  (function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();

	</script>
	
	
	<div id="footer" class="span-24">
		Copyright &copy; <?php echo date('Y'); ?> <a href="http://www.diligencelabs.com/">DiligenceLabs</a>.<br/>
		<?php echo Yii::powered(); ?>
	</div><!-- footer -->

</div><!-- page -->


</body>
</html>