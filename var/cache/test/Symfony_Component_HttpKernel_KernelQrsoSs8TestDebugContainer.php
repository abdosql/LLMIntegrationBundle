<?php

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.

if (\class_exists(\ContainerBE9xdLy\Symfony_Component_HttpKernel_KernelQrsoSs8TestDebugContainer::class, false)) {
    // no-op
} elseif (!include __DIR__.'/ContainerBE9xdLy/Symfony_Component_HttpKernel_KernelQrsoSs8TestDebugContainer.php') {
    touch(__DIR__.'/ContainerBE9xdLy.legacy');

    return;
}

if (!\class_exists(Symfony_Component_HttpKernel_KernelQrsoSs8TestDebugContainer::class, false)) {
    \class_alias(\ContainerBE9xdLy\Symfony_Component_HttpKernel_KernelQrsoSs8TestDebugContainer::class, Symfony_Component_HttpKernel_KernelQrsoSs8TestDebugContainer::class, false);
}

return new \ContainerBE9xdLy\Symfony_Component_HttpKernel_KernelQrsoSs8TestDebugContainer([
    'container.build_hash' => 'BE9xdLy',
    'container.build_id' => '21a47ac4',
    'container.build_time' => 1726423254,
    'container.runtime_mode' => \in_array(\PHP_SAPI, ['cli', 'phpdbg', 'embed'], true) ? 'web=0' : 'web=1',
], __DIR__.\DIRECTORY_SEPARATOR.'ContainerBE9xdLy');