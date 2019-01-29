<?php
class PluginReadmeYml{
  function __construct() {
    wfPlugin::includeonce('wf/yml');
    wfPlugin::includeonce('readme/parser');
  }
  public function widget_parse($data){
    $data = new PluginWfArray($data);
    $parser = new PluginReadmeParser();
    $readme = new PluginWfYml(wfGlobals::getAppDir().$data->get('data/file'));
    /**
     * Set ids.
     */
    foreach ($readme->get() as $v1) {
      $i1 = new PluginWfArray($v1);
      if($i1->get('item')){
        foreach ($i1->get('item') as $k2 => $v2) {
          $i2 = new PluginWfArray($v2);
          $readme->set("readme/item/$k2/id", "key_$k2");
          if($i2->get('item')){
            foreach ($i2->get('item') as $k3 => $v3) {
              $i3 = new PluginWfArray($v3);
              $readme->set("readme/item/$k2/item/$k3/id", "key_$k2"."_$k3");
              if($i3->get('item')){
                foreach ($i3->get('item') as $k4 => $v4) {
                  $i4 = new PluginWfArray($v4);
                  $readme->set("readme/item/$k2/item/$k3/item/$k4/id", "key_$k2"."_$k3"."_$k4");
                }
              }
            }
          }
        }
      }
    }
    /**
     * List
     */
    $li1 = array();
    foreach ($readme->get() as $v1) {
      $i1 = new PluginWfArray($v1);
      if($i1->get('item')){
        foreach ($i1->get('item') as $v2) {
          $i2 = new PluginWfArray($v2);
          $li1[] = wfDocument::createHtmlElement('li', array(wfDocument::createHtmlElement('a', $i2->get('name'), array('href' => '#'.$i2->get('id')))));
          if($i2->get('item')){
            $li2 = array();
            foreach ($i2->get('item') as $v3) {
              $i3 = new PluginWfArray($v3);
              $li2[] = wfDocument::createHtmlElement('li', array(wfDocument::createHtmlElement('a', $i3->get('name'), array('href' => '#'.$i3->get('id')))));
              if($i3->get('item')){
                $li3 = array();
                foreach ($i3->get('item') as $v4) {
                  $i4 = new PluginWfArray($v4);
                  $li3[] = wfDocument::createHtmlElement('li', array(wfDocument::createHtmlElement('a', $i4->get('name'), array('href' => '#'.$i4->get('id')))));
                }
                $li2[] = wfDocument::createHtmlElement('ul', $li3);
              }
            }
            $li1[] = wfDocument::createHtmlElement('ul', $li2);
          }
        }
      }
    }
    /**
     * Content
     */
    $element = array();
    foreach ($readme->get() as $v1) {
      $i1 = new PluginWfArray($v1);
      $element[] = wfDocument::createHtmlElement('h1', $i1->get('name'));
      $element[] = wfDocument::createHtmlElement('p', $parser->parse_text($i1->get('description')));
      $element[] = wfDocument::createHtmlElement('ul', $li1);
      if($i1->get('item')){
        foreach ($i1->get('item') as $v2) {
          $i2 = new PluginWfArray($v2);
          $element[] = wfDocument::createHtmlElement('a', null, array('name' => $i2->get('id')));
          $element[] = wfDocument::createHtmlElement('h2', $i2->get('name'));
          $element[] = wfDocument::createHtmlElement('p', $parser->parse_text($i2->get('description')));
          if($i2->get('item')){
            foreach ($i2->get('item') as $v3) {
              $i3 = new PluginWfArray($v3);
              $element[] = wfDocument::createHtmlElement('a', null, array('name' => $i3->get('id')));
              $element[] = wfDocument::createHtmlElement('h3', $i3->get('name'));
              $element[] = wfDocument::createHtmlElement('p', $parser->parse_text($i3->get('description')));
              if($i3->get('item')){
                foreach ($i3->get('item') as $v4) {
                  $i4 = new PluginWfArray($v4);
                  $element[] = wfDocument::createHtmlElement('a', null, array('name' => $i4->get('id')));
                  $element[] = wfDocument::createHtmlElement('h4', $i4->get('name'));
                  $element[] = wfDocument::createHtmlElement('p', $parser->parse_text($i4->get('description')));
                }
              }
            }
          }
        }
      }
    }
    /**
     * 
     */
    wfDocument::renderElement($element);
  }
}
