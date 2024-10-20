<?php

require_once 'randomgrouping.civix.php';
// phpcs:disable
use CRM_Randomgrouping_ExtensionUtil as E;
// phpcs:enable

function randomgrouping_civicrm_searchTasks( $objectName, &$tasks ) {
  if ($objectName == 'contact'){
    $tasks[] = [
      'title' => 'Maak gerandomiseerde groepen',
      'class' => 'CRM_Randomgrouping_Form_TargetGroups'
    ];
  }
}

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function randomgrouping_civicrm_config(&$config) {
  _randomgrouping_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function randomgrouping_civicrm_install() {
  _randomgrouping_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function randomgrouping_civicrm_enable() {
  _randomgrouping_civix_civicrm_enable();
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_preProcess
 */
//function randomgrouping_civicrm_preProcess($formName, &$form) {
//
//}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu
 */
//function randomgrouping_civicrm_navigationMenu(&$menu) {
//  _randomgrouping_civix_insert_navigation_menu($menu, 'Mailings', array(
//    'label' => E::ts('New subliminal message'),
//    'name' => 'mailing_subliminal_message',
//    'url' => 'civicrm/mailing/subliminal',
//    'permission' => 'access CiviMail',
//    'operator' => 'OR',
//    'separator' => 0,
//  ));
//  _randomgrouping_civix_navigationMenu($menu);
//}
