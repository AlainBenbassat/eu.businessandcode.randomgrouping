<?php

use CRM_Randomgrouping_ExtensionUtil as E;

class CRM_Randomgrouping_Form_TargetGroups extends CRM_Contact_Form_Task {
  public function buildQuickForm() {
    CRM_Utils_System::setTitle('Stop de geselecteerde contacten in gerandomiseerde groepen');

    $this->addFormItems();
    $this->addFormHelp();

    $this->assign('elementNames', $this->getRenderableElementNames());
    parent::buildQuickForm();
  }

  public function postProcess() {
    try {
      $values = $this->exportValues();

      $targetGroupGenerator = new CRM_Randomgrouping_Generator();
      $createdGroups = $targetGroupGenerator->execute($this->_contactIds, $values['group_name'], $values['number_of_groups']);

      $msg = $this->getSuccessMessage($createdGroups);
      CRM_Core_Session::setStatus($msg, '', 'success');
    }
    catch (Exception $e) {
      CRM_Core_Session::setStatus($e->getMessage(), 'Fout', 'error');
    }

    parent::postProcess();
  }

  private function addFormItems() {
    $defaults = [];

    $this->add('text', 'number_of_selected_contacts', 'Aantal geselecteerde contacten', ['readonly' => TRUE], TRUE);
    $defaults['number_of_selected_contacts'] = count($this->_contactIds);

    $this->add(
      'select',
      'number_of_groups',
      'Aantal groepen',
      $this->getNumberList(),
      TRUE
    );

    $this->add('text', 'group_name', 'Basis groepsnaam', [], TRUE);

    $this->setDefaults($defaults);
  }

  private function addFormHelp() {
    $msg = 'Deze actie zal een aantal groepen aanmaken en zal de geselecteerde contacten op een gerandomiseerde manier verspreiden over deze groepen.<br>'
      . 'Geef het aantal gewenste groepen op, en de basisnaam van de groep<br>'
      . 'Deze actie zal _X (waarbij X = letter van het alfabet) toevoegen achter de basis groepsnaam. (bv. mailing22mei_A, mailing22mei_B)<br><br>'
      . 'BELANGRIJKE OPMERKING: indien de doelgroepen al bestaan, zal de inhoud overschreven worden!';
    CRM_Core_Session::setStatus($msg,'','no-popup');
  }

  private function getSuccessMessage($createdGroups) {
    $msg = 'Volgende groepen zijn aangemaakt:<br>';
    $msg .= '<ul>';

    foreach ($createdGroups as $createdGroup) {
      $msg .= "<li>$createdGroup</li>";
    }

    $msg .= '</ul>';

    return $msg;
  }

  public function validate() {
    $values = $this->exportValues();

    $this->validateNumberOfSelectedContacts($values);
    $this->validateNumberOfGroups($values);

    return parent::validate();
  }

  private function validateNumberOfSelectedContacts($values) {
    if (empty($values['number_of_selected_contacts'])) {
      return;
    }

    if ($values['number_of_selected_contacts'] < 2) {
      $this->setElementError('number_of_selected_contacts', 'Aantal geselecteerde contacten moet minstens 2 zijn');
    }
  }

  private function validateNumberOfGroups($values) {
    if (empty($values['number_of_selected_contacts'])) {
      return;
    }

    if (empty($values['number_of_groups'])) {
      return;
    }

    if ($values['number_of_groups'] > $values['number_of_selected_contacts']) {
      $this->setElementError('number_of_groups', 'Aantal groepen moet kleiner zijn dan aantal geselecteerde contacten');
    }
  }

  private function getNumberList() {
    $numItems = 10;
    $numberList = [];

    for ($i = 2; $i <= $numItems; $i++) {
      $numberList[$i] = $i;
    }

    return $numberList;
  }

  private function getRenderableElementNames() {
    $elementNames = [];
    foreach ($this->_elements as $element) {
      /** @var HTML_QuickForm_Element $element */
      $label = $element->getLabel();
      if (!empty($label)) {
        $elementNames[] = $element->getName();
      }
    }
    return $elementNames;
  }

}
