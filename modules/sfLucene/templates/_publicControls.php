<?php
/**
 * @package sfLucenePlugin
 * @subpackage Module
 * @author Carl Vondrick <carl@carlsoft.net>
 * @version SVN: $Id: _publicControls.php 7108 2008-01-20 07:44:42Z Carl.Vondrick $
 */
?>

<?php use_helper('I18N') ?>

<div id="search-public">
  <?php echo form_tag('sfLucene/search', 'method=get') ?>
    <input type="text" name="query" id="public-query" value="<?php echo $query?>">
    <input type="submit" value="<?php echo __('Search')?>">
  </form>
</div>