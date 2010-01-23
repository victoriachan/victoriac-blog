<?php
// $Id $
/**
 * @file
 * Provide views handler so that Amazon Image can be displayed in the various
 * ways that are available.
 *
 * @author rfay
 *
 */

class views_handler_field_amazon_image extends views_handler_field {
  function options(&$options) {
    parent::options($options);
    $options['image_size'] = 'smallimage';
    $options['link_format'] = 'amazon';
  }

  /**
   * Override init function to provide generic option to link to node.
   */
  function init(&$view, &$data) {
    parent::init($view, $data);
    if (!empty($data['image_size'])) {
      //$this->additional_fields[] = 'size';
      $this->additional_fields[] = 'height';
      $this->additional_fields[] = 'width';
    }
    if (!empty($data['link_format']) && $data['link_format'] == 'amazon') {
      $this->additional_fields['detailpageurl'] = array(
        'table' => 'amazon_item', 'field' => 'detailpageurl', 'value' => 'amazon_item_detailpageurl'
      );
    }
    $this->additional_fields[] = 'asin';
  }

  function ensure_my_table() {
    if (empty($this->table_alias)) {
      $join_extra = array();
      if (!empty($this->options['image_size'])) {
        $join_extra[] = array('field' => 'size', 'value' => $this->options['image_size'], 'numeric' => FALSE);
      }

      $join = new views_join();
      $join->construct($this->table, 'amazon_item', 'asin', 'asin', $join_extra);

      $this->table_alias = $this->query->ensure_table($this->table, $this->relationship, $join);
    }
    return $this->table_alias;
  }

  /**
   * Provide link to node option
   */
  function options_form(&$form, &$form_state) {
    parent::options_form($form, $form_state);
    $form['image_size'] = array(
      '#title' => t('Image size'),
      '#type' => 'select',
      '#options' => array(
        'smallimage' => t('Small'),
        'mediumimage' => t("Medium"),
        'largeimage' => t("Large"),
      ),
      '#default_value' => !empty($this->options['image_size']) ? $this->options['image_size'] : 'MedumImage',
    );

    $form['link_format'] = array(
      '#title' => t('Link behavior'),
      '#type' => 'radios',
      '#options' => array(
        'plain' => t('No link'),
        'amazon' => t("A link to the product's Amazon page"),
      ),
      '#default_value' => !empty($this->options['link_format']) ? $this->options['link_format'] : 'plain',
    );
    if (module_exists('amazon_store')) {
      $form['link_format']['#options']['amazon_store'] = t("A link to the product's Amazon Store page (Amazon Store Module)");
    }
    $form['presentation_format'] = array(
      '#title' => t('Presentation format'),
      '#type' => 'select',
      '#options' => array(
        'markup' => t('HTML img markup'),
        'plain_url' => t('Plain URL to image'),
      ),
      '#default_value' => !empty($this->options['presentation_format']) ? $this->options['presentation_format'] : 'markup',
    );
    if ($this->view->base_table == 'node') {
      $form['link_format']['#options']['node'] = t('A link to the node the product is associated with');
    }
  }

  function render($values) {
    // We may not have a URL. It's not guaranteed that Amazon will give us one.
    if (empty($values->{$this->table_alias . '_url'})) {
      return;
    }
    $attributes = array(
      'height' => $values->{$this->table_alias . '_height'},
      'width' => $values->{$this->table_alias . '_width'},
    );

    // Choose presentation style
    if ($this->options['presentation_format'] == 'markup') {
      $image = theme('image', $values->{$this->table_alias . '_url'}, NULL, NULL, $attributes, FALSE);
    } else {
      $image = $values->{$this->table_alias . '_url'};
    }
    switch($this->options['link_format']) {
      case 'plain':
        return $image;
        break;
      case 'node':
        return l($image, 'node/'. $values->nid, array('html' => TRUE));
        break;
      case 'amazon':
        if (!empty($values->{$this->aliases['detailpageurl']})) {
          $urlfield = $values->{$this->aliases['detailpageurl']};
          return l($image, $urlfield, array('html' => TRUE));
        } else {
          return $image;
        }
        break;
      case 'amazon_store':
        $asin = $values->{$this->aliases['asin']};
        return l($image, 'amazon_store/item/' . $asin, array('html' => TRUE));
        break;
    }
  }
}
