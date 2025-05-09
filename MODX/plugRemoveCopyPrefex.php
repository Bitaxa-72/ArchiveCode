<?php
if ($modx->event->name !== 'OnResourceDuplicate') return;

$newResource = &$scriptProperties['newResource'];

function removeCopyPrefix($value) {
    $prefix = 'Копия ';
    if (mb_substr($value, 0, mb_strlen($prefix)) === $prefix) {
        return mb_substr($value, mb_strlen($prefix));
    }
    return $value;
}

function cleanTitlesRecursively(modResource $resource, modX $modx) {
    $fields = ['pagetitle', 'longtitle', 'menutitle', 'alias'];

    foreach ($fields as $field) {
        $original = $resource->get($field);
        if (!empty($original)) {
            $resource->set($field, removeCopyPrefix($original));
        }
    }

    $resource->save();

    $children = $modx->getCollection('modResource', ['parent' => $resource->get('id')]);
    foreach ($children as $child) {
        cleanTitlesRecursively($child, $modx);
    }
}

cleanTitlesRecursively($newResource, $modx);

// OnResourceDuplicate - включить