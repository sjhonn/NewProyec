<?php
$action = "/?module=$module&action=" . ($id === null ? 'store' : 'update') . ($id === null ? '' : "&id=$id");
?>
<section class="page-heading">
    <div><a class="back-link" href="/?module=<?= \Acme\Security::e($module) ?>"><i class="fa-solid fa-arrow-left"></i>Volver a <?= \Acme\Security::e(mb_strtolower($config['label'])) ?></a><h1><?= \Acme\Security::e($title) ?></h1><p>Completa la información y guarda los cambios.</p></div>
</section>
<form class="panel resource-form" method="post" action="<?= \Acme\Security::e($action) ?>" novalidate>
    <input type="hidden" name="_token" value="<?= \Acme\Security::e($csrf) ?>">
    <?php if ($errors !== []): ?><div class="alert alert-danger app-alert"><i class="fa-solid fa-circle-exclamation"></i>Revisa los campos marcados.</div><?php endif; ?>
    <div class="form-section-title"><span><i class="fa-solid <?= \Acme\Security::e($config['icon']) ?>"></i></span><div><h2>Información de <?= \Acme\Security::e(mb_strtolower($config['singular'])) ?></h2><p>Los campos con asterisco son obligatorios.</p></div></div>
    <div class="row">
        <?php foreach ($config['fields'] as $field): ?>
            <?php [$name, $label, $type, $required, $choices] = $field; $value = \Acme\Ui::input($record[$name] ?? null, $type); ?>
            <div class="col-md-6 form-group">
                <label for="field_<?= \Acme\Security::e($name) ?>"><?= \Acme\Security::e($label) ?><?= $required ? ' *' : '' ?></label>
                <?php if ($type === 'textarea'): ?><textarea class="form-control <?= isset($errors[$name]) ? 'is-invalid' : '' ?>" id="field_<?= \Acme\Security::e($name) ?>" name="<?= \Acme\Security::e($name) ?>" rows="4"><?= \Acme\Security::e($value) ?></textarea>
                <?php elseif ($type === 'select'): ?><select class="custom-select <?= isset($errors[$name]) ? 'is-invalid' : '' ?>" id="field_<?= \Acme\Security::e($name) ?>" name="<?= \Acme\Security::e($name) ?>" <?= $required ? 'required' : '' ?>><option value="">Seleccionar</option><?php foreach ($choices as $choice): ?><option value="<?= \Acme\Security::e($choice) ?>" <?= $value === $choice ? 'selected' : '' ?>><?= \Acme\Security::e($choice) ?></option><?php endforeach; ?></select>
                <?php elseif ($type === 'relation'): ?><select class="custom-select <?= isset($errors[$name]) ? 'is-invalid' : '' ?>" id="field_<?= \Acme\Security::e($name) ?>" name="<?= \Acme\Security::e($name) ?>" <?= $required ? 'required' : '' ?>><option value="">Sin asignar</option><?php foreach ($options[$name] ?? [] as $option): ?><option value="<?= (int) $option['id'] ?>" <?= (string) $value === (string) $option['id'] ? 'selected' : '' ?>><?= \Acme\Security::e($option['label']) ?></option><?php endforeach; ?></select>
                <?php else: ?><input class="form-control <?= isset($errors[$name]) ? 'is-invalid' : '' ?>" id="field_<?= \Acme\Security::e($name) ?>" type="<?= \Acme\Security::e($type) ?>" name="<?= \Acme\Security::e($name) ?>" value="<?= \Acme\Security::e($value) ?>" <?= $required ? 'required' : '' ?> <?= $type === 'number' ? 'min="0" step="0.01"' : '' ?>>
                <?php endif; ?>
                <?php if (isset($errors[$name])): ?><div class="invalid-feedback"><?= \Acme\Security::e($errors[$name]) ?></div><?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="form-actions"><a class="btn btn-light-outline" href="/?module=<?= \Acme\Security::e($module) ?>">Cancelar</a><button class="btn btn-dark-accent"><i class="fa-solid fa-floppy-disk"></i>Guardar cambios</button></div>
</form>
