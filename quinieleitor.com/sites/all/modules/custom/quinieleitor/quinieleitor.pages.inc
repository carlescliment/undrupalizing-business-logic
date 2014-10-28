<?php

function quinieleitor_bet_page() {
  $betting_slip = quinieleitor_load_current_slip();
  if ($betting_slip) {
    return drupal_get_form('quinieleitor_bet_form', $betting_slip);
  }

  return 'No betting slips have been created yet';
}


function quinieleitor_bet_form(&$form_state, $betting_slip) {
  $form_state['betting_slip_id'] = $betting_slip->id;
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
  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => 'Bet',
  );

  return $form;
}

/*
function quinieleitor_bet_form_validate($form, &$form_state) {
    $slip = quinieleitor_load_slip($form_state['betting_slip_id']);
    if (!$slip) {
        form_set_error(null, 'The slip does not exist');
        return;
    }
    if ($slip->closed) {
        form_set_error(null, 'The slip is closed');
    }

    return;
}
 */


function quinieleitor_bet_form_submit($form, &$form_state) {
  global $user;
  $bet = new stdClass;
  $bet->matches = array();
  foreach ($form_state['values']['matches'] as $match_id => $prediction) {
    $match = new stdClass;
    $match->user_id = $user->uid;
    $match->match_id = $match_id;
    $match->prediction = $prediction;
    $bet->matches[] = $match;
  }

  quinieleitor_save_bet($bet);
  drupal_set_message('Your bet has been submitted. Good luck!');
}
