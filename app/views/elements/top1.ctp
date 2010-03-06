<?php
/**
 * Tatoeba Project, free collaborative creation of multilingual corpuses project
 * Copyright (C) 2009  HO Ngoc Phuong Trang <tranglich@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 *
 * @category PHP
 * @package  Tatoeba
 * @author   HO Ngoc Phuong Trang <tranglich@gmail.com>
 * @license  Affero General Public License
 * @link     http://tatoeba.org
 */

$lang = 'eng';
if (isset($this->params['lang'])) {
    Configure::write('Config.language', $this->params['lang']);
    $lang = $this->params['lang'];
}

// array containing the elements of the menu : $title => $route
$menuElements = array(
    __('Home', true) => '/'.$lang,
    __('Browse', true) => array(
        "controller" => "sentences",
        "action" => "show",
        "random"
    ),
    __('Lists', true) => array(
        "controller" => "sentences_lists",
        "action" => "index"
    ),
    /*,__('Conversations',true) => array(
        "controller" => "conversations",
        "action" => "index"
    ),
    */
    __('Contribute', true) => array(
        "controller" => "pages",
        "action" => "contribute"
    ),
    __('Members', true) => array(
        "controller" => "users",
        "action" => "all"
    ),
    __('Wall', true) => array(
        "controller" => "wall",
        "action" => "index"
    ),
    __('What\'s new', true) => array(
        "controller" => "pages",
        "action" => "whats_new"
    )
);

?>

<div id="top_menu_container">
<div id="top_menu">

    <?php echo $this->element('interface_language'); ?>

    <div id="user_menu">
    <?php
    if (!$session->read('Auth.User.id')) {
        echo $this->element('login');
    } else {
        echo $this->element('space');
    }
    ?>
    </div>

    <div id="navigation_menu">
        <ul>

        <?php
        // current path param
        $param = '';
        if (isset($this->params['pass'][0])) {
            $param = $this->params['pass'][0];
        };
        
        // current path action
        $action = $this->params['action'];
        if ($action == 'display') {
            $action = $param;
        }
        
        // current path controller
        $controller = $this->params['controller'];
        
        foreach ($menuElements as $title => $route) {
            $cssClass = '';
            
            //Checking if we should apply the "current" CSS class to the <li> element
            if (is_array($route)) { // categories other than Home
                if ($controller == $route['controller']) {
                    if (isset($route['action'])) {
                        if ($action == $route['action']) {
                            $cssClass = 'class="show"';
                        }
                    } else {
                        if ($action == 'index') {
                            $cssClass = 'class="show"';
                        }
                    }
                }
            } else { // Home
                if (isset($param) && ($param == 'home' || $param == 'index')) {
                    $cssClass = 'class="show"';
                }
            }
            
            // displaying <li> element
            ?>
            <li <?php echo $cssClass; ?>>
            <?php
            echo $html->link($title, $route, array("class"=>$route['action']));
            ?>
            </li>
        <?php
        }
        ?>

        </ul>
    </div>
</div>
</div>
