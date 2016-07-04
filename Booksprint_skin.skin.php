<?php
/**
 * SkinTemplate class for the Booksprint_skin skin
 *
 * @ingroup Skins
 */
class SkinBooksprint_skin extends SkinTemplate {
	public $skinname = 'booksprint_skin', $stylename = 'Booksprint_skin',
		$template = 'Booksprint_skinTemplate', $useHeadElement = true;

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
			'skins.booksprint_skin'
		) );
		$out->addModules( array(
			'skins.booksprint_skin.js'
		) );
	}
	public function getBookname(){
		global $wgTitle;
		$title = $wgTitle;
		if ($wgTitle->getBaseText() !==  $wgTitle->getFullText()){
			$title = Title::newFromText($wgTitle->getBaseText());
		}
		if (in_array("Kategorie:Buch", array_keys($title->getParentCategories()))){
			return str_replace(" ", "_", $title->getBaseText());

		}
		return null;
	}

	public function hasBookTemplate(){

		$bookname = $this->getBookname();

		if ($bookname !== null){
			$bookTemplatePath = __DIR__ . "/customize/" . $bookname . "/BookTemplate.php";
			if ( file_exists($bookTemplatePath)){
				require_once($bookTemplatePath);
				return true;
			}
		}
		return false;
	}

	public function getBookHeadItems(){
		$items = array();
		$bookname = $this->getBookname();
		if ($bookname !== null){
			global $wgScriptPath;
			$cssPath =  __DIR__ . "/customize/" . $bookname . "/book.css";
			if ( file_exists($cssPath)){
				$items[$bookname . "_css"] = '<link rel="stylesheet" href="' . $wgScriptPath . '/skins/Booksprint_skin/customize/' . $bookname . '/book.css">';
			}
			$jsPath =  __DIR__ . "/customize/" . $bookname . "/book.js";
			if ( file_exists($jsPath)){
				$items[$bookname . "_js"] = '<script src="' . $wgScriptPath . '/skins/Booksprint_skin/customize/' . $bookname . '/book.js"></script>';
			}

		}
		return $items;
	}

	/**
	 * @param $out OutputPage
	 */
	function setupSkinUserCss( OutputPage $out ) {
		parent::setupSkinUserCss( $out );
	}
}
