<?php
/**
 * Plugin Name:       라디오 플레이리스트
 * Plugin URI:        https://github.com/chwnam/radio-playlist
 * Description:       Radio Addict 라디오의 플레이리스트를 개인적으로 저장하는 프로젝트.
 * Version:           1.3.1
 * Requires at least: 5.0.0
 * Requires PHP:      8.0
 * Author:            changwoo
 * Author URI:        https://blog.changwoo.pe.kr/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:
 * Text Domain:       rapl
 * Domain Path:       /languages
 * CPBN Version:      1.6.1
 */

/* ABSPATH check */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/vendor/autoload.php';

const RAPL_MAIN_FILE = __FILE__;
const RAPL_VERSION   = '1.3.1';
const RAPL_PRIORITY  = 8000;

rapl();
