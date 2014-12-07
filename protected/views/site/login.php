<?php
/* @var $this SiteController */
$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
	'Login',
);
?>

<h1>Login</h1>
<div style="text-align:center">
	<button onclick="fbLoginPage()">login</button>
</div>
<div id="status"></div>
<form id="fbLogin" method="post" action="" >
	<input type="hidden" name="email" value = ""/>
	<input type="hidden" name="hometown" value = ""/>
	<input type="hidden" name="firstName" value = ""/>
	<input type="hidden" name="lastName" value = ""/>
	<input type="hidden" name="fbId" value = ""/>
</form>

<?php
	$javaScript = <<<JS

	var fbInitialized = 0;
	function fbLoginPage() {
		fbInitialized = 0;
		window.fbAsyncInit = function() {
			FB.init({
				appId      : $FbAppId,
				xfbml      : true,  // parse social plugins on this page
				version    : 'v2.2'
			});
			fbInitialized = 1;
		};
		var e = document.createElement('script');
		e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
		e.async = true;
		document.getElementById('fb-root').appendChild(e);
		FBwait();
	}
	function FBwait() {
		if (typeof FB == 'undefined') {
				window.setTimeout(FBwait, 300);
		} else {
			validateFbInit();
		}
	}
	function validateFbInit() {
		if (fbInitialized == 0) {
			window.setTimeout(validateFbInit, 300);
		} else {
			FBlogin();
		}
	}
	function FBlogin() {
		FB.login(function(response) {
			if (response.status=="connected") {
				var FBCookie= "access_token="+response.authResponse.accessToken+"&uid="+response.authResponse.userID;
				document.cookie = "fbs_<?=$FbAppId?>"+encodeURIComponent(FBCookie)+"; path=/";
				// user is logged in and granted some permissions.
				FB.api("/me", function(response) {
					if (response && !response.error) {

						$('form>input[name=email]').val(response.email);
						if (typeof response.hometown == 'undefined')
							response.hometown= {name:'Jordan'};
						$('form>input[name=hometown]').val(response.hometown.name);
						$('form>input[name=firstName]').val(response.first_name);
						$('form>input[name=lastName]').val(response.last_name);

						$('form>input[name=fbId]').val(response.id);
						$('form').submit();
					}
				});
			}
		}, {scope:'email,offline_access,user_birthday,user_education_history,user_hometown,user_location,user_about_me,user_work_history,friends_work_history,friends_education_history,publish_stream'});
	}
JS;
	Yii::app()->clientScript->registerScript('FBFunctions', $javaScript, CClientScript::POS_END);
 	Yii::app()->clientScript->registerCoreScript('jquery');
?>