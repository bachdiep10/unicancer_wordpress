<?php
/**
 * Original site CDN images - mapped to placeholder usage
 * All images from https://huan-ya.oss-ap-southeast-1.aliyuncs.com/
 *
 * @package UNI_ASIA
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Original site CDN URL base
 */
function uniasia_cdn_base() {
	return 'https://huan-ya.oss-ap-southeast-1.aliyuncs.com/';
}

/**
 * Get a CDN image URL
 */
function uniasia_cdn( $path ) {
	return uniasia_cdn_base() . ltrim( $path, '/' );
}

/**
 * Hero images
 */
function uniasia_hero_bg() {
	return uniasia_cdn( 'frontend/index/bg.jpg' );
}

/**
 * Logo URLs
 */
function uniasia_logo() {
	return uniasia_cdn( 'frontend/logo.png' );
}

function uniasia_logo_white() {
	return uniasia_cdn( 'frontend/logo-white.png' );
}

function uniasia_logo_short_blue() {
	return uniasia_cdn( 'frontend/layout/logo-short-b.png' );
}

function uniasia_wata_blue() {
	return uniasia_cdn( 'frontend/layout/wata-g.png' );
}

function uniasia_wata_white() {
	return uniasia_cdn( 'frontend/layout/wata-white.png' );
}

/**
 * MDT Doctor photos
 */
function uniasia_doctor_photo( $index = 0 ) {
	$photos = array(
		'media/ms5lfj6e-5e562808.jpg', // Liao Zhengyin
		'media/ms5lbaro-e1940bd8.jpg', // Zhang Jinshan
		'media/ms5l9k9v-5497e8ed.jpg', // Xiao Yueyong
		'media/ms5lfsaj-b7ae816b.jpg', // Hu Xiaokun
		'media/ms5lehcv-647796d5.jpg', // Backup
		'media/ms5le62r-6d69ae96.jpg', // Backup
	);
	$key = isset( $photos[ $index ] ) ? $photos[ $index ] : $photos[0];
	return uniasia_cdn( $key );
}

/**
 * Cancer Type images
 */
function uniasia_cancer_image( $slug = '' ) {
	$map = array(
		'liver-cancer'     => 'media/mreretiq-76b2e192.jpg',
		'lung-cancer'      => 'media/mr37w1jn-4c0d8553.jpg',
		'pancreatic-cancer'=> 'media/mr1tz4v6-b8951a7b.jpg',
		'breast-cancer'    => 'media/ms3eqalv-53930cb8.jpg',
		'cervical-cancer'  => 'frontend/index/treatment1.jpg',
		'colon-rectal'     => 'frontend/index/treatment1.jpg',
	);
	if ( isset( $map[ $slug ] ) ) {
		return uniasia_cdn( $map[ $slug ] );
	}
	return uniasia_cdn( 'frontend/index/treatment1.jpg' );
}

/**
 * Technology icons (cancer treatment techniques)
 */
function uniasia_tech_icon( $slug = '' ) {
	$map = array(
		'ire'              => 'media/mpmedcv6-760e0231.png',
		'nano-knife'       => 'media/mpmedcv6-760e0231.png',
		'mwa'              => 'media/mpyto964-7f870b48.png',
		'microwave'        => 'media/mpyto964-7f870b48.png',
		'rfa'              => 'media/mpmeqfku-3bccd822.png',
		'radiofrequency'   => 'media/mpmeqfku-3bccd822.png',
		'cryo'             => 'media/mpme7nvh-b00e3e15.png',
		'cryoablation'     => 'media/mpme7nvh-b00e3e15.png',
		'tace'             => 'media/mpmdt7b2-70c79622.png',
		'immunotherapy'    => 'media/mpmf0u7n-50f05703.png',
		'sbrt'             => 'media/mpmf2o7o-1066a5a0.png',
		'radiation'        => 'media/mpmf4f80-baef9f4b.png',
		'chemotherapy'     => 'media/mpmf8p4f-17dd24bf.png',
		'general'          => 'media/mpnfcdao-46357914.png',
	);
	if ( isset( $map[ $slug ] ) ) {
		return uniasia_cdn( $map[ $slug ] );
	}
	return uniasia_cdn( 'media/mpmdt7b2-70c79622.png' );
}

/**
 * Why-choose / service icons
 */
function uniasia_service_icon( $index = 1 ) {
	$icons = array(
		1 => 'frontend/index/s1.png',
		2 => 'frontend/index/s2.png',
		3 => 'frontend/index/s3.png',
		4 => 'frontend/index/s4.png',
		5 => 'frontend/index/s5.png',
	);
	$key = isset( $icons[ $index ] ) ? $icons[ $index ] : $icons[1];
	return uniasia_cdn( $key );
}

/**
 * Treatment section image
 */
function uniasia_treatment_image() {
	return uniasia_cdn( 'frontend/index/treatment1.jpg' );
}

function uniasia_service_illustration() {
	return uniasia_cdn( 'frontend/index/service.png' );
}

/**
 * Patient Story / International Guide images
 */
function uniasia_patient_image( $index = 0 ) {
	$images = array(
		'media/ms5lfsaj-b7ae816b.jpg',
		'media/ms5lfj6e-5e562808.jpg',
		'media/ms5lbaro-e1940bd8.jpg',
		'media/ms5l9k9v-5497e8ed.jpg',
	);
	$key = isset( $images[ $index ] ) ? $images[ $index ] : $images[0];
	return uniasia_cdn( $key );
}

/**
 * Footer image
 */
function uniasia_footer_image() {
	return uniasia_cdn( 'frontend/footer/wa-en.jpg' );
}