<?php

namespace Unterrichtsplanung;

Trait RelationshipTrait
{
    private function getRelLink($slug)
    {
        return \PluginEngine::getLink('unterrichtsplanung/api/' . $slug);
    }
}
