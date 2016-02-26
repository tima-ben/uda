<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MyCButtonColumn
 *
 * @author Administrator
 */
class MyCButtonColumn extends CButtonColumn
{
   public function init()
   {
      $this->deleteButtonImageUrl = '/images/' . Controller::$button_size . '/trash.gif';
      $this->updateButtonImageUrl = '/images/' . Controller::$button_size . '/write2.gif';
      $this->viewButtonImageUrl = '/images/' . Controller::$button_size . '/search.gif';
      parent::init();
   }
}
?>
