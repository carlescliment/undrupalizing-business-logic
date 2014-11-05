<?php

use carlescliment\Quinieleitor\BetterSlip,
    carlescliment\Quinieleitor\Bet;

function quinieleitor_bet_page() {
  $betting_slip = quinieleitor_load_current_slip();
  if ($betting_slip) {
    return drupal_get_form('quinieleitor_bet_form', $betting_slip);
  }

  return 'No betting slips have been created yet';
}


function quinieleitor_hall_of_fame_page() {
    $hall_of_fame = quinieleitor_load_hall_of_fame();
    if (empty($hall_of_fame)) {
        return 'Nobody has earned points yet.';
    }

    $headers = array('Player', 'Points');
    $rows = array();
    foreach ($hall_of_fame as $better) {
        $rows[] = array($better->name, $better->points); 
    }

    return theme_table($headers, $rows);
}


function quinieleitor_bet_form(&$form_state, $betting_slip) {
  $form_state['betting_slip_id'] = $betting_slip->getId();
  $form = array(
    '#prefix' => "<div id='add-slip-{$betting_slip->getId()}'>",
    '#suffix' => "</div>",
  );
  $form['matches'] = array(
    '#tree' => true,
  );
  foreach ($betting_slip->getMatches() as $match) {
    $form['matches'][$match->getId()] = array(
      '#type' => 'textfield',
      '#title' => $match->getName(),
      '#required' => TRUE,
      '#default_value' => isset($form_state['matches'][$match->getId()]) ? $form_state['matches'][$match->getId()] : '',
    );
  }
  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => 'Bet',
  );

  return $form;
}


function quinieleitor_bet_form_validate($form, &$form_state) {
    $slip = quinieleitor_load_slip($form_state['betting_slip_id']);
    if (!$slip) {
        form_set_error(null, 'The slip does not exist');
        return;
    }
    if ($slip->isClosed()) {
        form_set_error(null, 'The slip is closed');
    }

    return;
}


function quinieleitor_bet_form_submit($form, &$form_state) {
  global $user;
  $slip = new BetterSlip($user->uid, null);
  foreach ($form_state['values']['matches'] as $match_id => $prediction) {
    $slip->add(new Bet(null, $match_id, $prediction));
  }

  quinieleitor_save_better_slip($slip);
  drupal_set_message('Your bet has been submitted. Good luck!');
}
