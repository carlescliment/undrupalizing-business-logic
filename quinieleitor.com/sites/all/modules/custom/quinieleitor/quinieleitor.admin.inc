<?php

/**
 * Renders the form to add a betting slip
 */
function quinieleitor_add_betting_slip_form(&$form_state) {
  $form = array();
  $form['date'] = array(
    '#type' => 'textfield',
    '#title' => 'Date',
    '#description' => 'Format YYYY-mm-dd',
    '#required' => TRUE,
    '#default_value' => isset($form_state['values']['date']) ? $form_state['values']['date'] : '',
  );
  $form['matches'] = array(
      '#tree' => true,
  );
  for ($i=0; $i<MATCHES_PER_SLIP; $i++) {
    $form["matches"][$i] = array(
      '#type' => 'textfield',
      '#title' => 'Match '.($i+1),
      '#description' => 'I.e.: RMA-FCB',
      '#required' => TRUE,
      '#default_value' => isset($form_state['values']['matches'][$i]) ? $form_state['values']['matches'][$i] : '',
    );
  }
  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => 'Add',
  );

  return $form;
}


function quinieleitor_add_betting_slip_form_submit($form, &$form_state) {
  $betting_slip = new stdClass;
  $betting_slip->date = $form_state['values']['date'];
  $betting_slip->matches = array();
  foreach ($form_state['values']['matches'] as $match_name) {
    $match = new stdClass;
    $match->name = $match_name;
    $betting_slip->matches[] = $match;
  }

  quinieleitor_save_betting_slip($betting_slip);
  drupal_set_message('The slip has been created');
}


/**
 * Renders the form to resolve a betting slip
 */
function quinieleitor_resolve_betting_slip_form(&$form_state) {
  $current_slip = quinieleitor_load_current_slip();
  $form_state['betting_slip_id'] = $current_slip->id;
  $form = array();
  $form['matches'] = array(
      '#tree' => true,
  );
  foreach ($current_slip->matches as $match) {
    $form["matches"][$match->id] = array(
      '#type' => 'textfield',
      '#title' => $match->name,
      '#required' => TRUE,
      '#default_value' => isset($form_state['values']['matches'][$match->id]) ? $form_state['values']['matches'][$match->id] : '',
    );
  }
  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => 'Resolve',
  );

  return $form;
}


function quinieleitor_resolve_betting_slip_form_submit($form, &$form_state) {
  quinieleitor_resolve_betting_slip($form_state['betting_slip_id'], $form_state['values']['matches']);
  drupal_set_message('The slip has been resolved');

  drupal_goto('/');
}
