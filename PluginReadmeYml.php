<?php
class PluginReadmeYml{
  private $calc_date = null;
  function __construct() {
    wfPlugin::includeonce('wf/yml');
    wfPlugin::includeonce('readme/parser');
    wfPlugin::includeonce('calc/date_v1');
    $this->calc_date = new PluginCalcDate_v1();
  }
  public function widget_parse($data){
    $data = new PluginWfArray($data);
    /**
     * Get element.
     */
    $element = $this->getElement($data->get('data/file'));
    /**
     * Save to file.
     */
    if($data->get('data/save')){
      $content = $this->getElementAsReadme($element[1]['innerHTML'][1]['innerHTML']);
      wfFilesystem::saveFile(wfGlobals::getAppDir().$data->get('data/save'), $content);
    }
    /**
     * 
     */
    wfDocument::renderElement($element);
  }
  private function getElementAsReadme($element){
    $s = null;
    foreach ($element as $v) {
      $v = new PluginWfArray($v);
      if($v->get('type')=='h1'){
        $s .= '# '.$v->get('innerHTML/0/innerHTML')."\n\n";
      }elseif($v->get('type')=='h2'){
        $s .= '## '.$v->get('innerHTML/0/innerHTML')."\n\n";
      }elseif($v->get('type')=='h3'){
        $s .= '### '.$v->get('innerHTML/0/innerHTML')."\n\n";
      }elseif($v->get('type')=='h4'){
        $s .= '#### '.$v->get('innerHTML/0/innerHTML')."\n\n";
      }elseif($v->get('type')=='h5'){
        $s .= '##### '.$v->get('innerHTML/0/innerHTML')."\n\n";
      }elseif($v->get('type')=='p'){
        $s .= '###### '.$v->get('innerHTML/0/innerHTML')."\n\n";
      }elseif($v->get('type')=='div'){
        $s .= ''.$v->get('innerHTML/0/innerHTML')."\n\n";
      }elseif($v->get('type')=='a'){
        $s .= '<a name="'.$v->get('attribute/name').'">'.null."</a>\n\n";
      }elseif($v->get('type')=='ul'){
        foreach ($v->get('innerHTML') as $ul1) {
          $li1 = new PluginWfArray($ul1);
          if($li1->get('type')=='li'){
            $s .= $this->getReadmeLink($li1);
          }elseif($li1->get('type')=='ul'){
            foreach ($li1->get('innerHTML') as $ul2) {
              $li2 = new PluginWfArray($ul2);
              if($li2->get('type')=='li'){
                $s .= $this->getReadmeLink($li2, 1);
              }elseif($li2->get('type')=='ul'){
                foreach ($li2->get('innerHTML') as $ul3) {
                  $li3 = new PluginWfArray($ul3);
                  if($li3->get('type')=='li'){
                    $s .= $this->getReadmeLink($li3, 2);
                  }elseif($li3->get('type')=='ul'){
                    foreach ($li3->get('innerHTML') as $ul4) {
                      $li4 = new PluginWfArray($ul4);
                      if($li4->get('type')=='li'){
                        $s .= $this->getReadmeLink($li4, 3);
                      }elseif($li4->get('type')=='ul'){
                        /**
                         * ...
                         */                         
                      }
                    }
                  }
                }
              }
            }
          }
        }
        $s .= "\n\n";
      }
    }
    return $s;
  }
  private function getReadmeLink($e, $level = 0){
    $line = str_repeat(' ', $level*2);
    $s = "$line- [".$e->get('innerHTML/0/innerHTML')."](".$e->get('innerHTML/0/attribute/href').") \n";
    return $s;
  }
  /**
   * Get elements from yml file.
   * @param string $file From app root.
   * @return array With elements.
   */
  public function getElement($file){
    $readme = new PluginWfYml(wfGlobals::getAppDir().$file);
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
                  if($i4->get('item')){
                    foreach ($i4->get('item') as $k5 => $v5) {
                      $i5 = new PluginWfArray($v5);
                      $readme->set("readme/item/$k2/item/$k3/item/$k4/item/$k5/id", "key_$k2"."_$k3"."_$k4"."_$k5");
                      if($i5->get('item')){
                        foreach ($i5->get('item') as $k6 => $v6) {
                          $i6 = new PluginWfArray($v6);
                          $readme->set("readme/item/$k2/item/$k3/item/$k4/item/$k5/item/$k6/id", "key_$k2"."_$k3"."_$k4"."_$k5"."_$k6");
                        }
                      }
                    }
                  }
                }
              }
            }
          }
        }
      }
    }
    /**
     * Set external links.
     */
    foreach ($readme->get() as $v1) {
      $i1 = new PluginWfArray($v1);
      if($i1->get('item')){
        foreach ($i1->get('item') as $k2 => $v2) {
          $i2 = new PluginWfArray($v2);
          $readme->set("readme/item/$k2/external", $this->get_label($i1).', '.$this->get_label($i2));
          if($i2->get('item')){
            foreach ($i2->get('item') as $k3 => $v3) {
              $i3 = new PluginWfArray($v3);
              $readme->set("readme/item/$k2/item/$k3/external", $this->get_label($i1).', '.$this->get_label($i2).', '.$this->get_label($i3));
              if($i3->get('item')){
                foreach ($i3->get('item') as $k4 => $v4) {
                  $i4 = new PluginWfArray($v4);
                  $readme->set("readme/item/$k2/item/$k3/item/$k4/external", $this->get_label($i1).', '.$this->get_label($i2).', '.$this->get_label($i3).', '.$this->get_label($i4));
                  if($i4->get('item')){
                    foreach ($i4->get('item') as $k5 => $v5) {
                      $i5 = new PluginWfArray($v5);
                      $readme->set("readme/item/$k2/item/$k3/item/$k4/item/$k5/external", $this->get_label($i1).', '.$this->get_label($i2).', '.$this->get_label($i3).', '.$this->get_label($i4).', '.$this->get_label($i5));
                      if($i5->get('item')){
                        foreach ($i5->get('item') as $k6 => $v6) {
                          $i6 = new PluginWfArray($v6);
                          $readme->set("readme/item/$k2/item/$k3/item/$k4/item/$k5/item/$k6/external", $this->get_label($i1).', '.$this->get_label($i2).', '.$this->get_label($i3).', '.$this->get_label($i4).', '.$this->get_label($i5).', '.$this->get_label($i6));
                        }
                      }
                    }
                  }
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
      $li1[] = wfDocument::createHtmlElement('h2', $this->get_label($i1));
      if($i1->get('item')){
        foreach ($i1->get('item') as $v2) {
          $i2 = new PluginWfArray($v2);
          $li1[] = wfDocument::createHtmlElement('li', array(wfDocument::createHtmlElement('a', $this->get_label($i2), array('href' => '#'.$i2->get('id'))), wfDocument::createHtmlElement('span', $this->get_date($i2), array('class' => 'badge bg-warning', 'style' => 'float:right;font-size:8px'))  ));
          if($i2->get('item')){
            $li2 = array();
            foreach ($i2->get('item') as $v3) {
              $i3 = new PluginWfArray($v3);
              $li2[] = wfDocument::createHtmlElement('li', array(wfDocument::createHtmlElement('a', $this->get_label($i3), array('href' => '#'.$i3->get('id'))), wfDocument::createHtmlElement('span', $this->get_date($i3), array('class' => 'badge bg-warning', 'style' => 'float:right;font-size:8px'))));
              if($i3->get('item')){
                $li3 = array();
                foreach ($i3->get('item') as $v4) {
                  $i4 = new PluginWfArray($v4);
                  $li3[] = wfDocument::createHtmlElement('li', array(wfDocument::createHtmlElement('a', $this->get_label($i4), array('href' => '#'.$i4->get('id'))), wfDocument::createHtmlElement('span', $this->get_date($i4), array('class' => 'badge bg-warning', 'style' => 'float:right;font-size:8px'))));
                  if($i4->get('item')){
                    $li4 = array();
                    foreach ($i4->get('item') as $v5) {
                      $i5 = new PluginWfArray($v5);
                      $li4[] = wfDocument::createHtmlElement('li', array(wfDocument::createHtmlElement('a', $this->get_label($i5), array('href' => '#'.$i5->get('id'))), wfDocument::createHtmlElement('span', $this->get_date($i5), array('class' => 'badge bg-warning', 'style' => 'float:right;font-size:8px'))));
                      if($i5->get('item')){
                        $li5 = array();
                        foreach($i5->get('item') as $v6){
                          $i6 = new PluginWfArray($v6);
                          $li5[] = wfDocument::createHtmlElement('li', array(wfDocument::createHtmlElement('a', $this->get_label($i6), array('href' => '#'.$i6->get('id'))), wfDocument::createHtmlElement('span', $this->get_date($i6), array('class' => 'badge bg-warning', 'style' => 'float:right;font-size:8px'))));
                        }
                        $li4[] = wfDocument::createHtmlElement('ul', $li5);
                      }
                    }
                    $li3[] = wfDocument::createHtmlElement('ul', $li4);
                  }
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
    $content = array();
    $external_style = array('style' => 'font-style:italic;font-size:10px;');
    foreach ($readme->get() as $v1) {
      $i1 = new PluginWfArray($v1);
      $content[] = wfDocument::createHtmlElement('h1', array(wfDocument::createHtmlElement('span', $this->get_label($i1)), wfDocument::createHtmlElement('span', $this->get_date($i1), array('class' => 'badge bg-warning', 'style' => 'float:right;font-size:10px'))));
      $content[] = $this->get_div($i1);
      if($i1->get('item')){
        foreach ($i1->get('item') as $v2) {
          $i2 = new PluginWfArray($v2);
          $content[] = $this->get_anchor($i2);
          $content[] = wfDocument::createHtmlElement('a', null, array('name' => $i2->get('id'), 'id' => $i2->get('id')));
          $content[] = wfDocument::createHtmlElement('h2', array(wfDocument::createHtmlElement('span', $this->get_label($i2)), wfDocument::createHtmlElement('span', $this->get_date($i2), array('class' => 'badge bg-warning', 'style' => 'float:right;font-size:10px'))));
          $content[] = wfDocument::createHtmlElement('code', $i2->get('external'), $external_style);
          $content[] = $this->get_link($i2);
          $content[] = $this->get_div($i2, 'primary');
          if($i2->get('item')){
            foreach ($i2->get('item') as $v3) {
              $i3 = new PluginWfArray($v3);
              $content[] = $this->get_anchor($i3);
              $content[] = wfDocument::createHtmlElement('a', null, array('name' => $i3->get('id'), 'id' => $i3->get('id')));
              $content[] = wfDocument::createHtmlElement('h3', array(wfDocument::createHtmlElement('span', $this->get_label($i3)), wfDocument::createHtmlElement('span', $this->get_date($i3), array('class' => 'badge bg-warning', 'style' => 'float:right;font-size:10px'))));
              $content[] = wfDocument::createHtmlElement('code', $i3->get('external'), $external_style);
              $content[] = $this->get_link($i3);
              $content[] = $this->get_div($i3, 'secondary');
              if($i3->get('item')){
                foreach ($i3->get('item') as $v4) {
                  $i4 = new PluginWfArray($v4);
                  $content[] = $this->get_anchor($i4);
                  $content[] = wfDocument::createHtmlElement('a', null, array('name' => $i4->get('id'), 'id' => $i4->get('id')));
                  $content[] = wfDocument::createHtmlElement('h4', array(wfDocument::createHtmlElement('span', $this->get_label($i4)), wfDocument::createHtmlElement('span', $this->get_date($i4), array('class' => 'badge bg-warning', 'style' => 'float:right;font-size:10px'))));
                  $content[] = wfDocument::createHtmlElement('code', $i4->get('external'), $external_style);
                  $content[] = $this->get_link($i4);
                  $content[] = $this->get_div($i4, 'info');
                  if($i4->get('item')){
                    foreach ($i4->get('item') as $v5) {
                      $i5 = new PluginWfArray($v5);
                      $content[] = $this->get_anchor($i5);
                      $content[] = wfDocument::createHtmlElement('a', null, array('name' => $i5->get('id'), 'id' => $i5->get('id')));
                      $content[] = wfDocument::createHtmlElement('h5', array(wfDocument::createHtmlElement('span', $this->get_label($i5)), wfDocument::createHtmlElement('span', $this->get_date($i5), array('class' => 'badge bg-warning', 'style' => 'float:right;font-size:10px'))));
                      $content[] = wfDocument::createHtmlElement('code', $i5->get('external'), $external_style);
                      $content[] = $this->get_link($i5);
                      $content[] = $this->get_div($i5, 'light');
                      if($i5->get('item')){
                        foreach ($i5->get('item') as $v6) {
                          $i6 = new PluginWfArray($v6);
                          $content[] = $this->get_anchor($i6);
                          $content[] = wfDocument::createHtmlElement('a', null, array('name' => $i6->get('id'), 'id' => $i6->get('id')));
                          $content[] = wfDocument::createHtmlElement('p', array(wfDocument::createHtmlElement('span', $this->get_label($i6)), wfDocument::createHtmlElement('span', $this->get_date($i6), array('class' => 'badge bg-warning', 'style' => 'float:right;font-size:10px'))));
                          $content[] = wfDocument::createHtmlElement('code', $i6->get('external'), $external_style);
                          $content[] = $this->get_link($i6);
                          $content[] = $this->get_div($i6, 'light');
                        }
                      }
                    }
                  }
                }
              }
            }
          }
        }
      }
    }
    /**
     * element
     */
    $element = array();
    /**
     * alert file path (webmaster)
     */

    $temp = wfDocument::getElementFromFolder(__DIR__, 'widget_parse_file');
    $temp->setByTag(array('file' => $file));
    $element[] = $temp->get();
    /**
     * 
     */
    $element[] = wfDocument::createHtmlElement('div', array(
      wfDocument::createHtmlElement('div', $li1,     array('id' => 'my_navigation',                                         'class' => 'col-md-3', 'style' => 'max-height:90vh;overflow:auto;list-style-type: none;')),
      wfDocument::createHtmlElement('div', $content, array('data-bs-spy' => 'scroll', 'data-bs-target' => '#my_navigation', 'class' => 'col-md-9', 'style' => 'max-height:90vh;overflow:auto;')),
      wfDocument::createHtmlElement('style', '.plugin_readme_yml .active{color:red;font-weight:bold;}'),
      wfDocument::createHtmlElement('style', 'list-style-type: none;')
    ), array('class' => 'row plugin_readme_yml'));
    /**
     * 
     */
    return $element;
  }
  private function get_div($data, $alert = ''){
    $element = null;
    $class = '';
    if($alert){
      $class = "alert alert-$alert";
    }
    $element = wfDocument::createHtmlElement('div', $this->get_description($data), array('class' => $class));
    return $element;
  }
  private function get_anchor($data){
    $element = null;
    if($data->get('anchor')){
      $element = wfDocument::createHtmlElement('a', null, array('name' => $data->get('anchor')));
    }
    return $element;
  }
  private function get_link($data){
    $element = null;
    if($data->get('link')){
      $element = wfDocument::createHtmlElement('a', 'Link', array('href' => '#'.$data->get('link')));
    }
    return $element;
  }
  private function get_description($data){
    $parser = new PluginReadmeParser();
    $element = array();
    $element[] = wfDocument::createHtmlElement('div', $parser->parse_text($data->get('description')));
    if($data->get('description_dev')){
      $element[] = wfDocument::createHtmlElement('div', $parser->parse_text($data->get('description_dev')), array('class' => 'text-warning'));
    }
    return $element;
  }
  private function get_label($data){
    $label = $data->get('name');
    return $label;
  }
  private function get_date($data){
    $date = '';
    if($data->get('date')){
      $calc_date = new PluginWfArray($this->calc_date->calcAll($data->get('date'), date('Y-m-d')));
      if($calc_date->get('days_total')<=30){
        $date = $data->get('date');
      }
    }
    return $date;
  }
}
