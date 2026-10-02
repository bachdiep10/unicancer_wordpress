<?php
/** Resolve imported ../.../index.html links to language-aware WordPress permalinks. */
if ( PHP_SAPI !== 'cli' ) { exit( "CLI only.\n" ); }
require getcwd().'/wp-load.php';
global $wpdb;
$apply=in_array('--apply',$argv,true);
$types=array('page','post','cancer','treatment','doctor','patient_story','special_topic');
$routes=array('post'=>'news','cancer'=>'cancers','treatment'=>'treatments','doctor'=>'doctors','patient_story'=>'patient-stories','special_topic'=>'special-topics');
$archive_sources=array('cancers'=>180,'treatments'=>188,'doctors'=>182,'patient-stories'=>184,'news'=>183,'about-us'=>7,'contact-us'=>9,'services'=>14);
$ids=$wpdb->get_col("SELECT ID FROM {$wpdb->posts} WHERE post_type IN ('page','post','cancer','treatment','doctor','patient_story','special_topic') AND post_status IN ('publish','draft') ORDER BY ID");
$alias=array(); $global_alias=array();
foreach($ids as $id){
	$p=get_post($id); $route=$routes[$p->post_type]??'';
	$vi=function_exists('pll_get_post')?(int)pll_get_post($id,'vi'):(int)$id; if(!$vi){$vi=(int)$id;}
	$names=array($p->post_name);
	foreach(array('_wp_old_slug','_uc_pre_seo_architecture_20261002_post_name','_uc_pre_localized_slug_20261002') as $key){$names=array_merge($names,get_post_meta($id,$key,false));}
	foreach(array_unique(array_filter(array_map('sanitize_title',$names))) as $name){
		$alias[$route.'|'.$name][$vi]=true; $global_alias[$name][$vi]=true;
	}
}

function uc_link_normalize_path($base,$relative){
	$relative=preg_replace('~[?#].*$~','',$relative);
	if('/'===substr($relative,0,1)){$parts=array();}else{$parts=explode('/',trim($base,'/'));}
	foreach(explode('/',$relative) as $part){if(''===$part||'.'===$part){continue;} if('..'===$part){array_pop($parts);}else{$parts[]=$part;}}
	return $parts;
}
function uc_link_target($raw,$post,$lang,$routes,$archive_sources,$alias,$global_alias){
	if(!$raw||preg_match('~^(?:\#|mailto:|tel:|javascript:|data:)~i',$raw)){return '';}
	$fragment=(string)(parse_url($raw,PHP_URL_FRAGMENT)??'');
	$host=(string)(parse_url($raw,PHP_URL_HOST)??'');
	$path=(string)(parse_url($raw,PHP_URL_PATH)??'');
	$site_host=(string)parse_url(home_url('/'),PHP_URL_HOST);
	$malformed_host=$host&&false===strpos($host,'.');
	if($host&&$host!==$site_host&&!$malformed_host){return '';}
	if($malformed_host){$parts=array($host);}
	else{
		$route=$routes[$post->post_type]??'';
		$base=('page'===$post->post_type)?'':$route.'/'.$post->post_name;
		$parts=uc_link_normalize_path($base,$path?:$raw);
	}
	$parts=array_values(array_filter($parts,function($p){return $p!==''&&strtolower($p)!=='index.html';}));
	if(isset($parts[0])&&in_array($parts[0],array('vi','en','id','zh-cn'),true)){array_shift($parts);}
	$route=''; $slug='';
	if(isset($parts[0])&&in_array($parts[0],array('cancers','treatments','doctors','patient-stories','news','special-topics'),true)){$route=array_shift($parts);}
	if($parts){$slug=sanitize_title(end($parts));}
	if(!$slug&&$route&&isset($archive_sources[$route])){$source=$archive_sources[$route]; $target=function_exists('pll_get_post')?pll_get_post($source,$lang):$source; return $target?get_permalink($target).($fragment?'#'.$fragment:''):'';}
	if(!$route&&$slug&&isset($archive_sources[$slug])){$source=$archive_sources[$slug]; $target=function_exists('pll_get_post')?pll_get_post($source,$lang):$source; return $target?get_permalink($target).($fragment?'#'.$fragment:''):'';}
	$candidates=$route&&isset($alias[$route.'|'.$slug])?array_keys($alias[$route.'|'.$slug]):(isset($global_alias[$slug])?array_keys($global_alias[$slug]):array());
	if(count($candidates)!==1){return '';}
	$source=(int)$candidates[0]; $target=function_exists('pll_get_post')?pll_get_post($source,$lang):$source;
	if(!$target||'publish'!==get_post_status($target)){return '';}
	return get_permalink($target).($fragment?'#'.$fragment:'');
}

$stats=array('posts_scanned'=>0,'posts_changed'=>0,'links_changed'=>0,'unresolved'=>0); $unresolved=array();
foreach($ids as $id){
	$post=get_post($id); if(!$post||false===stripos($post->post_content,'index.html')){continue;}
	$lang=function_exists('pll_get_post_language')?pll_get_post_language($id,'slug'):'vi'; $lang=$lang?:'vi'; $changed=0;
	$content=preg_replace_callback('#href=(["\'])([^"\']+)\1#i',function($m)use($post,$lang,$routes,$archive_sources,$alias,$global_alias,&$changed,&$unresolved){
		$raw=html_entity_decode($m[2],ENT_QUOTES,'UTF-8');
		if(false===stripos($raw,'index.html')&&!preg_match('#^https?://[^./]+/#i',$raw)){return $m[0];}
		$target=uc_link_target($raw,$post,$lang,$routes,$archive_sources,$alias,$global_alias);
		if(!$target){$unresolved[$post->ID.'|'.$raw]=array('post_id'=>$post->ID,'lang'=>$lang,'url'=>$raw);return $m[0];}
		$changed++; return 'href='.$m[1].esc_url($target).$m[1];
	},$post->post_content);
	$stats['posts_scanned']++;
	if(!$changed){continue;}
	$stats['posts_changed']++; $stats['links_changed']+=$changed;
	echo sprintf("%s #%d [%s] links=%d\n",$apply?'APPLY':'DRY',$id,$lang,$changed);
	if($apply){if(!metadata_exists('post',$id,'_uc_pre_link_normalization_20261002')){add_post_meta($id,'_uc_pre_link_normalization_20261002',$post->post_content,true);} wp_update_post(array('ID'=>$id,'post_content'=>wp_slash($content)));}
}
$stats['unresolved']=count($unresolved);
echo wp_json_encode(array('mode'=>$apply?'apply':'dry-run','stats'=>$stats,'unresolved'=>array_values($unresolved)),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT).PHP_EOL;
