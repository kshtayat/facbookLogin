<h1>Welcome <?= $firstName.' '.$lastName ?></h1>
<br />
<br />
<?php
$this->widget('ext.widget.googleMapWidget',array('userAddress'=>$hometown));
?>