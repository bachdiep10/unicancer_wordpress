<?php
if(!defined('ABSPATH')) exit(1);
$target=get_post(128);
if(!$target || $target->post_type!=='special_topic') throw new RuntimeException('Wrong target');
$html=$target->post_content;
add_post_meta(128,'_unicancer_pre_special_layout_20261003',$html,true);
function uc_balanced_end($html,$start){
 preg_match_all('~</?div\b[^>]*>~i',substr($html,$start),$tags,PREG_OFFSET_CAPTURE);
 $depth=0; foreach($tags[0] as $tag){$depth+=strpos($tag[0],'</')===0?-1:1;if($depth===0)return $start+$tag[1]+strlen($tag[0]);}
 throw new RuntimeException('Unbalanced section');
}
function uc_rebuild_cards($fragment,$feature=false){
 $fragment=preg_replace('~</?p\b[^>]*>|&nbsp;~i','',$fragment);
 $dom=new DOMDocument();libxml_use_internal_errors(true);
 $dom->loadHTML('<?xml encoding="UTF-8"><div id="uc-root">'.$fragment.'</div>',LIBXML_HTML_NOIMPLIED|LIBXML_HTML_NODEFDTD);
 $root=$dom->getElementById('uc-root')->firstChild;
 while($root && $root->nodeType!==XML_ELEMENT_NODE)$root=$root->nextSibling;
 $nodes=[];foreach($root->childNodes as $n)if($n->nodeType===XML_ELEMENT_NODE)$nodes[]=$n;
 $doctor=isset($nodes[0]) && strpos($nodes[0]->getAttribute('class'),'rounded-full')!==false;
 $patient=isset($nodes[0]) && strpos($nodes[0]->getAttribute('class'),'h-48')!==false;
 $step=$doctor||$feature?2:3;
 if(count($nodes)%$step)throw new RuntimeException('Unexpected card count '.count($nodes));
 $out='';
 for($i=0;$i<count($nodes);$i+=$step){
  $first=$nodes[$i];$link='';
  if($first->tagName==='a'){$link=unicancer_localize_internal_url($first->getAttribute('href'));}
  if($doctor||$patient){
   $style=html_entity_decode($first->getAttribute('style'),ENT_QUOTES,'UTF-8');
   preg_match('~url\([\x27\x22]?(.*?)[\x27\x22]?\)~',$style,$m);
   $src=trim($m[1]??'',"'\"");if(!$src)throw new RuntimeException('Missing original image');
   $name=trim($nodes[$i+1]->textContent);
   if($doctor){$q=get_posts(['post_type'=>'doctor','posts_per_page'=>-1]);foreach($q as $p){if(stripos($p->post_title,trim(explode('\n',$name)[0]))!==false){$link=get_permalink($p);break;}}}
   $image='<img loading="lazy" src="'.esc_url($src).'" alt="'.esc_attr(mb_substr($name,0,80)).'" style="'.($doctor?'width:120px;height:120px;border-radius:50%;object-fit:cover;object-position:top;flex-shrink:0':'width:100%;height:192px;object-fit:cover;object-position:top').'">';
  }else{ $image='';foreach($first->childNodes as $n)$image.=$dom->saveHTML($n); }
  $tag=$link?'a':'div';
  $style=$feature?'flex:1;min-width:0;display:flex;align-items:center;padding:24px;gap:16px':($doctor?'flex:0 0 380px;display:flex;align-items:center;padding:20px;gap:12px':($patient?'flex:0 0 344px;overflow:hidden;padding-bottom:20px':'flex:0 0 280px;padding:20px;text-align:center'));
  $out.='<'.$tag.($link?' href="'.esc_url($link).'"':'').' class="uc-special-card" style="'.$style.';background:white;border-radius:16px;border:1px solid #d7eaff;color:inherit;text-decoration:none">'.$image;
  for($j=1;$j<$step;$j++)$out.=$dom->saveHTML($nodes[$i+$j]);
  $out.='</'.$tag.'>';
 }
 $class=$feature?'uc-special-feature':'flex gap-4 move-target uc-special-track';
 return '<div class="'.$class.'">'.$out.'</div>';
}
$marker='<div class="flex flex-col lg:flex-row gap-4 mb-6 max-w-240 mx-auto">';
$start=strpos($html,$marker);if($start===false)throw new RuntimeException('Feature missing');
$end=uc_balanced_end($html,$start);$html=substr_replace($html,uc_rebuild_cards(substr($html,$start,$end-$start),true),$start,$end-$start);
$offset=0;$count=0;
while(($start=strpos($html,'<div class="flex gap-4 move-target">',$offset))!==false){
 $end=uc_balanced_end($html,$start);$replacement=uc_rebuild_cards(substr($html,$start,$end-$start));
 $html=substr_replace($html,$replacement,$start,$end-$start);$offset=$start+strlen($replacement);$count++;
}
if($count!==3)throw new RuntimeException('Expected 3 tracks, got '.$count);
kses_remove_filters();$result=wp_update_post(['ID'=>128,'post_content'=>$html],true);kses_init_filters();
if(is_wp_error($result))throw new RuntimeException($result->get_error_message());
echo "Repaired 3 tracks and feature cards, page 128\n";
