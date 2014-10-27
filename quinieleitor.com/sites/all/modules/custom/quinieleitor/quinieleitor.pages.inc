<?php


function quinieleitor_bet_form(&$form_state) {
  $betting_slip = quinieleitor_load_current_slip();
  $form = array(
    '#prefix' => "<div id='add-slip-{$betting_slip->id}'>",
    '#suffix' => "</div>",
  );
  $form['matches'] = array(
    '#tree' => true,
  );
  foreach ($betting_slip->matches as $match) {
    $form['matches'][$match->id] = array(
      '#type' => 'textfield',
      '#title' => $match->name,
      '#required' => TRUE,
      '#default_value' => isset($form_state['matches'][$match->id]) ? $form_state['matches'][$match->id] : '',
    );
  }

  return $form;
}
