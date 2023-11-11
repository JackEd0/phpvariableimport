<?php
/** @var \Individuals\Individual $individual */
/** @var \Template\Core $Template */
/** @var \Template\Core $this */
/** @var \Template\Core $Template */
/** @var \Localization\Core $Locale */
/** @var \Html\Core $Html */
/** @var \Input\Core $Input */
/** @var \Equipments\RIndividual $relation */
/** @var \Equipments\Equipment $equipment */
/** @var \Individuals\Individual $individual */
/** @var \Productions\Production $production */
/** @var \Equipments\Size $size */
/** @var \Equipments\Core $Equipments */
?>
<form name="single" action="<?= $Input->HasGet("action") ? $Http->GetURI(true) : $Http->GetURI() ?>" method="post" data-locked="0" data-unload-message="<?= $Locale->Translate("Vous n'avez pas enregistré vos modifications.") ?>">

    <section class="group no-border no-margin">
        <ul class="alerts columns">
            <?php include $Template->LoadView("includes/alerts.php") ?>
        </ul>
    </section>

    <?php include $Template->LoadView("includes/subnav.php") ?>

    <section class="group">
        <input type="hidden" name="action" value="assign">
        <input type="hidden" name="relation" value="<?= $relation ? $relation->GetID() : null ?>">

        <div class="field" data-size="25">
            <label><?= $Locale->Translate('Individu') ?></label>
            <input type="hidden" name="individual" value="<?= $individual ? $individual->GetID() : null ?>">
            <a data-view data-map="id;name" data-attributes="href;" data-formats="<?= $Template->Link("individuals") ?>{0}/;" href="<?= $individual ? sprintf("%s%d/", $Template->Link("individuals"), $individual->GetID()) : "#" ?>"></a>
            <input data-autocomplete="/individuals/" data-parameter="query" data-name="individual" data-min="<?= \Individuals\Core::FIRSTNAMEMIN ?>" placeholder="<?= $Locale->Translate("Spécifier un individu") ?>" value="<?= $individual ? $individual->GetName() : null ?>">
        </div>

        <div class="field" data-size="25">
            <label><?= $Locale->Translate('Production') ?></label>
            <input type="hidden" name="production" value="<?= $production ? $production->GetID() : null ?>">
            <a data-view data-map="id;name" data-attributes="href;" data-formats="<?= $Template->Link("productions") ?>{0}/;" href="<?= $production ? sprintf("%s%d/", $Template->Link("productions"), $production->GetID()) : "#" ?>"></a>
            <input data-autocomplete="/productions/" data-parameter="query" data-name="production" data-min="<?= \Productions\Core::TITLEMIN ?>" placeholder="<?= $Locale->Translate("Spécifier une production") ?>" value="<?= $production ? $production->GetTitle() : null ?>" >
        </div>

        <div class="field" data-size="25">
            <label><?= $Locale->Translate('Équipement') ?></label>
            <input type="hidden" name="equipment" value="<?= $equipment ? $equipment->GetID() : null ?>">
            <a data-view data-map="id;name" data-attributes="href;" data-formats="<?= $Template->Link("equipments") ?>{0}/;" href="<?= $equipment ? sprintf("%s%d/", $Template->Link("equipments"), $equipment->GetID()) : "#" ?>"></a>
            <input data-autocomplete="/equipments/" data-parameter="query" data-name="equipment" data-min="<?= \Equipments\Core::DESCRIPTIONMIN ?>" placeholder="<?= $Locale->Translate("Spécifier un équipement") ?>" value="<?= $equipment ? $equipment->GetDescription() : null ?>" >
        </div>

        <div class="field" data-size="25">
            <label><?= $Locale->Translate('Grandeur') ?></label>
            <?= $Html->Select("size", $Equipments->GetSizeList(true), $size ? $size->GetId() : 1) ?>
        </div>

        <div class="field" data-size="25">
            <label><?= $Locale->Translate('Quantité') ?></label>
            <input type="number" name="quantity" min="0" value="<?= $relation ? $relation->GetQuantity() : 1 ?>" placeholder="<?= $Locale->Translate("Entrer une quantité") ?>">
        </div>

        <div class="field" data-size="25">
            <label><?= $Locale->Translate("Date Donnée") ?></label>
            <input type="date" name="given_on" value="<?= $relation ? $relation->GetGivenOn() : null ?>" min="1920-01-01" max="2080-01-01">
        </div>

    </section>

    <footer>
        <a href="<?= $Template->Link("equipments") ?>"><?= $Locale->Translate('Annuler') ?></a>
        <input type="submit" value="<?= $equipment ? $Locale->Translate('Sauvegarder') : $Locale->Translate('Créer') ?>">
    </footer>

    <nav data-tabs="eligible" data-tabs-initial="<?= $Session->Fetch("tabs/eligible") ?>">
        <button type="button" data-index="0"><?= $Locale->Translate("Fonctions et départements") ?></button>
    </nav>

    <section data-tab="eligible" data-index="0">
        <?php if ($individual && $individual->IsMember() && !$individual->GetRecognizedFunctions(true, true)) : ?>
        <section class="group summary advertissement">

            <p><?= $Locale->Translate("%s est %s et n’a pas de fonction reconnue.", $individual->GetName(), $individual->GetStatusName()) ?>
            </p>

        </section>
        <?php endif; ?>

        <section class="group container" data-size="100">
            <table name="functions_readonly" data-rows="6" data-width="2" title="<?= $Locale->Translate("Fonctions") ?>">
                <thead>
                    <th data-sorting><?= $Locale->Translate("Fonction") ?></th>
                    <th data-sorting title="<?= $Locale->Translate("Départment") ?>"><?= $Locale->Translate("Dépt.") ?></th>
                    <th data-sorting><?= $Locale->Translate("Reconn.") ?></th>
                    <th data-sorting><?= $Locale->Translate("Reconn. É.-U") ?></th>
                    <th data-sorting><?= $Locale->Translate("Total") ?></th>
                    <th data-sorting><?= $Locale->Translate("Date effective") ?></th>
                </thead>
                <tbody>
                <template>
                    <tr>
                        <td>
                            <span></span>
                        </td>
                        <td>
                            <span></span>
                        </td>
                        <td class="action disabled">
                            <?= $CustomHtml->PhantomCheckbox("functions_type_readonly[]", $Locale->Translate("Fonction reconnue"), false, true) ?>
                        </td>
                        <td class="action disabled">
                            <?= $CustomHtml->PhantomCheckbox("functions_is_recognized_usa_production_readonly[]", $Locale->Translate("Reconnue production américaine"), false, true) ?>
                        </td>
                        <td class="compact">
                            <span></span>
                        </td>
                        <td>
                            <span></span>
                        </td>
                    </tr>
                </template>
                <?php if ($individual) : ?>
                    <?php foreach ($individual->GetFunctions() as $relation => $function) :  ?>
                        <tr>
                            <td>
                                <span><?= $function->GetName() ?></span>
                            </td>
                            <td>
                                <span><?= $function->GetDepartmentCode() ?></span>
                            </td>
                            <td class="action disabled">
                                <?= $CustomHtml->PhantomCheckbox("functions_type_readonly[]", $Locale->Translate("Fonction reconnue"), $function->IsRecognized(), true) ?>
                            </td>
                            <td class="action disabled">
                                <?= $CustomHtml->PhantomCheckbox("functions_is_recognized_usa_production_readonly[]", $Locale->Translate("Reconnue production américaine"), $function->IsRecognizedProductionUSA(), true) ?>
                            </td>
                            <td class="compact">
                                <span><?= $function->GetTotalCredits() ?></span>
                            </td>
                            <td>
                                <span><?= $function->GetEffectiveOn("Y-m-d") ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </section>
    </section>
</form>
