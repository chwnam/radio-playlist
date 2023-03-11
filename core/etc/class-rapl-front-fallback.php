<?php
/**
 * Naran Boilerplate Core
 *
 * etc/class-rapl-front-fallback.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RAPL_Front_Fallback' ) ) {
	class RAPL_Front_Fallback implements RAPL_Front_Module {
		use RAPL_Template_Impl;

		public function display(): void {
			?>
            <style>
                #rapl-fallback-wide {
                    width: 100%;
                    background-color: #eee;
                    font-family: "Ubuntu Mono", monospace;
                    font-size: 12pt;
                    color: #191919;
                    margin-top: 50px;
                    margin-bottom: 50px;
                }

                #rapl-fallback-wide .rapl-fallback-container {
                    width: 640px;
                    margin: 0 auto 0;
                    padding: 20px 10px 20px;
                    background-color: #ddd;
                }

                #rapl-fallback-wide .rapl-fallback-title {
                    width: 100%;
                    text-align: center;
                }

                #rapl-fallback-wide .rapl-fallback-instruction pre.bold {
                    display: inline-block;
                    background-color: #b3b3b3;
                    font-weight: bold;
                }

                #rapl-fallback-wide pre,
                #rapl-fallback-wide code {
                    font-family: "Ubuntu Mono", monospace;
                    font-size: 10pt;
                }

                #rapl-fallback-wide pre.code {
                    background-color: #aaa;
                    padding: 10px 15px;
                    margin-right: 35px;
                }

            </style>

            <div id="rapl-fallback-wide">
                <div class="rapl-fallback-container">
                    <h1 class="rapl-fallback-title">Fallback template</h1>
                    <p>
                        Front module is not properly set up. Please follow the instructions below:
                    </p>
                    <ol class="rapl-fallback-instruction">
                        <li>Open
                            <pre class="bold">`class-rapl-register-theme-support.php`</pre>
                            file.
                        </li>
                        <li>Search for
                            <pre class="bold">`RAPL_Register_Theme_Support::map_front_modules()`</pre>
                            method.
                        </li>
                        <li>Set up the front module, like:
                            <pre class="code"><code>public function map_front_modules( WP_Query $query ) {
    if ( ! $query->is_main_query() ) {
        return;
    }

    $this->remove_action( 'pre_get_posts', 'map_front_modules' );

    $hierarchy = RAPL_Theme_Hierarchy::get_instance();

    // Decide which front module will handle the front scene.
    if ( $hierarchy->is_archive() ) {
        $hierarchy->set_front_module( Archive_Front_Module::class );
    } elseif ( $hierarchy->is_singular() ) {
        $hierarchy->set_front_module( Singular_Front_Module::class );
    }
}</code></pre>
                        </li>
                        <li>You can also override the state of your theme hierarchy instance by action
                            <pre class="bold">'rapl_theme_hierarchy'</pre>.
                            See <pre class="bold">`RAPL_Theme_Hierarchy::__construct()`</pre>.
                        </li>
                    </ol>
                </div>
            </div>
			<?php
		}
	}
} 
