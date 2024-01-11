<?php

require_once 'defaultcontributionstatus.civix.php';
// phpcs:disable
use CRM_Defaultcontributionstatus_ExtensionUtil as E;
// phpcs:enable

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function defaultcontributionstatus_civicrm_config(&$config): void {
  _defaultcontributionstatus_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function defaultcontributionstatus_civicrm_install(): void {
  _defaultcontributionstatus_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function defaultcontributionstatus_civicrm_enable(): void {
  _defaultcontributionstatus_civix_civicrm_enable();
}
/**
 * Implements hook_civicrm_buildForm().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_buildForm
 *
 */
function defaultcontributionstatus_civicrm_buildForm($formName, $form) {
  if ($formName == 'CRM_Admin_Form_Options'
    && $form->getVar('_gName') == 'contribution_status'
    && $form->getVar('_action') != CRM_Core_Action::DELETE
  ) {
    $form->assign('showDefault', TRUE);
    $form->add('checkbox', 'is_default', ts('Default Option?'));
  }

  if (in_array($formName, ['CRM_Member_Form_Membership',
    'CRM_Contribute_Form_Contribution', 'CRM_Member_Form_MembershipRenewal',
    'CRM_Event_Form_Participant']) && (($form->getVar('_action') & CRM_Core_Action::ADD)
      || ($form->getVar('_action') & CRM_Core_Action::RENEW)
    )
  ) {
    $defaultContributionStatusId = \Civi\Api4\OptionValue::get(FALSE)
      ->addSelect('value')
      ->addWhere('option_group_id:name', '=', 'contribution_status')
      ->addWhere('is_default', '=', TRUE)
      ->execute()
      ->first()['value'];

    if (!empty($defaultContributionStatusId)) {
      $defaults = ['contribution_status_id' => $defaultContributionStatusId];
      $form->setDefaults($defaults);
    }
  }

}
