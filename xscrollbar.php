<?php
/*****************************************************************
 * @package Xscroller
 * @version 1.1
 * @author ThemeXpert http://www.themexpert.com
 * @copyright Copyright (C) 2009 - 2014 ThemeXpert
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *****************************************************************/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

class plgSystemXscrollbar extends JPlugin
{
    function __construct(&$subject, $config)
    {
        parent::__construct($subject, $config);

        $this->app = JFactory::getApplication();
    }

    function onBeforeRender()
    {
        if( $this->app->isAdmin() ) return;

        if($this->app->input->get('option') == 'com_acymailing'){
            $disable_acymailing = $this->params->get('disable_acymailing', 0);
            if($disable_acymailing) return;
        }

        // Load jQuery
        $this->loadjQuery();

        // Load Script
        $this->loadScript();
    }


    function loadScript()
    {
        $doc = JFactory::getDocument();

        $options = array();
        $options[] = 'cursorcolor : "#' . $this->params->get('bar_color') . '"';
        $options[] = 'cursorwidth : "' . $this->params->get('bar_width') . '"';
        $options[] = 'cursorborderradius: "' . $this->params->get('bar_radius') . '"';
        $options[] = 'autohidemode: "' . $this->params->get('bar_hide') . '"';

        $script_path = JURI::root(true) . '/plugins/system/xscrollbar/assets/jquery.nicescroll.js';
        $doc->addScript($script_path);

        $js = "jQuery(document).ready(function() {
                 jQuery('html').niceScroll({" . implode(',', $options) .", cursorborder: 'none', zindex: '99999'});
            });";

        $doc->addScriptDeclaration($js);
    }

    function loadjQuery()
    {
        $doc = JFactory::getDocument();

        if ($this->params->get('jquery'))
        {
            // if its running on J2.5.x then load jquery from module core othwerwise framework library
            if(version_compare( JVERSION, '2.5', 'ge' ) && version_compare( JVERSION, '3.0', 'lt' ) ) {

                $jquery = JURI::root(true) . '/plugins/system/xscrollbar/assets/jquery-1.8.2.min.js';
                $jquery_noconflict = JURI::root(true) . '/plugins/system/xscrollbar/assets/jquery-noconflict.js';
                $doc->addScript($jquery);
                $doc->addScript($jquery_noconflict);

            }else{
                JHtml::_('jquery.framework');
            }
        }
    }
}