<?php
if(PHP_SAPI!=='cli'){exit;} require getcwd().'/wp-load.php';
$apply=in_array('--apply',$argv,true);
$news=array('11'=>79,'13'=>80,'14'=>81,'15'=>82,'16'=>83,'17'=>84,'18'=>85,'19'=>86,'20'=>88,'21'=>89);
$ids=get_posts(array('post_type'=>array('page','post','cancer','treatment','doctor','patient_story','special_topic'),'post_status'=>array('publish','draft'),'posts_per_page'=>-1,'fields'=>'ids','suppress_filters'=>true));
global $wpdb; $ids=$wpdb->get_col("SELECT ID FROM {$wpdb->posts} WHERE post_type IN ('page','post','cancer','treatment','doctor','patient_story','special_topic') AND post_status IN ('publish','draft')");
$stats=array('posts_changed'=>0,'links_changed'=>0);
foreach($ids as $id){$p=get_post($id);$lang=function_exists('pll_get_post_language')?pll_get_post_language($id,'slug'):'vi';$lang=$lang?:'vi';$count=0;
	$content=preg_replace_callback('#href=(["\'])([^"\']+)\1#i',function($m)use($lang,$news,&$count){$raw=html_entity_decode($m[2],ENT_QUOTES,'UTF-8');$target='';
		if(preg_match('#^(?:\.\./\.\./)?liver-cancer/index\.html$#',$raw)){$tid=function_exists('pll_get_post')?pll_get_post(128,$lang):128;$target=$tid?get_permalink($tid):'';}
		elseif('../../../index.html'===$raw){$target=function_exists('pll_home_url')?pll_home_url($lang):home_url('/'.$lang.'/');}
		elseif(preg_match('#^(?:news/)?([0-9]+)/index\.html$#',$raw,$n)&&isset($news[$n[1]])){$tid=function_exists('pll_get_post')?pll_get_post($news[$n[1]],$lang):$news[$n[1]];$target=$tid?get_permalink($tid):'';}
		if(!$target){return $m[0];}$count++;return 'href='.$m[1].esc_url($target).$m[1];
	},$p->post_content);
	if(!$count){continue;}$stats['posts_changed']++;$stats['links_changed']+=$count;echo sprintf("%s #%d [%s] links=%d\n",$apply?'APPLY':'DRY',$id,$lang,$count);
	if($apply){if(!metadata_exists('post',$id,'_uc_pre_link_normalization_20261002')){add_post_meta($id,'_uc_pre_link_normalization_20261002',$p->post_content,true);}wp_update_post(array('ID'=>$id,'post_content'=>wp_slash($content)));}
}
echo wp_json_encode(array('mode'=>$apply?'apply':'dry-run','stats'=>$stats),JSON_PRETTY_PRINT).PHP_EOL;
