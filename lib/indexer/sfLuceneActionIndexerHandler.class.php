<?php
/*
 * This file is part of the sfLucenePlugin package
 * (c) 2007 - 2008 Carl Vondrick <carl@carlsoft.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * @package sfLucenePlugin
 * @subpackage Indexer
 * @author Carl Vondrick
 * @version SVN: $Id: sfLuceneActionIndexerHandler.class.php 7108 2008-01-20 07:44:42Z Carl.Vondrick $
 */

class sfLuceneActionIndexerHandler extends sfLuceneIndexerHandler
{
  public function rebuild()
  {
    $prefix = sfConfig::get('sf_app_dir') . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR;
    $suffix = DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'search.yml';

    $path = $prefix . '*' . $suffix;

    $searches = glob($path);

    foreach ($searches as $search)
    {
      $module = substr($search, strlen($prefix), strlen($search) - strlen($prefix) - strlen($suffix));

      $this->rebuildModule($module);
    }
  }

  public function rebuildModule($module)
  {
   $config = sfConfig::get('sf_app_dir') . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR  . $module . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'search.yml';

    if (!is_readable($config))
    {
      throw new sfLuceneIndexerException(sprintf('Unable to read "%s"', $config));
    }

    include(sfContext::getInstance()->getConfigCache()->checkConfig($config));

    if (!isset($config[$this->getSearch()->getParameter('name')]['actions']) || !is_array($config[$this->getSearch()->getParameter('name')]['actions']))
    {
      throw new sfLuceneIndexerException(sprintf('No actions were defined for module "%s", but a search.yml file was found', $module));
    }
    elseif (count($config[$this->getSearch()->getParameter('name')]['actions']) == 0)
    {
      return;
    }

    if (isset($config[$this->getSearch()->getParameter('name')]['actions']))
    {
      $this->getSearch()->getEventDispatcher()->notify(new sfEvent($this, 'indexer.log', array('Discovered %d actions in module "%s"', count($config[$this->getSearch()->getParameter('name')]['actions']), $module)));

      foreach ($config[$this->getSearch()->getParameter('name')]['actions'] as $action => $properties)
      {
        $this->getFactory()->getAction($module, $action)->save();
      }
    }




    
  }
}