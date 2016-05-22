<?php
/**
 * SkinTemplate class for the Handbuchio2 skin
 *
 * @ingroup Skins
 */
class SkinHandbuchio2 extends SkinTemplate {
	public $skinname = 'handbuchio2', $stylename = 'Handbuchio2',
		$template = 'Handbuchio2Template', $useHeadElement = true;

	/**
	 * Add CSS via ResourceLoader
	 *
	 * @param $out OutputPage
	 */
	public function initPage( OutputPage $out ) {

		$out->addMeta( 'viewport', 'width=device-width, initial-scale=1.0' );

		$out->addModuleStyles( array(
			'mediawiki.skinning.interface',
			'mediawiki.skinning.content.externallinks',
			'skins.handbuchio2'
		) );
		$out->addModules( array(
			'skins.handbuchio2.js'
		) );
	}

	/**
	 * @param $out OutputPage
	 */
	function setupSkinUserCss( OutputPage $out ) {
		parent::setupSkinUserCss( $out );
	}
}
