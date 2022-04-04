<?php

class CRM_Randomgrouping_Generator {
  public const MAX_GROUPS = 10;

  public function execute($contactIds, $baseGroupName, $numberOfGroups) {
    $this->deleteGroupIfExists($baseGroupName);
    $groups = $this->getGroupNames($baseGroupName, $numberOfGroups);
    $createdGroups = $this->createGroups($groups);
    $this->fillGroupsRandomly($contactIds, $createdGroups);

    return $groups;
  }

  private function getGroupNames($baseGroupName, $numberOfGroups) {
    $groups = [];

    for ($i = 0; $i < $numberOfGroups; $i++) {
      $letter = chr(65 + $i);
      $groups[] = "{$baseGroupName}_{$letter}";
    }

    return $groups;
  }

  private function deleteGroupIfExists($baseGroupName) {
    $groups = $this->getGroupNames($baseGroupName, self::MAX_GROUPS);
    foreach ($groups as $groupTitle) {
      $this->deleteGroup($groupTitle);
    }
  }

  private function deleteGroup($groupTitle) {
    $sql = "delete from civicrm_group where title = %1";
    $sqlParams = [
      1 => [$groupTitle, 'String'],
    ];
    CRM_Core_DAO::executeQuery($sql, $sqlParams);
  }

  private function createGroups($groups) {
    $createdGroups = [];

    foreach ($groups as $groupTitle) {
      $createdGroups[] = $this->createGroup($groupTitle);
    }

    return $createdGroups;
  }

  private function createGroup($groupTitle) {
    $result = civicrm_api3('Group', 'create', [
      'sequential' => 1,
      'title' => $groupTitle,
    ]);

    if ($result['is_error']) {
      throw new Exception("Kan groep $groupTitle niet aanmaken");
    }

    return $result['values'][0]['id'];
  }

  private function fillGroupsRandomly($contactIds, $createdGroups) {
    shuffle($contactIds);

    $numGroups = count($createdGroups);
    $numSlices = count($contactIds) / $numGroups;
    $indexOfGroupToFill = 0;
    $counter = 0;

    foreach ($contactIds as $contactId) {
      if ($counter >= $numSlices) {
        $indexOfGroupToFill++;
        $counter = 0;
      }

      $this->addContactToGroup($contactId, $createdGroups[$indexOfGroupToFill]);

      $counter++;
    }
  }

  private function addContactToGroup($contactId, $groupId) {
    civicrm_api3('GroupContact', 'create', [
      'group_id' => $groupId,
      'contact_id' => $contactId,
    ]);
  }
}
