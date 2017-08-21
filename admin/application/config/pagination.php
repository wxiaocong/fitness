<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//分页配置
//$config['per_page'] = 10;
// $config['num_links'] = 4;
$config ['per_page'] = pageSize;
$config['first_link'] = false;
$config['last_link'] = false;
$config['use_page_numbers'] = TRUE;
//当前页
$config['cur_tag_open'] = '<em class="page_selected"><a href="#">';
$config['cur_tag_close'] = '</a></em>';
//数字链接
$config['num_tag_open'] = '<em>';
$config['num_tag_close'] = '</em>';
//上一页
$config['prev_link'] = '<em>◀</em>上一页';
$config['prev_tag_open'] = '<span class="page_prev">';
$config['prev_tag_close'] = '</span>';
//下一页
$config['next_link'] = '下一页<em>▶</em>';
$config['next_tag_open'] = '<span class="page_next">';
$config['next_tag_close'] = '</a></span>';
