<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title><?php echo isset($this->_vars->share['title'])?$this->_vars->share['title']:'热炼健身' ; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=2.0" />
	<meta name="format-detection" content="telephone=no"> 
	<meta http-equiv="Expires" content="0">
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="Cache-control" content="no-cache">
	<meta http-equiv="Cache" content="no-cache">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ; ?>static/css/common.css"/>
    <?php if(isset($this->_vars->headerCss) && !empty($this->_vars->headerCss) ) {  ?>
    <?php foreach($this->_vars->headerCss as $this->_vars->key => $this->_vars->value ) {  ?>
    <link href="<?php echo base_url() ; ?>static/css/<?php echo $this->_vars->value ; ?>" rel="stylesheet">
    <?php } ?>
    <?php } ?>
</head>
<body>