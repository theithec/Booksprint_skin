<?php
/**
 * BaseTemplate class for the Handbuchio2 skin
 *
 * @ingroup Skins
 */
class Handbuchio2Template extends BaseTemplate {
  /**
   * Outputs the entire contents of the page
   */
  public function execute() {
    global $wgUser;
    $this->loggedIn =  $wgUser->isAllowed( 'edit' );

    $this->html( 'headelement' );
    echo '<style>.dropdown .menu { display: none; }.dropdown .js-dropdown-active { display: block; }</style><div id="mw-wrapper" class="row">';
    //$this->outputLogo();
?>
<header class="columns">
<img src="<?php echo $this->text('stylepath'); ?>/<?php echo $this->text('stylename'); ?>/dist/assets/img/TIB_LogoNT_de.png" alt="TIB Logo" />
<div id="user-tools" class="subnav">
<?php $this->outputUserLinks(); ?>
</div>
</header>
<div id="mw-navigation">
<?php 
    //echo $this->getMsg( 'navigation-heading' )->parse() 
    $this->outputSearch();
    if ($this->loggedIn){
      echo '<div id="page-tools" class="subnav">';
      $this->outputPageLinks();
      echo '</div>';
      //echo ' <div id="site-navigation" class="subnav"></div>';
    } 
?>
</div>
      <div style="float: left;" class="mw-body-wrapper columns">
      <div id="content" class="mw-body column" role="main">
<?php
    if ( $this->data['sitenotice'] ) {
?>
          <div id="siteNotice"><?php $this->html( 'sitenotice' ) ?></div>
<?php
    }
    if ( $this->data['newtalk'] ) {
?>
          <div class="usermessage"><?php $this->html( 'newtalk' ) ?></div>
<?php
    }
    echo $this->getIndicators();
?>

        <h1 class="firstHeading" lang="<?php $this->text( 'pageLanguage' ); ?>">
          <?php $this->html( 'title' ) ?>
        </h1>
        <div id="siteSub"><?php echo $this->getMsg( 'tagline' )->parse() ?></div>
        <div id="bodyContent" class="mw-body-content">
          <div id="contentSub">
<?php
    if ( $this->data['subtitle'] ) {
?>
              <p><?php $this->html( 'subtitle' ) ?></p>
<?php
    }
    if ( $this->data['undelete'] ) {
?>
              <p><?php $this->html( 'undelete' ) ?></p>
<?php
    }
?>
          </div>

<?php
    $this->html( 'bodycontent' );
    $this->clear();
?>
          <div class="printfooter">
            <?php $this->html( 'printfooter' ); ?>
          </div>
<?php
    $this->html( 'catlinks' );
    $this->html( 'dataAfterContent' );
?>
        </div>
      </div>
      </div>

      <div id="mw-footer">
<?php
    foreach ( $this->getFooterLinks() as $category => $links ) {
?>
          <ul id="footer-<?php echo htmlspecialchars( $category, ENT_QUOTES ) ?>" role="contentinfo">
<?php
      foreach ( $links as $key ) { 
?>
              <li id="footer-<?php echo htmlspecialchars( $category, ENT_QUOTES ) ?>-<?php echo htmlspecialchars( $key, ENT_QUOTES ) ?>"><?php $this->html( $key ) ?></li>
<?php
      }
?>
          </ul>
<?php
    }

    $this->clear();
?>

      </div>
    </div>
<footer class="row">
<?php //echo 	$this->outputSiteNavigation();?>
<ul id="footer-icons" role="contentinfo">
<?php
    foreach ( $this->getFooterIcons( 'icononly' ) as $blockName => $footerIcons ) {
?>
            <li id="footer-<?php echo htmlspecialchars( $blockName, ENT_QUOTES ) ?>ico">
<?php
      foreach ( $footerIcons as $icon ) {
        echo $this->getSkin()->makeFooterIcon( $icon );
      }
?>
            </li>
<?php
    }
?>
        </ul>

<ul id="footer-places" role="contentinfo">
<?php
    foreach ( $this->places as $links => $key ) { 
?>
              <li id="footer-<?php echo htmlspecialchars( $key, ENT_QUOTES ) ?>-<?php echo htmlspecialchars( $key, ENT_QUOTES ) ?>"><?php $this->html( $key ) ?></li>
<?php
    }
?>
          </ul>

</footer>
    <?php $this->printTrail() ?>
    </body></html>

<?php
  }

  /**
   * Outputs a single sidebar portlet of any kind.
   */
  private function outputPortlet( $box ) {
    if ( !$box['content'] ) {
      return;
    }
    $hasHeaderMsg =  isset( $box['headerMessage'] )&& $box['headerMessage'] !== false;
    if ($hasHeaderMsg or isset($box['header'])){
      echo '<h3>';
      if($hasHeaderMsg){
        echo $this->getMsg( $box['headerMessage'] )->escaped();
      }else {
        echo htmlspecialchars( $box['header'], ENT_QUOTES );
      }
      echo '</h3>';
    }

    if ( is_array( $box['content'] ) ) {
      echo '<ul class="menu ' . (isset($box['menustyle']) ? $box['menustyle']: 'horizontal') . '">';
      foreach ( $box['content'] as $key => $item ) {
        echo $this->makeListItem( $key, $item );
      }
      echo '</ul>';
    } else {
      echo $box['content'];
    }
  }

  private function outputPortletInDropdown( $box ) {
    if ( !$box['content'] ) {
      return;
    }
    $box['menustyle'] = 'vertical';
    echo ' <li role="navigation" class="mw-portlet" ' .
      ' id=" ' .  Sanitizer::escapeId( $box['id'] ) .'" ' .
      Linker::tooltip( $box['id'] ) . ' >';
    $msg = isset($box['headerMessage'])? $this->getMsg($box['headerMessage'])->escaped() : $box['header'];
    echo '<a href="#">' . $msg . '</a>';
    $box['headerMessage'] = false;
    $box['header'] = false;
    $this->outputPortlet($box);
    echo "</li>";

  }
  /**
   * Outputs the logo and (optionally) site title
   */
  private function outputLogo( $id = 'p-logo', $imageOnly = false ) {
?>
    <div id="<?php echo $id ?>" class="mw-portlet" role="banner">
      <a
        class="mw-wiki-logo"
          href="<?php echo htmlspecialchars( $this->data['nav_urls']['mainpage']['href'], ENT_QUOTES )
          ?>" <?php
          echo Xml::expandAttributes( Linker::tooltipAndAccesskeyAttribs( 'p-logo' ) )
?>></a>
<?php
          if ( !$imageOnly ) {
?>
        <a id="p-banner" class="mw-wiki-title" href="<?php echo htmlspecialchars( $this->data['nav_urls']['mainpage']['href'], ENT_QUOTES ) ?>">
          <?php echo $this->getMsg( 'sitetitle' )->escaped() ?>
        </a>
<?php
          }
?>
    </div>
<?php

  }

  /**
   * Outputs the search form
   */
  private function outputSearch() {
?>
    <form
      action="<?php $this->text( 'wgScript' ) ?>"
      role="search"
      class="mw-portlet"
      id="p-search"
    >
      <input type="hidden" name="title" value="<?php $this->text( 'searchtitle' ) ?>" />
      <h3>
        <label for="searchInput"><?php echo $this->getMsg( 'search' )->escaped() ?></label>
      </h3>
      <?php echo $this->makeSearchInput( array( 'id' => 'searchInput' ) ) ?>
      <?php echo $this->makeSearchButton( 'go', array( 'id' => 'searchGoButton', 'class' => 'searchButton' ) ) ?>
      <input type="hidden" name="title" value="<?php $this->text( 'searchtitle' ) ?>"/>
    </form>
<?php
  }

  /**
   * Outputs the sidebar
   * Set the elements to true to allow them to be part of the sidebar
   */
  private function outputSiteNavigation() {
    $sidebar = $this->getSidebar();

    $sidebar['SEARCH'] = false;
    $sidebar['TOOLBOX'] = true;
    $sidebar['LANGUAGES'] = true;

    foreach ( $sidebar as $boxName => $box ) {
      if ( $boxName === false ) {
        continue;
      }
      $this->outputPortlet( $box, true );
    }
  }

  /**
   * Outputs page-related tools/links
   */
  private function outputPageLinks() {

    echo '<ul class="dropdown menu" data-dropdown-menu>';
    /*$this->outputPortletInDropdown( array(
      'id' => 'p-namespaces',
      'headerMessage' => 'namespaces',
      'content' => $this->data['content_navigation']['namespaces'],
    ) );
     */
    $this->outputPortletInDropdown( array(
      'id' => 'p-variants',
      'headerMessage' => 'variants',
      'content' => $this->data['content_navigation']['variants'],
    ) );
    $this->outputPortletInDropdown( array(
      'id' => 'p-views',
      'headerMessage' => 'views',
      'content' => $this->data['content_navigation']['views'],
    ) );
    $this->outputPortletInDropdown( array(
      'id' => 'p-actions',
      'headerMessage' => 'actions',
      'content' => $this->data['content_navigation']['actions'],
    ) );
    $this->outputPortletInDropdown( array(
      'id' => 'p-specialpages',
      'headerMessage' => 'toolbox',
      'content' => $this->getToolbox()
    ) );
    //$this->getToolbox()
    echo '</ul>';
  }

  /**
   * Outputs user tools menu
   */
  private function outputUserLinks() {
    global $wgUser;
    echo '<ul class="dropdown menu" data-dropdown-menu>';
    $this->outputPortletInDropdown( array(
      'id' => 'p-personal',
      'header' => $this->loggedIn ? $wgUser->getName(): "Anmelden",
      'content' => $this->getPersonalTools(),
    ) );
    echo "</ul>";
  }

  /**
   * Outputs a css clear using the core visualClear class
   */
  private function clear() {
    echo '<div class="visualClear"></div>';
  }
  function getFooterLinks($option=null){
    $links = parent::getFooterLinks($option);
    $this->places = $links['places'];
    unset($links['places']);
    return $links;
  }
}
