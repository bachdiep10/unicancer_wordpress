<?php
/** Localize patient-story slugs from their original legacy path, never from Yoast keywords. */
if ( PHP_SAPI !== 'cli' ) { exit( "CLI only.\n" ); }
require getcwd() . '/wp-load.php';
$apply = in_array( '--apply', $argv, true );

$legacy = array(
	97=>'bladder-cancer-microwave-ablation-patient-w1', 98=>'bladder-cancer-tace-treatment-patient-cd',
	99=>'breast-cancer-tace-treatment-patient-xl', 100=>'cervical-cancer-tace-treatment-patient-rsqx',
	101=>'colorectal-cancer-tace-treatment-patient-tyf', 102=>'colorectal-cancer-tace-treatment-patient-zgd',
	103=>'liver-cancer-nanoknife-wknife-treatment-patient-gs', 104=>'liver-cancer-tace-treatment-patient-jnn',
	105=>'liver-cancer-tace-treatment-patient-kdy', 106=>'liver-cancer-tace-treatment-patient-lhy',
	107=>'liver-cancer-tace-treatment-patient-lw', 108=>'liver-cancer-tace-treatment-patient-lxf',
	109=>'liver-cancer-tace-treatment-patient-wxl', 110=>'liver-cancer-tace-treatment-patient-zxl',
	111=>'liver-cancer-vertebral-metastasis-vertebroplasty-patient-ak',
	112=>'lung-cancer-cryoablation-treatment-patient-zgs',
	113=>'lung-cancer-intra-arterial-therapy-iodine-125-seed-implantation-vertebroplasty-treatment-patient-wdh',
	114=>'lung-cancer-liver-argon-helium-cryoablation-treatment-bb',
	115=>'lung-cancer-tace-treatment-patient-rvs', 116=>'lung-cancer-tace-treatment-patient-wxc',
	117=>'lung-liver-cancer-cryoablation-treatment-patient-ly',
	118=>'nasopharyngeal-cancer-iodine-125-seed-implantation-patient-aml',
	119=>'nasopharyngeal-cancer-tace-treatment-patient-ns',
	120=>'pancreatic-cancer-nanoknife-ablation-patient-gd',
	121=>'pancreatic-cancer-nanoknife-ablation-patient-syz',
	122=>'pancreatic-cancer-nanoknife-treatment-patient-bgp',
	123=>'prostate-cancer-tace-treatment-patient-hpg',
	124=>'stomach-cancer-tace-immunotherapy-treatment-rsm',
	125=>'stomach-cancer-tace-treatment-patient-sy', 126=>'stomach-cancer-tace-treatment-patient-tls',
);

function uc_slug_translate( $slug, $lang ) {
	if ( 'vi' === $lang ) { return $slug; }
	if ( 'en' === $lang ) {
		$slug = str_replace( array('-treatment-patient-','-patient-','-treatment-'), array('-patient-','-case-','-case-'), $slug );
		return sanitize_title( $slug );
	}
	$maps = array(
		'id'=>array(
			'nasopharyngeal-cancer'=>'kanker-nasofaring','pancreatic-cancer'=>'kanker-pankreas','colorectal-cancer'=>'kanker-kolorektal',
			'cervical-cancer'=>'kanker-serviks','prostate-cancer'=>'kanker-prostat','bladder-cancer'=>'kanker-kandung-kemih',
			'breast-cancer'=>'kanker-payudara','stomach-cancer'=>'kanker-lambung','liver-cancer'=>'kanker-hati','lung-cancer'=>'kanker-paru',
			'microwave-ablation'=>'ablasi-gelombang-mikro','argon-helium-cryoablation'=>'krioablasi-argon-helium',
			'cryoablation'=>'krioablasi','intra-arterial-therapy'=>'terapi-intra-arteri','iodine-125-seed-implantation'=>'implantasi-biji-iodium-125',
			'vertebral-metastasis'=>'metastasis-tulang-belakang','vertebroplasty'=>'vertebroplasti','immunotherapy'=>'imunoterapi',
			'ablation'=>'ablasi','treatment'=>'pengobatan','patient'=>'pasien',
		),
		'zh-cn'=>array(
			'nasopharyngeal-cancer'=>'鼻咽癌','pancreatic-cancer'=>'胰腺癌','colorectal-cancer'=>'结直肠癌','cervical-cancer'=>'宫颈癌',
			'prostate-cancer'=>'前列腺癌','bladder-cancer'=>'膀胱癌','breast-cancer'=>'乳腺癌','stomach-cancer'=>'胃癌','liver-cancer'=>'肝癌','lung-cancer'=>'肺癌',
			'microwave-ablation'=>'微波消融','argon-helium-cryoablation'=>'氩氦刀冷冻消融','cryoablation'=>'冷冻消融',
			'intra-arterial-therapy'=>'动脉介入治疗','iodine-125-seed-implantation'=>'碘125粒子植入','vertebral-metastasis'=>'椎体转移',
			'vertebroplasty'=>'椎体成形术','immunotherapy'=>'免疫治疗','nanoknife'=>'纳米刀','wknife'=>'陡脉冲刀',
			'ablation'=>'消融','treatment'=>'治疗','patient'=>'患者',
		),
	);
	return sanitize_title( strtr( $slug, $maps[ $lang ] ?? array() ) );
}

$planned = array();
foreach ( $legacy as $vi_id => $source_slug ) {
	$translations = function_exists('pll_get_post_translations') ? pll_get_post_translations($vi_id) : array('vi'=>$vi_id);
	foreach ( $translations as $lang=>$id ) {
		if ( ! in_array($lang,array('vi','en','id','zh-cn'),true) ) { continue; }
		$planned[$id] = array('lang'=>$lang,'slug'=>uc_slug_translate($source_slug,$lang),'vi_id'=>$vi_id);
	}
}
$duplicates = array();
foreach ( $planned as $id=>$item ) { $duplicates[$item['slug']][]=$id; }
$duplicates = array_filter($duplicates,function($ids){return count($ids)>1;});
if ( $duplicates ) { echo wp_json_encode(array('error'=>'duplicate planned slugs','duplicates'=>$duplicates),JSON_PRETTY_PRINT).PHP_EOL; exit(2); }

$stats=array('planned'=>count($planned),'changed'=>0,'restored'=>0,'failed'=>0);
foreach ( $planned as $id=>$item ) {
	$post=get_post($id); if(!$post||$post->post_name===$item['slug']){continue;}
	$stats['changed']++;
	echo sprintf("%s #%d [%s] %s => %s\n",$apply?'APPLY':'DRY',$id,$item['lang'],$post->post_name,$item['slug']);
	if(!$apply){continue;}
	if(!metadata_exists('post',$id,'_uc_pre_localized_slug_20261002')){add_post_meta($id,'_uc_pre_localized_slug_20261002',$post->post_name,true);}
	delete_post_meta($id,'_wp_old_slug',$item['slug']);
	wp_update_post(array('ID'=>$id,'post_name'=>$item['slug']));
	$actual=get_post_field('post_name',$id);
	if($actual===$item['slug']){$stats['restored']++;}else{$stats['failed']++;echo "  FAILED actual=$actual\n";}
}
if($apply){flush_rewrite_rules(false);}
echo wp_json_encode(array('mode'=>$apply?'apply':'dry-run','stats'=>$stats),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT).PHP_EOL;
