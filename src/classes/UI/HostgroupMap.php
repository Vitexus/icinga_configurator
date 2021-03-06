<?php

namespace Icinga\Editor\UI;

/**
 * Description of IEHostgroupMap
 *
 * @author vitex
 */
class HostgroupMap extends HostMap {

    /**
     * Skupina hostů
     * @var IEHostgroup
     */
    public $hostgroup = null;

    /**
     * Mapa skupiny hostů
     *
     * @param int $hostgroup_id
     * @param type $directed
     * @param array $attributes
     * @param type $name
     * @param type $strict
     * @param type $returnError
     */
    public function __construct($hostgroup_id, $directed = false,
            $attributes = [], $name = 'G', $strict = true,
            $returnError = false) {
        $this->hostgroup = new Engine\IEHostgroup($hostgroup_id);

        $attributes['rankdir'] = 'LR';
//        $attributes['fontsize'] = '8';
        parent::__construct($directed, $attributes, $name, $strict, $returnError);
    }

    /**
     * Naplní diagram
     */
    function fillUp() {
        $members = $this->hostgroup->getDataValue('members');
        $host = new Engine\IEHost();
        $hosts = $host->getColumnsFromSQL(
                ['alias', 'address', 'parents', 'notifications_enabled', 'active_checks_enabled',
                    'passive_checks_enabled', '3d_coords', $host->myCreateColumn, $host->myLastModifiedColumn,
                    $host->nameColumn, $host->keyColumn],
                'host_id IN ( ' . implode(',', array_keys($members)) . ' )'
        );


        foreach ($hosts as $hostNo => $host_info) {

            if (strstr($host_info['3d_coords'], ',')) {
                list($x, $y, $z) = explode(',', $host_info['3d_coords']);
            } else {
                $x = $y = 0;
            }

            if (strlen(trim($host_info['parents']))) {
                $host_info['parents'] = unserialize($host_info['parents']);
            }
            if (isset($host_info[$host->nameColumn])) {
                $name = $host_info[$host->nameColumn];
            } else {
                continue;
            }
            $alias = $host_info['alias'];
            $color = '';
            if ($host_info['active_checks_enabled']) {
                $color = 'lightgreen';
            }
            if ($host_info['passive_checks_enabled']) {
                $color = 'lightblue';
            }


            $this->addNode($name,
                    [
                        'id' => 'host_' . $host_info[$host->keyColumn],
                        'node_id' => $host_info[$host->keyColumn],
                        'color' => $color,
                        'x' => $x,
                        'y' => $y,
                        'fixed' => boolval($x + $y),
                        'shape' => 'point',
                        'style' => 'filled',
                        'tooltip' => $alias,
                        'label' => $name]
            );

            if (isset($host_info[$host->nameColumn])) {
                if (is_array($host_info['parents'])) {
                    foreach ($host_info['parents'] as $parent_name) {
                        $this->addEdge([$host_info[$host->nameColumn] => $parent_name]);
                    }
                }
            }
        }
    }

}
