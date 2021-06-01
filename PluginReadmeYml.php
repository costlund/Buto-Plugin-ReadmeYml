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
      $content = $this->getElementAsReadme($element);
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
        $s .= '# '.$v->get('innerHTML')."\n\n";
      }elseif($v->get('type')=='h2'){
        $s .= '## '.$v->get('innerHTML')."\n\n";
      }elseif($v->get('type')=='h3'){
        $s .= '### '.$v->get('innerHTML')."\n\n";
      }elseif($v->get('type')=='h4'){
        $s .= '#### '.$v->get('innerHTML')."\n\n";
      }elseif($v->get('type')=='h5'){
        $s .= '##### '.$v->get('innerHTML')."\n\n";
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
    $parser = new PluginReadmeParser();
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
      if($i1->get('item')){
        foreach ($i1->get('item') as $v2) {
          $i2 = new PluginWfArray($v2);
          $li1[] = wfDocument::createHtmlElement('li', array(wfDocument::createHtmlElement('a', $this->get_label($i2), array('href' => '#'.$i2->get('id')))));
          if($i2->get('item')){
            $li2 = array();
            foreach ($i2->get('item') as $v3) {
              $i3 = new PluginWfArray($v3);
              $li2[] = wfDocument::createHtmlElement('li', array(wfDocument::createHtmlElement('a', $this->get_label($i3), array('href' => '#'.$i3->get('id')))));
              if($i3->get('item')){
                $li3 = array();
                foreach ($i3->get('item') as $v4) {
                  $i4 = new PluginWfArray($v4);
                  $li3[] = wfDocument::createHtmlElement('li', array(wfDocument::createHtmlElement('a', $this->get_label($i4), array('href' => '#'.$i4->get('id')))));
                  if($i4->get('item')){
                    $li4 = array();
                    foreach ($i4->get('item') as $v5) {
                      $i5 = new PluginWfArray($v5);
                      $li4[] = wfDocument::createHtmlElement('li', array(wfDocument::createHtmlElement('a', $this->get_label($i5), array('href' => '#'.$i5->get('id')))));
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
    $element = array();
    foreach ($readme->get() as $v1) {
      $i1 = new PluginWfArray($v1);
      $element[] = wfDocument::createHtmlElement('h1', $this->get_label($i1));
      $element[] = $this->get_div($i1);
      $element[] = wfDocument::createHtmlElement('ul', $li1);
      if($i1->get('item')){
        foreach ($i1->get('item') as $v2) {
          $i2 = new PluginWfArray($v2);
          $element[] = $this->get_anchor($i2);
          $element[] = wfDocument::createHtmlElement('a', null, array('name' => $i2->get('id')));
          $element[] = wfDocument::createHtmlElement('h2', $this->get_label($i2));
          $element[] = $this->get_link($i2);
          $element[] = $this->get_div($i2, 'primary');
          if($i2->get('item')){
            foreach ($i2->get('item') as $v3) {
              $i3 = new PluginWfArray($v3);
              $element[] = $this->get_anchor($i3);
              $element[] = wfDocument::createHtmlElement('a', null, array('name' => $i3->get('id')));
              $element[] = wfDocument::createHtmlElement('h3', $this->get_label($i3));
              $element[] = $this->get_link($i3);
              $element[] = $this->get_div($i3, 'info');
              if($i3->get('item')){
                foreach ($i3->get('item') as $v4) {
                  $i4 = new PluginWfArray($v4);
                  $element[] = $this->get_anchor($i4);
                  $element[] = wfDocument::createHtmlElement('a', null, array('name' => $i4->get('id')));
                  $element[] = wfDocument::createHtmlElement('h4', $this->get_label($i4));
                  $element[] = $this->get_link($i4);
                  $element[] = $this->get_div($i4, 'light');
                  if($i4->get('item')){
                    foreach ($i4->get('item') as $v5) {
                      $i5 = new PluginWfArray($v5);
                      $element[] = $this->get_anchor($i5);
                      $element[] = wfDocument::createHtmlElement('a', null, array('name' => $i5->get('id')));
                      $element[] = wfDocument::createHtmlElement('h5', $this->get_label($i5));
                      $element[] = $this->get_link($i5);
                      $element[] = $this->get_div($i5);
                    }
                  }
                }
              }
            }
          }
        }
      }
    }
    return $element;
  }
  private function get_div($data, $alert = ''){
    $element = null;
    if($data->get('description')){
      $class = '';
      if($alert){
        $class = "alert alert-$alert";
      }
      $element = wfDocument::createHtmlElement('div', $this->get_description($data), array('class' => $class));
    }
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
      $element = wfDocument::createHtmlElement('a', 'Link', array('href' => '#'.$data->get('link'), 'class' => 'btn btn-secondary btn-sm'));
    }
    return $element;
  }
  private function get_description($data){
    $parser = new PluginReadmeParser();
    $element = array();
    $element[] = wfDocument::createHtmlElement('div', $parser->parse_text($data->get('description')));
    if($data->get('description_dev')){
      $element[] = wfDocument::createHtmlElement('div', $parser->parse_text($data->get('description_dev')), array('class' => 'text-danger'));
    }
    return $element;
  }
  private function get_label($data){
    $label = $data->get('name');
    if($data->get('date')){
      $calc_date = new PluginWfArray($this->calc_date->calcAll($data->get('date'), date('Y-m-d')));
      if($calc_date->get('days_total')<=30){
        $label .= ' <span class="badge badge-pill badge-success" style="font-size:10px" title="'.$data->get('date').'">'.$data->get('date').'</span>';
      }
    }
    return $label;
  }
}
